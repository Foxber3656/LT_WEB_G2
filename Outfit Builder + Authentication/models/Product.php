<?php
// models/Product.php
class Product {
    private $db;

    public function __construct($db_connection) {
        $this->db = $db_connection;
    }

    // Lấy danh sách sản phẩm theo loại (để phân loại Áo / Quần / Phụ kiện trong Outfit Builder)
    // Giả định bảng 'products' của nhóm có cột 'category_id' hoặc 'type' ('top', 'bottom', 'accessory')
    public function getProductsByType($type) {
        $query = "SELECT id, name, price, image FROM products WHERE type = :type";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':type', $type);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --- LOGIC WISHLIST ---
    public function addToWishlist($user_id, $product_id) {
        // Kiểm tra xem đã tồn tại trong wishlist chưa
        $check = "SELECT id FROM wishlist WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $this->db->prepare($check);
        $stmt->bindValue(':user_id', $user_id);
        $stmt->bindValue(':product_id', $product_id);
        $stmt->execute();
        if($stmt->rowCount() > 0) return "exists";

        $query = "INSERT INTO wishlist (user_id, product_id) VALUES (:user_id, :product_id)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':user_id', $user_id);
        $stmt->bindValue(':product_id', $product_id);
        return $stmt->execute();
    }

    // --- LOGIC SAVE OUTFIT ---
    public function saveOutfit($user_id, $name, $top_id, $bottom_id, $accessory_id = null) {
        $query = "INSERT INTO outfits (user_id, outfit_name, top_product_id, bottom_product_id, accessory_id) 
                  VALUES (:user_id, :name, :top_id, :bottom_id, :accessory_id)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':user_id', $user_id);
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':top_id', $top_id);
        $stmt->bindValue(':bottom_id', $bottom_id);
        $stmt->bindValue(':accessory_id', $accessory_id);
        return $stmt->execute();
    }
    // Hàm lấy danh sách các bộ phối đồ của người dùng hiện tại (Đã fix lỗi sai tên cột)
    public function getUserOutfits($user_id) {
        $sql = "SELECT o.*, 
                       p1.name as top_name, p1.image as top_image, p1.price as top_price,
                       p2.name as bottom_name, p2.image as bottom_image, p2.price as bottom_price
                FROM outfits o
                LEFT JOIN products p1 ON o.top_product_id = p1.id
                LEFT JOIN products p2 ON o.bottom_product_id = p2.id
                WHERE o.user_id = ? 
                ORDER BY o.created_at DESC";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$user_id]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
