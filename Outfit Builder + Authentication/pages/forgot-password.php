<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/config.php';
require_once '../config/database.php';

$message = "";
$message_type = "";

// Xử lý khi người dùng nhấn nút "Gửi yêu cầu"
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    
    // Kiểm tra email có tồn tại trong hệ thống không
    $query = "SELECT id FROM users WHERE email = :email LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        // Trong thực tế sẽ gửi mã OTP hoặc Link đổi mật khẩu qua Email.
        // Ở đây chúng ta làm Demo thông báo giả lập thành công để phục vụ bài tập/đồ án.
        $message = "Hệ thống đã gửi hướng dẫn đặt lại mật khẩu vào email của bạn!";
        $message_type = "success";
    } else {
        $message = "Email này chưa được đăng ký trên hệ thống!";
        $message_type = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>THE FOX - Quên Mật Khẩu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background-color: #f8f9fa; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .forgot-container { width: 100%; max-width: 450px; background: #fff; border-radius: 16px; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); text-align: center; }
        .icon-wrapper { width: 70px; height: 70px; background: #fff3f3; color: #ff5722; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 2rem; border: 1px solid #fecdd3; }
        h3 { font-size: 1.6rem; color: #111; margin-bottom: 10px; font-weight: 700; }
        p { color: #666; font-size: 0.9rem; margin-bottom: 25px; line-height: 1.5; }
        .input-group { position: relative; margin-bottom: 20px; text-align: left; }
        .input-group i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #aaa; }
        .input-group input { width: 100%; padding: 14px 15px 14px 45px; border: 1px solid #ddd; border-radius: 8px; font-size: 0.95rem; outline: none; transition: 0.3s; }
        .input-group input:focus { border-color: #ff5722; }
        .btn-submit { width: 100%; padding: 14px; background-color: #ff5722; color: white; border: none; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: 0.3s; margin-bottom: 20px; }
        .btn-submit:hover { background-color: #e64a19; }
        .back-to-login { font-size: 0.9rem; }
        .back-to-login a { color: #ff5722; text-decoration: none; font-weight: 600; }
        .alert { padding: 12px; border-radius: 8px; font-size: 0.9rem; margin-bottom: 20px; border: 1px solid; }
        .alert.success { color: #15803d; background: #f0fdf4; border-color: #bbf7d0; }
        .alert.error { color: #db4437; background: #fff3f3; border-color: #fecdd3; }
    </style>
</head>
<body>

    <div class="forgot-container">
        <div class="icon-wrapper">
            <i class="fa-solid fa-key"></i>
        </div>
        <h3>Quên mật khẩu?</h3>
        <p>Nhập email tài khoản của bạn để nhận liên kết xác thực đặt lại mật khẩu mới.</p>

        <?php if (!empty($message)): ?>
            <div class="alert <?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form action="forgot-password.php" method="POST">
            <div class="input-group">
                <i class="fa-regular fa-envelope"></i>
                <input type="email" name="email" placeholder="Nhập địa chỉ email của bạn" required>
            </div>
            <button type="submit" class="btn-submit">Gửi yêu cầu</button>
        </form>

        <div class="back-to-login">
            <a href="../index.php"><i class="fa-solid fa-arrow-left"></i> Quay lại Đăng nhập</a>
        </div>
    </div>

</body>
</html>
