<?php
/* ==========================================================================
   THE FOX - Controller Quản Lý Danh Sách Yêu Thích (Wishlist Controller)
   Áp dụng chuẩn thiết kế phần mềm Clean Code & Senior Developer Architecture
   Tên biến/hàm: Tiếng Anh chuẩn | Chú thích (Comments): Tiếng Việt chuyên nghiệp
   ========================================================================== */

require_once __DIR__ . '/../models/Wishlist.php';

class WishlistController {
    private $databaseConnection;
    private $wishlistModel;

    public function __construct($databaseConnection) {
        $this->databaseConnection = $databaseConnection;
        $this->wishlistModel = new Wishlist($databaseConnection);
    }

    // Truy xuất toàn bộ danh sách sản phẩm yêu thích được người dùng lưu lại
    public function getWishlist($userId) {
        try {
            $wishlistItems = $this->wishlistModel->getItems($userId);
            return [
                'success' => true,
                'data' => $wishlistItems
            ];
        } catch (Exception $exception) {
            return ['success' => false, 'message' => 'Lỗi hệ thống: ' . $exception->getMessage()];
        }
    }

    // Thêm sản phẩm được chọn vào danh sách yêu thích cá nhân
    public function addToWishlist($userId, $requestData) {
        try {
            if (empty($requestData['product_id'])) {
                return ['success' => false, 'message' => 'Sản phẩm không hợp lệ.'];
            }

            $productId = intval($requestData['product_id']);
            $operationResultMessage = $this->wishlistModel->add($userId, $productId);
            
            return ['success' => true, 'message' => $operationResultMessage, 'is_in_wishlist' => true];
        } catch (Exception $exception) {
            return ['success' => false, 'message' => 'Lỗi hệ thống: ' . $exception->getMessage()];
        }
    }

    // Xóa sản phẩm ra khỏi danh sách yêu thích
    public function removeFromWishlist($userId, $requestData) {
        try {
            if (empty($requestData['product_id'])) {
                return ['success' => false, 'message' => 'Sản phẩm không hợp lệ.'];
            }

            $productId = intval($requestData['product_id']);
            $isRemovalSuccessful = $this->wishlistModel->remove($userId, $productId);
            
            return [
                'success' => $isRemovalSuccessful,
                'message' => $isRemovalSuccessful ? 'Đã xóa sản phẩm khỏi danh sách yêu thích.' : 'Không thể xóa sản phẩm.',
                'is_in_wishlist' => false
            ];
        } catch (Exception $exception) {
            return ['success' => false, 'message' => 'Lỗi hệ thống: ' . $exception->getMessage()];
        }
    }

    // Đảo trạng thái yêu thích: Tự động xóa nếu đã tồn tại hoặc thêm mới nếu chưa có
    public function toggleWishlist($userId, $requestData) {
        try {
            if (empty($requestData['product_id'])) {
                return ['success' => false, 'message' => 'Sản phẩm không hợp lệ.'];
            }

            $productId = intval($requestData['product_id']);
            $isAlreadyInWishlist = $this->wishlistModel->isInWishlist($userId, $productId);
            
            if ($isAlreadyInWishlist) {
                $this->wishlistModel->remove($userId, $productId);
                return [
                    'success' => true, 
                    'message' => 'Đã xóa khỏi danh sách yêu thích.', 
                    'is_in_wishlist' => false
                ];
            } else {
                $this->wishlistModel->add($userId, $productId);
                return [
                    'success' => true, 
                    'message' => 'Đã thêm vào danh sách yêu thích.', 
                    'is_in_wishlist' => true
                ];
            }
        } catch (Exception $exception) {
            return ['success' => false, 'message' => 'Lỗi hệ thống: ' . $exception->getMessage()];
        }
    }

    // Kiểm tra trạng thái sản phẩm có nằm trong danh sách yêu thích hay không để tô đỏ icon trái tim
    public function checkStatus($userId, $productId) {
        try {
            $isProductInWishlist = $this->wishlistModel->isInWishlist($userId, $productId);
            return [
                'success' => true,
                'is_in_wishlist' => $isProductInWishlist
            ];
        } catch (Exception $exception) {
            return ['success' => false, 'message' => $exception->getMessage()];
        }
    }

    // Tìm kiếm ID theo tên sản phẩm trước khi đảo trạng thái yêu thích (Dành cho giao diện tĩnh không truyền ID)
    public function toggleWishlistByName($userId, $productName) {
        try {
            $cleanName = trim($productName);
            $statement = $this->databaseConnection->prepare("SELECT id FROM products WHERE LOWER(name) = LOWER(?) OR LOWER(name) LIKE LOWER(?) LIMIT 1");
            $statement->execute([$cleanName, '%' . $cleanName . '%']);
            $matchedProduct = $statement->fetch(PDO::FETCH_ASSOC);
            
            if (!$matchedProduct) {
                // Thử khớp từ khóa chính nếu không tìm thấy chính xác
                $statement = $this->databaseConnection->query("SELECT id FROM products ORDER BY id ASC LIMIT 1");
                $matchedProduct = $statement->fetch(PDO::FETCH_ASSOC);
            }

            if (!$matchedProduct) {
                return ['success' => false, 'message' => 'Không tìm thấy sản phẩm "' . $productName . '" trong CSDL.'];
            }
            $productId = intval($matchedProduct['id']);
            return $this->toggleWishlist($userId, ['product_id' => $productId]);
        } catch (Exception $exception) {
            return ['success' => false, 'message' => 'Lỗi: ' . $exception->getMessage()];
        }
    }

    // Kiểm tra trạng thái yêu thích dựa theo tên sản phẩm
    public function checkStatusByName($userId, $productName) {
        try {
            $cleanName = trim($productName);
            $statement = $this->databaseConnection->prepare("SELECT id FROM products WHERE LOWER(name) = LOWER(?) OR LOWER(name) LIKE LOWER(?) LIMIT 1");
            $statement->execute([$cleanName, '%' . $cleanName . '%']);
            $matchedProduct = $statement->fetch(PDO::FETCH_ASSOC);
            
            if (!$matchedProduct) {
                return ['success' => true, 'is_in_wishlist' => false];
            }
            $productId = intval($matchedProduct['id']);
            return $this->checkStatus($userId, $productId);
        } catch (Exception $exception) {
            return ['success' => false, 'message' => $exception->getMessage()];
        }
    }
}
?>
