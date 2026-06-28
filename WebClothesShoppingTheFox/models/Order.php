<?php
/* ==========================================================================
   THE FOX - Model Thao Tác CSDL Đơn Hàng (Order Model)
   Áp dụng chuẩn thiết kế phần mềm Clean Code & Senior Developer
   Tên biến/hàm: Tiếng Anh chuẩn | Chú thích (Comments): Tiếng Việt chuyên nghiệp
   ========================================================================== */

class Order {
    private $databaseConnection;

    public function __construct($databaseConnection) {
        $this->databaseConnection = $databaseConnection;
    }

    // Khởi tạo bản ghi đơn hàng mới trong CSDL
    public function create($orderDataArray) {
        $sqlQuery = "INSERT INTO orders (
                    order_code, user_id, fullname, phone, email, address, 
                    shipping_method, shipping_fee, discount, subtotal, final_total, 
                    payment_method, payment_status, note
                ) VALUES (
                    :order_code, :user_id, :fullname, :phone, :email, :address, 
                    :shipping_method, :shipping_fee, :discount, :subtotal, :final_total, 
                    :payment_method, :payment_status, :note
                )";
        
        $statement = $this->databaseConnection->prepare($sqlQuery);
        $statement->execute([
            ':order_code' => $orderDataArray['order_code'],
            ':user_id' => $orderDataArray['user_id'] ?? null,
            ':fullname' => $orderDataArray['fullname'],
            ':phone' => $orderDataArray['phone'],
            ':email' => $orderDataArray['email'] ?? '',
            ':address' => $orderDataArray['address'],
            ':shipping_method' => $orderDataArray['shipping_method'],
            ':shipping_fee' => $orderDataArray['shipping_fee'],
            ':discount' => $orderDataArray['discount'] ?? 0.00,
            ':subtotal' => $orderDataArray['subtotal'],
            ':final_total' => $orderDataArray['final_total'],
            ':payment_method' => $orderDataArray['payment_method'],
            ':payment_status' => $orderDataArray['payment_status'] ?? 'Chưa thanh toán',
            ':note' => $orderDataArray['note'] ?? ''
        ]);

        return $this->databaseConnection->lastInsertId();
    }

    // Truy xuất toàn bộ danh sách đơn hàng toàn hệ thống (Dành cho Admin)
    public function getAll() {
        $statement = $this->databaseConnection->query("SELECT * FROM orders ORDER BY created_at DESC");
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    // Truy xuất lịch sử danh sách đơn hàng của một người dùng cụ thể
    public function getByUserId($userId) {
        $statement = $this->databaseConnection->prepare("SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC");
        $statement->execute([':user_id' => $userId]);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    // Cập nhật trạng thái xử lý đơn hàng và trạng thái thanh toán tương ứng
    public function updateStatus($orderCode, $orderStatus, $paymentStatus) {
        $statement = $this->databaseConnection->prepare("UPDATE orders SET status = ?, payment_status = ? WHERE order_code = ?");
        $statement->execute([$orderStatus, $paymentStatus, $orderCode]);
        return $statement->rowCount() > 0;
    }
}
?>
