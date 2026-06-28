<?php
class User {
    private $db;

    public function __construct($db_connection) {
        $this->db = $db_connection;
    }

    // Đăng ký tài khoản mới (Mã hóa mật khẩu an toàn)
    public function register($name, $email, $phone, $password) {
        // Kiểm tra xem email đã tồn tại chưa
        $checkQuery = "SELECT id FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($checkQuery);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        if($stmt->rowCount() > 0) return "email_exists";

        // ĐÃ ĐỒNG BỘ: Thêm cột role và avatar mặc định để khớp với cấu trúc Session khi Đăng nhập
        $query = "INSERT INTO users (name, email, phone, password, role, avatar) 
                  VALUES (:name, :email, :phone, :password, 'customer', 'default-avatar.png')";
        $stmt = $this->db->prepare($query);
        
        // Mã hóa mật khẩu trước khi lưu vào DB
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':password', $hashed_password);

        if($stmt->execute()) return true;
        return false;
    }

    // Kiểm tra đăng nhập (Email hoặc SĐT)
    public function login($identity, $password) {
        $query = "SELECT * FROM users WHERE email = :identity OR phone = :identity LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':identity', $identity);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            return $user; // Trả về toàn bộ thông tin của user
        }
        return false;
    }

    // Cập nhật thông tin Profile (User CRUD)
    public function updateProfile($id, $name, $phone, $avatar = null) {
        if ($avatar) {
            $query = "UPDATE users SET name = :name, phone = :phone, avatar = :avatar WHERE id = :id";
        } else {
            $query = "UPDATE users SET name = :name, phone = :phone WHERE id = :id";
        }
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($avatar) $stmt->bindParam(':avatar', $avatar);
        
        return $stmt->execute();
    }
}
?>
