<?php
class Order {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function create($data) {
        $sql = "INSERT INTO orders (
                    order_code, user_id, fullname, phone, email, address, 
                    shipping_method, shipping_fee, discount, subtotal, final_total, 
                    payment_method, payment_status, note
                ) VALUES (
                    :order_code, :user_id, :fullname, :phone, :email, :address, 
                    :shipping_method, :shipping_fee, :discount, :subtotal, :final_total, 
                    :payment_method, :payment_status, :note
                )";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':order_code' => $data['order_code'],
            ':user_id' => $data['user_id'] ?? null,
            ':fullname' => $data['fullname'],
            ':phone' => $data['phone'],
            ':email' => $data['email'] ?? '',
            ':address' => $data['address'],
            ':shipping_method' => $data['shipping_method'],
            ':shipping_fee' => $data['shipping_fee'],
            ':discount' => $data['discount'] ?? 0.00,
            ':subtotal' => $data['subtotal'],
            ':final_total' => $data['final_total'],
            ':payment_method' => $data['payment_method'],
            ':payment_status' => $data['payment_status'] ?? 'Chưa thanh toán',
            ':note' => $data['note'] ?? ''
        ]);

        return $this->db->lastInsertId();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM orders ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($orderCode, $status, $paymentStatus) {
        $stmt = $this->db->prepare("UPDATE orders SET status = ?, payment_status = ? WHERE order_code = ?");
        $stmt->execute([$status, $paymentStatus, $orderCode]);
        return $stmt->rowCount() > 0;
    }
}
?>
