<?php
/* ==========================================================================
   THE FOX - Model Thao Tác CSDL Bộ Phối Đồ (Outfit Model)
   Áp dụng chuẩn thiết kế phần mềm Clean Code & Senior Developer
   Tên biến/hàm: Tiếng Anh chuẩn | Chú thích (Comments): Tiếng Việt chuyên nghiệp
   ========================================================================== */

class Outfit {
    private $databaseConnection;

    public function __construct($databaseConnection) {
        $this->databaseConnection = $databaseConnection;
    }

    // Khởi tạo và lưu bộ phối đồ mới cùng danh sách sản phẩm thành phần
    public function save($userId, $outfitName, $outfitDescription, $productIdsArray) {
        if (empty($productIdsArray)) {
            throw new Exception("Bộ phối đồ phải chứa ít nhất 1 sản phẩm.");
        }

        try {
            // Đảm bảo tính toàn vẹn giao dịch (ACID Transaction): Thêm tiêu đề outfit và lưu các món đồ phải cùng thành công
            $this->databaseConnection->beginTransaction();

            $statementOutfit = $this->databaseConnection->prepare("INSERT INTO outfits (user_id, name, description) VALUES (?, ?, ?)");
            $statementOutfit->execute([$userId, $outfitName, $outfitDescription]);
            $createdOutfitId = $this->databaseConnection->lastInsertId();

            $statementItem = $this->databaseConnection->prepare("INSERT INTO outfit_items (outfit_id, product_id) VALUES (?, ?)");
            foreach ($productIdsArray as $singleProductId) {
                $statementItem->execute([$createdOutfitId, intval($singleProductId)]);
            }

            $this->databaseConnection->commit();
            return $createdOutfitId;
        } catch (Exception $exception) {
            $this->databaseConnection->rollBack();
            throw $exception;
        }
    }

    // Truy xuất tất cả bộ phối đồ đã lưu của người dùng kèm số lượng món đồ trong mỗi bộ
    public function getByUser($userId) {
        $sqlQuery = "SELECT o.id, o.name, o.description, o.created_at, COUNT(oi.id) AS item_count 
                    FROM outfits o
                    LEFT JOIN outfit_items oi ON o.id = oi.outfit_id
                    WHERE o.user_id = ?
                    GROUP BY o.id
                    ORDER BY o.created_at DESC";
        $statement = $this->databaseConnection->prepare($sqlQuery);
        $statement->execute([$userId]);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy chi tiết tiêu đề và mảng danh sách sản phẩm thành phần trong bộ phối đồ
    public function getDetails($outfitId, $userId) {
        // Xác minh bảo mật quyền sở hữu: Đảm bảo người dùng chỉ xem được bộ phối đồ thuộc tài khoản của chính mình
        $statementCheck = $this->databaseConnection->prepare("SELECT id, name, description FROM outfits WHERE id = ? AND user_id = ?");
        $statementCheck->execute([$outfitId, $userId]);
        $outfitData = $statementCheck->fetch(PDO::FETCH_ASSOC);

        if (!$outfitData) {
            return null;
        }

        $sqlQueryItems = "SELECT p.id AS product_id, p.name AS product_name, p.price, p.image, p.description
                        FROM outfit_items oi
                        JOIN products p ON oi.product_id = p.id
                        WHERE oi.outfit_id = ?";
        $statementItems = $this->databaseConnection->prepare($sqlQueryItems);
        $statementItems->execute([$outfitId]);
        $outfitData['items'] = $statementItems->fetchAll(PDO::FETCH_ASSOC);

        return $outfitData;
    }

    // Xóa bộ phối đồ khỏi CSDL dựa trên ID định danh và ID người dùng
    public function delete($outfitId, $userId) {
        $statement = $this->databaseConnection->prepare("DELETE FROM outfits WHERE id = ? AND user_id = ?");
        $statement->execute([$outfitId, $userId]);
        return $statement->rowCount() > 0;
    }
}
?>
