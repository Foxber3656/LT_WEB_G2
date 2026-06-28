<?php
/* ==========================================================================
   THE FOX - Controller Quản Lý Đơn Hàng & Thanh Toán (Order Controller)
   Áp dụng chuẩn thiết kế phần mềm Clean Code & Senior Developer
   Tên biến/hàm: Tiếng Anh chuẩn | Chú thích (Comments): Tiếng Việt chuyên nghiệp
   ========================================================================== */

require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/OrderDetail.php';
require_once __DIR__ . '/../models/Cart.php';

class OrderController {
    private $databaseConnection;
    private $orderModel;
    private $orderDetailModel;

    public function __construct($databaseConnection) {
        $this->databaseConnection = $databaseConnection;
        $this->orderModel = new Order($databaseConnection);
        $this->orderDetailModel = new OrderDetail($databaseConnection);
    }

    // Xử lý quy trình đặt hàng và tạo giao dịch thanh toán (Checkout Transaction)
    public function checkout($requestData) {
        try {
            // Ràng buộc dữ liệu bắt buộc để đảm bảo đơn hàng đủ thông tin giao nhận
            if (empty($requestData['fullname']) || empty($requestData['phone']) || empty($requestData['address']) || empty($requestData['items'])) {
                return ['success' => false, 'message' => 'Vui lòng cung cấp đầy đủ thông tin giao hàng và sản phẩm.'];
            }

            $orderSubtotal = 0;
            foreach ($requestData['items'] as $cartItem) {
                $orderSubtotal += $cartItem['price'] * $cartItem['quantity'];
            }

            // Quy tắc chiết khấu nghiệp vụ: Tự động giảm 100.000đ cho đơn hàng mua sắm từ 1.000.000đ
            $discountAmount = $orderSubtotal >= 1000000 ? 100000 : 0;
            $shippingFee = isset($requestData['shipping_fee']) ? (int)$requestData['shipping_fee'] : 0;
            $finalTotalAmount = $orderSubtotal + $shippingFee - $discountAmount;

            $generatedOrderCode = 'FOX' . rand(100000, 999999);
            $initialPaymentStatus = $requestData['payment_method'] === 'COD' ? 'Chưa thanh toán' : 'Đã thanh toán';

            // Sử dụng Database Transaction để bảo đảm tính toàn vẹn dữ liệu (ACID): Tạo đơn hàng và tạo các chi tiết đơn hàng phải cùng thành công
            $this->databaseConnection->beginTransaction();

            $formattedOrderData = [
                'order_code' => $generatedOrderCode,
                'user_id' => $requestData['user_id'] ?? null,
                'fullname' => $requestData['fullname'],
                'phone' => $requestData['phone'],
                'email' => $requestData['email'] ?? '',
                'address' => $requestData['address'],
                'shipping_method' => $requestData['shipping_method'] ?? 'Tiêu chuẩn',
                'shipping_fee' => $shippingFee,
                'discount' => $discountAmount,
                'subtotal' => $orderSubtotal,
                'final_total' => $finalTotalAmount,
                'payment_method' => $requestData['payment_method'],
                'payment_status' => $initialPaymentStatus,
                'note' => $requestData['note'] ?? ''
            ];

            $createdOrderId = $this->orderModel->create($formattedOrderData);

            foreach ($requestData['items'] as $cartItem) {
                $orderItemData = [
                    'product_id' => $cartItem['product_id'] ?? null,
                    'product_name' => $cartItem['product_name'],
                    'color' => $cartItem['color'],
                    'size' => $cartItem['size'],
                    'price' => $cartItem['price'],
                    'quantity' => $cartItem['quantity']
                ];
                $this->orderDetailModel->create($createdOrderId, $orderItemData);
            }

            // Xóa sạch giỏ hàng của người dùng sau khi đặt hàng thành công
            if (!empty($requestData['user_id'])) {
                $userCartModel = new Cart($this->databaseConnection);
                $userCartModel->clear($requestData['user_id']);
            }

            $this->databaseConnection->commit();
            return [
                'success' => true, 
                'order_code' => $generatedOrderCode,
                'message' => 'Đặt hàng thành công!'
            ];

        } catch (Exception $exception) {
            // Hoàn tác dữ liệu (Rollback) nếu xảy ra bất kỳ lỗi hệ thống nào trong quá trình xử lý chuỗi giao dịch
            if ($this->databaseConnection->inTransaction()) {
                $this->databaseConnection->rollBack();
            }
            return ['success' => false, 'message' => 'Lỗi hệ thống: ' . $exception->getMessage()];
        }
    }

    // Truy xuất lịch sử đơn hàng phân quyền dựa trên Vai trò người dùng (RBAC)
    public function getOrdersByUser($userId, $userRole = 'user') {
        try {
            // Phân quyền dữ liệu: Tài khoản Admin xem toàn bộ hệ thống đơn hàng, tài khoản User chỉ xem được đơn hàng chính mình
            if ($userRole === 'admin') {
                $userOrdersList = $this->orderModel->getAll();
            } else {
                $userOrdersList = $this->orderModel->getByUserId($userId);
            }

            foreach ($userOrdersList as &$singleOrder) {
                $singleOrder['items'] = $this->orderDetailModel->getItemsByOrderId($singleOrder['id']);
            }

            return ['success' => true, 'orders' => $userOrdersList];
        } catch (Exception $exception) {
            return ['success' => false, 'message' => $exception->getMessage()];
        }
    }

    // Cập nhật trạng thái đơn hàng (Dành cho Admin hoặc Giả lập trạng thái)
    public function updateStatus($requestData) {
        try {
            if (empty($requestData['order_code']) || empty($requestData['status'])) {
                return ['success' => false, 'message' => 'Thiếu thông tin cập nhật.'];
            }
            
            $updatedOrderStatus = $requestData['status'];
            $updatedPaymentStatus = $updatedOrderStatus === 'Đã hoàn thành' ? 'Đã thanh toán' : 'Chưa thanh toán';
            
            $isStatusUpdateSuccessful = $this->orderModel->updateStatus($requestData['order_code'], $updatedOrderStatus, $updatedPaymentStatus);
            
            return [
                'success' => $isStatusUpdateSuccessful, 
                'message' => $isStatusUpdateSuccessful ? 'Cập nhật trạng thái thành công!' : 'Không tìm thấy đơn hàng.'
            ];
        } catch (Exception $exception) {
            return ['success' => false, 'message' => $exception->getMessage()];
        }
    }
}
?>
