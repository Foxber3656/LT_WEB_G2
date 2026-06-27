<?php
/* ==========================================================================
   THE FOX - Model Thao Tác CSDL Danh Sách Yêu Thích (Wishlist Model)
   Áp dụng chuẩn thiết kế phần mềm Clean Code & Senior Developer
   Tên biến/hàm: Tiếng Anh chuẩn | Chú thích (Comments): Tiếng Việt chuyên nghiệp
   ========================================================================== */

class Wishlist {
    private $databaseConnection;

    public function __construct($databaseConnection) {
        $this->databaseConnection = $databaseConnection;
    }

    // Truy xuất danh sách chi tiết các sản phẩm yêu thích được người dùng lưu lại
    public function getItems($userId) {
        $sqlQuery = "SELECT w.id AS wishlist_id, w.product_id, p.name AS product_name, 
                       p.price, p.image, p.description
                FROM wishlists w
                JOIN products p ON w.product_id = p.id
                WHERE w.user_id = ?
                ORDER BY w.created_at DESC";
        $statement = $this->databaseConnection->prepare($sqlQuery);
        $statement->execute([$userId]);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thêm một sản phẩm mới vào danh sách yêu thích cá nhân
    public function add($userId, $productId) {
        // Kiểm tra tránh ghi trùng dữ liệu nếu sản phẩm đã nằm trong danh sách từ trước
        if ($this->isInWishlist($userId, $productId)) {
            return "Sản phẩm đã có trong danh sách yêu thích.";
        }

        $statement = $this->databaseConnection->prepare("INSERT INTO wishlists (user_id, product_id) VALUES (?, ?)");
        $statement->execute([$userId, $productId]);
        return "Đã thêm sản phẩm vào danh sách yêu thích.";
    }

    // Xóa sản phẩm ra khỏi danh sách yêu thích
    public function remove($userId, $productId) {
        $statement = $this->databaseConnection->prepare("DELETE FROM wishlists WHERE user_id = ? AND product_id = ?");
        $statement->execute([$userId, $productId]);
        return $statement->rowCount() > 0;
    }

    // Kiểm tra nhanh sự tồn tại của sản phẩm trong danh sách yêu thích
    public function isInWishlist($userId, $productId) {
        $statement = $this->databaseConnection->prepare("SELECT id FROM wishlists WHERE user_id = ? AND product_id = ?");
        $statement->execute([$userId, $productId]);
        return (bool)$statement->fetch();
    }
}
?>
