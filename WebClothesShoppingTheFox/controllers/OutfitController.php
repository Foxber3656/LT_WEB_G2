<?php
/* ==========================================================================
   THE FOX - Controller Quản Lý Bộ Phối Đồ (Outfit Controller)
   Áp dụng chuẩn thiết kế phần mềm Clean Code & Senior Developer
   Tên biến/hàm: Tiếng Anh chuẩn | Chú thích (Comments): Tiếng Việt chuyên nghiệp
   ========================================================================== */

require_once __DIR__ . '/../models/Outfit.php';

class OutfitController {
    private $databaseConnection;
    private $outfitModel;

    public function __construct($databaseConnection) {
        $this->databaseConnection = $databaseConnection;
        $this->outfitModel = new Outfit($databaseConnection);
    }

    // Khởi tạo và lưu trữ bộ phối đồ thời trang do người dùng tự phối
    public function saveOutfit($userId, $requestData) {
        try {
            // Xác thực dữ liệu đầu vào bắt buộc để tránh tạo bộ phối đồ rỗng gây lỗi hiển thị
            if (empty($requestData['name']) || empty($requestData['product_ids'])) {
                return ['success' => false, 'message' => 'Vui lòng cung cấp tên bộ phối đồ và danh sách sản phẩm.'];
            }

            $outfitName = trim($requestData['name']);
            $outfitDescription = isset($requestData['description']) ? trim($requestData['description']) : '';
            $productIds = $requestData['product_ids'];

            $createdOutfitId = $this->outfitModel->save($userId, $outfitName, $outfitDescription, $productIds);
            return [
                'success' => true,
                'message' => 'Lưu bộ phối đồ thành công!',
                'outfit_id' => $createdOutfitId
            ];
        } catch (Exception $exception) {
            return ['success' => false, 'message' => 'Lỗi hệ thống: ' . $exception->getMessage()];
        }
    }

    // Truy xuất danh sách các bộ phối đồ đã lưu thuộc về tài khoản hiện tại
    public function getUserOutfits($userId) {
        try {
            $userOutfits = $this->outfitModel->getByUser($userId);
            return [
                'success' => true,
                'data' => $userOutfits
            ];
        } catch (Exception $exception) {
            return ['success' => false, 'message' => 'Lỗi hệ thống: ' . $exception->getMessage()];
        }
    }

    // Lấy thông tin chi tiết kèm danh sách các sản phẩm thành phần của bộ phối đồ
    public function getOutfitDetails($userId, $outfitId) {
        try {
            // Kiểm tra quyền sở hữu đối tượng nhằm đảm bảo người dùng khác không truy cập được outfit riêng tư
            $outfitDetails = $this->outfitModel->getDetails($outfitId, $userId);
            if ($outfitDetails) {
                return [
                    'success' => true,
                    'data' => $outfitDetails
                ];
            } else {
                return ['success' => false, 'message' => 'Không tìm thấy bộ phối đồ hoặc không có quyền xem.'];
            }
        } catch (Exception $exception) {
            return ['success' => false, 'message' => 'Lỗi hệ thống: ' . $exception->getMessage()];
        }
    }

    // Xóa bộ phối đồ khỏi bộ sưu tập cá nhân của người dùng
    public function deleteOutfit($userId, $outfitId) {
        try {
            $isDeletionSuccessful = $this->outfitModel->delete($outfitId, $userId);
            return [
                'success' => $isDeletionSuccessful,
                'message' => $isDeletionSuccessful ? 'Đã xóa bộ phối đồ thành công.' : 'Không thể xóa bộ phối đồ.'
            ];
        } catch (Exception $exception) {
            return ['success' => false, 'message' => 'Lỗi hệ thống: ' . $exception->getMessage()];
        }
    }
}
?>
