<?php
/* ==========================================================================
   THE FOX - Model Thao Tác CSDL Người Dùng (User Model)
   Áp dụng chuẩn thiết kế phần mềm Clean Code & Senior Developer
   Tên biến/hàm: Tiếng Anh chuẩn | Chú thích (Comments): Tiếng Việt chuyên nghiệp
   ========================================================================== */

class User {
    private $databaseConnection;

    public function __construct($databaseConnection) {
        $this->databaseConnection = $databaseConnection;
    }

    // Đăng ký tài khoản người dùng mới vào hệ thống
    public function register($fullname, $emailAddress, $phoneNumber, $plainPassword) {
        // Ràng buộc duy nhất (Unique constraint): Kiểm tra Email đã được đăng ký chưa để tránh trùng lặp tài khoản
        if ($this->getByEmail($emailAddress)) {
            throw new Exception("Email đã được sử dụng bởi tài khoản khác.");
        }

        // Áp dụng thuật toán băm Bcrypt đạt chuẩn bảo mật cao để lưu trữ mật khẩu an toàn trong CSDL
        $hashedPassword = password_hash($plainPassword, PASSWORD_BCRYPT);

        // Mặc định phân quyền tài khoản mới tạo là khách hàng ('user')
        $statement = $this->databaseConnection->prepare("INSERT INTO users (fullname, email, phone, password, role) VALUES (?, ?, ?, ?, 'user')");
        $statement->execute([$fullname, $emailAddress, $phoneNumber, $hashedPassword]);
        return $this->databaseConnection->lastInsertId();
    }

    // Thực hiện xác thực đăng nhập người dùng
    public function login($emailAddress, $plainPassword) {
        $userData = $this->getByEmail($emailAddress);
        if (!$userData) {
            return false;
        }

        // Xác minh mật khẩu nhập vào với chuỗi băm lưu trong CSDL
        if (password_verify($plainPassword, $userData['password'])) {
            // Nguyên tắc tối thiểu dữ liệu (Data Minimization): Xóa thông tin mật khẩu khỏi mảng dữ liệu trước khi trả về Frontend
            unset($userData['password']);
            return $userData;
        }

        return false;
    }

    // Truy vấn thông tin người dùng dựa trên địa chỉ Email
    public function getByEmail($emailAddress) {
        $statement = $this->databaseConnection->prepare("SELECT * FROM users WHERE email = ?");
        $statement->execute([$emailAddress]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    // Truy vấn hồ sơ chi tiết của người dùng dựa trên ID định danh
    public function getById($userId) {
        // Tự động bảo trì schema (Auto Migration): Đảm bảo các cột thông tin mới luôn tồn tại trong bảng mà không làm gãy ứng dụng
        try { $this->databaseConnection->exec("ALTER TABLE users ADD COLUMN gender VARCHAR(20) NULL;"); } catch (Exception $exception) {}
        try { $this->databaseConnection->exec("ALTER TABLE users ADD COLUMN birthday DATE NULL;"); } catch (Exception $exception) {}
        try { $this->databaseConnection->exec("ALTER TABLE users ADD COLUMN bio TEXT NULL;"); } catch (Exception $exception) {}
        try { $this->databaseConnection->exec("ALTER TABLE users ADD COLUMN cccd_fullname VARCHAR(100) NULL;"); } catch (Exception $exception) {}
        try { $this->databaseConnection->exec("ALTER TABLE users ADD COLUMN cccd_number VARCHAR(50) NULL;"); } catch (Exception $exception) {}
        try { $this->databaseConnection->exec("ALTER TABLE users ADD COLUMN cccd_address TEXT NULL;"); } catch (Exception $exception) {}

        $statement = $this->databaseConnection->prepare("SELECT id, fullname, email, phone, address, avatar, gender, birthday, bio, cccd_fullname, cccd_number, cccd_address, role, created_at FROM users WHERE id = ?");
        $statement->execute([$userId]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    // Cập nhật dữ liệu Căn cước công dân (CCCD) phục vụ định danh khách hàng
    public function updateCccd($userId, $cccdFullname, $cccdNumber, $cccdAddress) {
        $statement = $this->databaseConnection->prepare("UPDATE users SET cccd_fullname = ?, cccd_number = ?, cccd_address = ? WHERE id = ?");
        return $statement->execute([$cccdFullname, $cccdNumber, $cccdAddress, $userId]);
    }

    // Truy xuất toàn bộ danh sách người dùng dành cho trang quản trị Admin
    public function getAll() {
        $statement = $this->databaseConnection->query("SELECT id, fullname, email, phone, address, avatar, role, created_at FROM users ORDER BY id DESC");
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    // Cập nhật hồ sơ cá nhân người dùng (kèm xử lý cập nhật mật khẩu linh hoạt)
    public function update($userId, $fullname, $emailAddress, $phoneNumber, $address = null, $avatarUrl = null, $newPassword = null, $gender = null, $birthday = null, $bio = null) {
        $existingUser = $this->getByEmail($emailAddress);
        if ($existingUser && $existingUser['id'] != $userId) {
            throw new Exception("Email đã được sử dụng bởi tài khoản khác.");
        }

        if (!empty($newPassword)) {
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
            $statement = $this->databaseConnection->prepare("UPDATE users SET fullname = ?, email = ?, phone = ?, address = ?, avatar = ?, password = ?, gender = ?, birthday = ?, bio = ? WHERE id = ?");
            $statement->execute([$fullname, $emailAddress, $phoneNumber, $address, $avatarUrl, $hashedPassword, $gender, $birthday, $bio, $userId]);
        } else {
            $statement = $this->databaseConnection->prepare("UPDATE users SET fullname = ?, email = ?, phone = ?, address = ?, avatar = ?, gender = ?, birthday = ?, bio = ? WHERE id = ?");
            $statement->execute([$fullname, $emailAddress, $phoneNumber, $address, $avatarUrl, $gender, $birthday, $bio, $userId]);
        }
        return true;
    }

    // Cập nhật vai trò/quyền hạn của người dùng (Chỉ Admin mới có quyền thực thi)
    public function updateRole($userId, $targetRole) {
        if (!in_array($targetRole, ['guest', 'user', 'admin'])) {
            throw new Exception("Quyền không hợp lệ.");
        }
        $statement = $this->databaseConnection->prepare("UPDATE users SET role = ? WHERE id = ?");
        $statement->execute([$targetRole, $userId]);
        return true;
    }

    // Xóa vĩnh viễn tài khoản người dùng khỏi hệ thống
    public function delete($userId) {
        $statement = $this->databaseConnection->prepare("DELETE FROM users WHERE id = ?");
        $statement->execute([$userId]);
        return $statement->rowCount() > 0;
    }
    
    // Khởi tạo người dùng mới trực tiếp từ trang quản trị Admin
    public function createByAdmin($fullname, $emailAddress, $phoneNumber, $plainPassword, $assignedRole) {
        if ($this->getByEmail($emailAddress)) {
            throw new Exception("Email đã được sử dụng.");
        }
        $hashedPassword = password_hash($plainPassword, PASSWORD_BCRYPT);
        $statement = $this->databaseConnection->prepare("INSERT INTO users (fullname, email, phone, password, role) VALUES (?, ?, ?, ?, ?)");
        $statement->execute([$fullname, $emailAddress, $phoneNumber, $hashedPassword, $assignedRole]);
        return $this->databaseConnection->lastInsertId();
    }
}
?>
