<?php
class OrderDetail {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function create($orderId, $itemData) {
        $sql = "INSERT INTO order_items (
                    order_id, product_id, product_name, color, size, price, quantity
                ) VALUES (
                    :order_id, :product_id, :product_name, :color, :size, :price, :quantity
                )";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':order_id' => $orderId,
            ':product_id' => $itemData['product_id'] ?? null,
            ':product_name' => $itemData['product_name'],
            ':color' => $itemData['color'],
            ':size' => $itemData['size'],
            ':price' => $itemData['price'],
            ':quantity' => $itemData['quantity']
        ]);
        
        return $this->db->lastInsertId();
    }

    public function getItemsByOrderId($orderId) {
        $stmt = $this->db->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
