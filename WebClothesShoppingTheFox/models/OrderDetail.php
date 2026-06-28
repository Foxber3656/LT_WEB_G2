<?php
/* ==========================================================================
   THE FOX - Model Thao Tác CSDL Chi Tiết Đơn Hàng (OrderDetail Model)
   Áp dụng chuẩn thiết kế phần mềm Clean Code & Senior Developer
   Tên biến/hàm: Tiếng Anh chuẩn | Chú thích (Comments): Tiếng Việt chuyên nghiệp
   ========================================================================== */

class OrderDetail {
    private $databaseConnection;

    public function __construct($databaseConnection) {
        $this->databaseConnection = $databaseConnection;
    }

    // Lưu từng mục sản phẩm thành phần thuộc về một đơn hàng xác định
    public function create($orderId, $itemDataArray) {
        $sqlQuery = "INSERT INTO order_items (
                    order_id, product_id, product_name, color, size, price, quantity
                ) VALUES (
                    :order_id, :product_id, :product_name, :color, :size, :price, :quantity
                )";
        
        $statement = $this->databaseConnection->prepare($sqlQuery);
        $statement->execute([
            ':order_id' => $orderId,
            ':product_id' => $itemDataArray['product_id'] ?? null,
            ':product_name' => $itemDataArray['product_name'],
            ':color' => $itemDataArray['color'],
            ':size' => $itemDataArray['size'],
            ':price' => $itemDataArray['price'],
            ':quantity' => $itemDataArray['quantity']
        ]);
        
        return $this->databaseConnection->lastInsertId();
    }

    // Truy xuất danh sách sản phẩm nằm trong một đơn hàng dựa vào mã ID đơn hàng
    public function getItemsByOrderId($orderId) {
        $statement = $this->databaseConnection->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $statement->execute([$orderId]);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
