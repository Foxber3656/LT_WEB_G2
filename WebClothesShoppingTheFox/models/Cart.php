<?php
/* ==========================================================================
   THE FOX - Model Thao Tác CSDL Giỏ Hàng (Cart Model)
   Áp dụng chuẩn thiết kế phần mềm Clean Code & Senior Developer
   Tên biến/hàm: Tiếng Anh chuẩn | Chú thích (Comments): Tiếng Việt chuyên nghiệp
   ========================================================================== */

class Cart {
    private $databaseConnection;

    public function __construct($databaseConnection) {
        $this->databaseConnection = $databaseConnection;
    }

    // Lấy ID giỏ hàng hiện tại hoặc tự động tạo mới nếu người dùng chưa có giỏ hàng trong CSDL
    private function getOrCreateCartId($userId) {
        $statement = $this->databaseConnection->prepare("SELECT id FROM carts WHERE user_id = ?");
        $statement->execute([$userId]);
        $existingCart = $statement->fetch();

        if ($existingCart) {
            return $existingCart['id'];
        }

        $statement = $this->databaseConnection->prepare("INSERT INTO carts (user_id) VALUES (?)");
        $statement->execute([$userId]);
        return $this->databaseConnection->lastInsertId();
    }

    // Truy xuất toàn bộ mục sản phẩm có trong giỏ hàng kèm thông tin chi tiết tên, giá, hình ảnh
    public function getItems($userId) {
        $cartId = $this->getOrCreateCartId($userId);
        
        $sqlQuery = "SELECT ci.id AS cart_item_id, ci.product_id, p.name AS product_name, 
                       p.price, p.image, ci.color, ci.size, ci.quantity 
                FROM cart_items ci
                JOIN products p ON ci.product_id = p.id
                WHERE ci.cart_id = ?";
        
        $statement = $this->databaseConnection->prepare($sqlQuery);
        $statement->execute([$cartId]);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thêm sản phẩm vào giỏ hàng (Tự động cộng dồn số lượng nếu trùng cả ID, Màu sắc và Kích cỡ)
    public function add($userId, $productId, $selectedColor, $selectedSize, $addedQuantity) {
        $cartId = $this->getOrCreateCartId($userId);

        $statement = $this->databaseConnection->prepare("SELECT id, quantity FROM cart_items WHERE cart_id = ? AND product_id = ? AND color = ? AND size = ?");
        $statement->execute([$cartId, $productId, $selectedColor, $selectedSize]);
        $existingItem = $statement->fetch();

        if ($existingItem) {
            $updatedQuantity = $existingItem['quantity'] + $addedQuantity;
            $statement = $this->databaseConnection->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
            $statement->execute([$updatedQuantity, $existingItem['id']]);
            return "Đã cập nhật số lượng sản phẩm trong giỏ hàng.";
        } else {
            $statement = $this->databaseConnection->prepare("INSERT INTO cart_items (cart_id, product_id, color, size, quantity) VALUES (?, ?, ?, ?, ?)");
            $statement->execute([$cartId, $productId, $selectedColor, $selectedSize, $addedQuantity]);
            return "Đã thêm sản phẩm vào giỏ hàng.";
        }
    }

    // Cập nhật lại số lượng sản phẩm xác định trong giỏ hàng
    public function update($userId, $cartItemId, $newQuantity) {
        $cartId = $this->getOrCreateCartId($userId);
        
        $statement = $this->databaseConnection->prepare("UPDATE cart_items SET quantity = ? WHERE id = ? AND cart_id = ?");
        $statement->execute([$newQuantity, $cartItemId, $cartId]);
        return $statement->rowCount() > 0;
    }

    // Xóa một sản phẩm cụ thể ra khỏi giỏ hàng
    public function remove($userId, $cartItemId) {
        $cartId = $this->getOrCreateCartId($userId);
        
        $statement = $this->databaseConnection->prepare("DELETE FROM cart_items WHERE id = ? AND cart_id = ?");
        $statement->execute([$cartItemId, $cartId]);
        return $statement->rowCount() > 0;
    }

    // Xóa sạch toàn bộ sản phẩm trong giỏ hàng (Thường gọi sau khi hoàn tất thanh toán)
    public function clear($userId) {
        $cartId = $this->getOrCreateCartId($userId);
        $statement = $this->databaseConnection->prepare("DELETE FROM cart_items WHERE cart_id = ?");
        $statement->execute([$cartId]);
        return true;
    }
}
?>
