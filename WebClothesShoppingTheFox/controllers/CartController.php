<?php
require_once __DIR__ . '/../models/Cart.php';

class CartController {
    private $db;
    private $cartModel;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
        $this->cartModel = new Cart($dbConnection);
    }

    public function getCart($userId) {
        try {
            $items = $this->cartModel->getItems($userId);
            return [
                'success' => true,
                'data' => $items
            ];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()];
        }
    }

    public function addToCart($userId, $request) {
        try {
            if (empty($request['product_id']) || empty($request['color']) || empty($request['size']) || empty($request['quantity'])) {
                return ['success' => false, 'message' => 'Dữ liệu đầu vào không hợp lệ'];
            }

            $productId = intval($request['product_id']);
            $color = trim($request['color']);
            $size = trim($request['size']);
            $quantity = intval($request['quantity']);

            $message = $this->cartModel->add($userId, $productId, $color, $size, $quantity);
            return ['success' => true, 'message' => $message];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()];
        }
    }

    public function updateQuantity($userId, $request) {
        try {
            if (empty($request['cart_item_id']) || empty($request['quantity'])) {
                return ['success' => false, 'message' => 'Dữ liệu đầu vào không hợp lệ'];
            }

            $cartItemId = intval($request['cart_item_id']);
            $quantity = intval($request['quantity']);

            if ($quantity <= 0) {
                return ['success' => false, 'message' => 'Số lượng phải lớn hơn 0'];
            }

            $success = $this->cartModel->update($userId, $cartItemId, $quantity);
            return ['success' => $success, 'message' => $success ? 'Đã cập nhật số lượng sản phẩm.' : 'Không thể cập nhật số lượng.'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()];
        }
    }

    public function removeItem($userId, $request) {
        try {
            if (empty($request['cart_item_id'])) {
                return ['success' => false, 'message' => 'Sản phẩm không hợp lệ'];
            }

            $cartItemId = intval($request['cart_item_id']);

            $success = $this->cartModel->remove($userId, $cartItemId);
            return ['success' => $success, 'message' => $success ? 'Đã xóa sản phẩm khỏi giỏ hàng.' : 'Không thể xóa sản phẩm.'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()];
        }
    }
}
?>
