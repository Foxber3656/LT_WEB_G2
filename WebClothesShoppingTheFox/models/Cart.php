<?php
class Cart {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    private function getOrCreateCartId($userId) {
        $stmt = $this->db->prepare("SELECT id FROM carts WHERE user_id = ?");
        $stmt->execute([$userId]);
        $cart = $stmt->fetch();

        if ($cart) {
            return $cart['id'];
        }

        $stmt = $this->db->prepare("INSERT INTO carts (user_id) VALUES (?)");
        $stmt->execute([$userId]);
        return $this->db->lastInsertId();
    }

    public function getItems($userId) {
        $cartId = $this->getOrCreateCartId($userId);
        
        $sql = "SELECT ci.id AS cart_item_id, ci.product_id, p.name AS product_name, 
                       p.price, p.image, ci.color, ci.size, ci.quantity 
                FROM cart_items ci
                JOIN products p ON ci.product_id = p.id
                WHERE ci.cart_id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$cartId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function add($userId, $productId, $color, $size, $quantity) {
        $cartId = $this->getOrCreateCartId($userId);

        $stmt = $this->db->prepare("SELECT id, quantity FROM cart_items WHERE cart_id = ? AND product_id = ? AND color = ? AND size = ?");
        $stmt->execute([$cartId, $productId, $color, $size]);
        $existingItem = $stmt->fetch();

        if ($existingItem) {
            $newQuantity = $existingItem['quantity'] + $quantity;
            $stmt = $this->db->prepare("UPDATE cart_items SET quantity = ? WHERE id = ?");
            $stmt->execute([$newQuantity, $existingItem['id']]);
            return "Đã cập nhật số lượng sản phẩm trong giỏ hàng.";
        } else {
            $stmt = $this->db->prepare("INSERT INTO cart_items (cart_id, product_id, color, size, quantity) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$cartId, $productId, $color, $size, $quantity]);
            return "Đã thêm sản phẩm vào giỏ hàng.";
        }
    }

    public function update($userId, $cartItemId, $quantity) {
        $cartId = $this->getOrCreateCartId($userId);
        
        $stmt = $this->db->prepare("UPDATE cart_items SET quantity = ? WHERE id = ? AND cart_id = ?");
        $stmt->execute([$quantity, $cartItemId, $cartId]);
        return $stmt->rowCount() > 0;
    }

    public function remove($userId, $cartItemId) {
        $cartId = $this->getOrCreateCartId($userId);
        
        $stmt = $this->db->prepare("DELETE FROM cart_items WHERE id = ? AND cart_id = ?");
        $stmt->execute([$cartItemId, $cartId]);
        return $stmt->rowCount() > 0;
    }

    public function clear($userId) {
        $cartId = $this->getOrCreateCartId($userId);
        $stmt = $this->db->prepare("DELETE FROM cart_items WHERE cart_id = ?");
        $stmt->execute([$cartId]);
        return true;
    }
}
?>
