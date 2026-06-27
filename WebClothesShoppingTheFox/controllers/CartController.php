<?php
/* ==========================================================================
   THE FOX - Controller Quản Lý Giỏ Hàng (Cart Controller)
   Áp dụng chuẩn thiết kế phần mềm Clean Code & Senior Developer
   Tên biến/hàm: Tiếng Anh chuẩn | Chú thích (Comments): Tiếng Việt chuyên nghiệp
   ========================================================================== */

require_once __DIR__ . '/../models/Cart.php';

class CartController {
    private $databaseConnection;
    private $cartModel;

    public function __construct($databaseConnection) {
        $this->databaseConnection = $databaseConnection;
        $this->cartModel = new Cart($databaseConnection);
    }

    // Truy xuất danh sách sản phẩm hiện có trong giỏ hàng lưu trữ ở CSDL của người dùng
    public function getCart($userId) {
        try {
            $cartItems = $this->cartModel->getItems($userId);
            return [
                'success' => true,
                'data' => $cartItems
            ];
        } catch (Exception $exception) {
            return ['success' => false, 'message' => 'Lỗi hệ thống: ' . $exception->getMessage()];
        }
    }

    // Thêm sản phẩm mới hoặc tăng số lượng nếu sản phẩm cùng thuộc tính đã tồn tại trong giỏ
    public function addToCart($userId, $requestData) {
        try {
            // Kiểm tra tính đầy đủ của thuộc tính bắt buộc nhằm bảo đảm dữ liệu đơn hàng hợp lệ
            if (empty($requestData['product_id']) || empty($requestData['color']) || empty($requestData['size']) || empty($requestData['quantity'])) {
                return ['success' => false, 'message' => 'Dữ liệu đầu vào không hợp lệ'];
            }

            $productId = intval($requestData['product_id']);
            $selectedColor = trim($requestData['color']);
            $selectedSize = trim($requestData['size']);
            $itemQuantity = intval($requestData['quantity']);

            $operationResultMessage = $this->cartModel->add($userId, $productId, $selectedColor, $selectedSize, $itemQuantity);
            return ['success' => true, 'message' => $operationResultMessage];
        } catch (Exception $exception) {
            return ['success' => false, 'message' => 'Lỗi hệ thống: ' . $exception->getMessage()];
        }
    }

    // Cập nhật lại số lượng mua của một mục sản phẩm xác định trong giỏ hàng
    public function updateQuantity($userId, $requestData) {
        try {
            if (empty($requestData['cart_item_id']) || empty($requestData['quantity'])) {
                return ['success' => false, 'message' => 'Dữ liệu đầu vào không hợp lệ'];
            }

            $cartItemId = intval($requestData['cart_item_id']);
            $newQuantity = intval($requestData['quantity']);

            // Ràng buộc số lượng dương tối thiểu để tránh số lượng âm hoặc bằng 0 sai logic kinh doanh
            if ($newQuantity <= 0) {
                return ['success' => false, 'message' => 'Số lượng phải lớn hơn 0'];
            }

            $isUpdateSuccessful = $this->cartModel->update($userId, $cartItemId, $newQuantity);
            return [
                'success' => $isUpdateSuccessful, 
                'message' => $isUpdateSuccessful ? 'Đã cập nhật số lượng sản phẩm.' : 'Không thể cập nhật số lượng.'
            ];
        } catch (Exception $exception) {
            return ['success' => false, 'message' => 'Lỗi hệ thống: ' . $exception->getMessage()];
        }
    }

    // Loại bỏ hoàn toàn một sản phẩm ra khỏi giỏ hàng
    public function removeItem($userId, $requestData) {
        try {
            if (empty($requestData['cart_item_id'])) {
                return ['success' => false, 'message' => 'Sản phẩm không hợp lệ'];
            }

            $cartItemId = intval($requestData['cart_item_id']);

            $isRemovalSuccessful = $this->cartModel->remove($userId, $cartItemId);
            return [
                'success' => $isRemovalSuccessful, 
                'message' => $isRemovalSuccessful ? 'Đã xóa sản phẩm khỏi giỏ hàng.' : 'Không thể xóa sản phẩm.'
            ];
        } catch (Exception $exception) {
            return ['success' => false, 'message' => 'Lỗi hệ thống: ' . $exception->getMessage()];
        }
    }
}
?>
