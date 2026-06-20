<?php
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/OrderDetail.php';
require_once __DIR__ . '/../models/Cart.php';

class OrderController {
    private $db;
    private $orderModel;
    private $orderDetailModel;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
        $this->orderModel = new Order($dbConnection);
        $this->orderDetailModel = new OrderDetail($dbConnection);
    }

    public function checkout($request) {
        try {
            if (empty($request['fullname']) || empty($request['phone']) || empty($request['address']) || empty($request['items'])) {
                return ['success' => false, 'message' => 'Vui lòng cung cấp đầy đủ thông tin giao hàng và sản phẩm.'];
            }

            $subtotal = 0;
            foreach ($request['items'] as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }

            $discount = $subtotal >= 1000000 ? 100000 : 0;
            $shipping_fee = isset($request['shipping_fee']) ? (int)$request['shipping_fee'] : 0;
            $final_total = $subtotal + $shipping_fee - $discount;

            $order_code = 'FOX' . rand(100000, 999999);
            $payment_status = $request['payment_method'] === 'COD' ? 'Chưa thanh toán' : 'Đã thanh toán';

            $this->db->beginTransaction();

            $orderData = [
                'order_code' => $order_code,
                'user_id' => $request['user_id'] ?? null,
                'fullname' => $request['fullname'],
                'phone' => $request['phone'],
                'email' => $request['email'] ?? '',
                'address' => $request['address'],
                'shipping_method' => $request['shipping_method'] ?? 'Tiêu chuẩn',
                'shipping_fee' => $shipping_fee,
                'discount' => $discount,
                'subtotal' => $subtotal,
                'final_total' => $final_total,
                'payment_method' => $request['payment_method'],
                'payment_status' => $payment_status,
                'note' => $request['note'] ?? ''
            ];

            $orderId = $this->orderModel->create($orderData);

            foreach ($request['items'] as $item) {
                $itemData = [
                    'product_id' => $item['product_id'] ?? null,
                    'product_name' => $item['product_name'],
                    'color' => $item['color'],
                    'size' => $item['size'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity']
                ];
                $this->orderDetailModel->create($orderId, $itemData);
            }

            if (!empty($request['user_id'])) {
                $cartModel = new Cart($this->db);
                $cartModel->clear($request['user_id']);
            }

            $this->db->commit();
            return [
                'success' => true, 
                'order_code' => $order_code,
                'message' => 'Đặt hàng thành công!'
            ];

        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            return ['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()];
        }
    }

    public function getOrders() {
        try {
            $orders = $this->orderModel->getAll();
            
            foreach ($orders as &$order) {
                $order['items'] = $this->orderDetailModel->getItemsByOrderId($order['id']);
            }
            
            return ['success' => true, 'orders' => $orders];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function updateStatus($request) {
        try {
            if (empty($request['order_code']) || empty($request['status'])) {
                return ['success' => false, 'message' => 'Thiếu thông tin cập nhật.'];
            }
            
            $status = $request['status'];
            $paymentStatus = $status === 'Đã hoàn thành' ? 'Đã thanh toán' : 'Chưa thanh toán';
            
            $success = $this->orderModel->updateStatus($request['order_code'], $status, $paymentStatus);
            
            return ['success' => $success, 'message' => $success ? 'Cập nhật trạng thái thành công!' : 'Không tìm thấy đơn hàng.'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
?>
