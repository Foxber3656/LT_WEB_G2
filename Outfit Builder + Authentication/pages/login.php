<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (isset($_SESSION['user'])) { header("Location: ../index.php"); exit(); }
if (isset($_SESSION['auth_error'])) {
    echo '<div style="color: red; text-align: center; margin-bottom: 15px; font-size: 0.9rem;">' . $_SESSION['auth_error'] . '</div>';
    unset($_SESSION['auth_error']);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>THE FOX - Đăng Nhập</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background-color: #f5f5f5; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .auth-container { display: flex; width: 100%; max-width: 1100px; background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.03); min-height: 650px; }
        .auth-sidebar { flex: 1.1; background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=1000') no-repeat center center/cover; color: #fff; padding: 40px; display: flex; flex-direction: column; justify-content: space-between; }
        .brand-text h2 { font-size: 2.2rem; font-weight: 800; letter-spacing: 1px; margin-bottom: 10px; }
        .brand-text p { font-size: 0.95rem; line-height: 1.5; color: #e0e0e0; }
        .auth-form-section { flex: 1.3; padding: 50px; display: flex; flex-direction: column; justify-content: center; }
        .form-header { margin-bottom: 30px; }
        .form-header h3 { font-size: 1.8rem; color: #111; margin-bottom: 8px; }
        .form-header p { color: #666; font-size: 0.9rem; }
        .input-group { position: relative; margin-bottom: 20px; }
        .input-group i.input-icon { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #aaa; }
        .input-group input { width: 100%; padding: 14px 15px 14px 45px; border: 1px solid #ddd; border-radius: 8px; font-size: 0.95rem; outline: none; transition: 0.3s; color: #111; }
        .input-group input:focus { border-color: #ff5722; }
        .btn-submit { width: 100%; padding: 14px; background-color: #ff5722; color: white; border: none; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: 0.3s; }
        .btn-submit:hover { background-color: #e64a19; }
        .alert-danger { color: #db4437; background: #fff3f3; padding: 12px; border-radius: 8px; margin-bottom: 15px; font-size: 0.9rem; border-left: 4px solid #db4437; }
        .alert-success { color: #2e7d32; background: #edf7ed; padding: 12px; border-radius: 8px; margin-bottom: 15px; font-size: 0.9rem; border-left: 4px solid #2e7d32; }
        .divider { text-align: center; margin: 25px 0; position: relative; color: #888; font-size: 0.85rem; }
        .divider::before, .divider::after { content: ""; position: absolute; top: 50%; width: 30%; height: 1px; background-color: #eee; }
        .divider::before { left: 0; } .divider::after { right: 0; }
        .social-login { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 30px; }
        .social-btn { display: flex; align-items: center; justify-content: center; gap: 8px; padding: 12px; border: 1px solid #ddd; border-radius: 8px; background: #fff; cursor: pointer; font-size: 0.9rem; font-weight: 600; color: #333; }
        .social-btn.google i { color: #db4437; } .social-btn.facebook i { color: #1877f2; }
        .redirect-text { text-align: center; font-size: 0.9rem; color: #555; }
        .redirect-text a { color: #ff5722; text-decoration: none; font-weight: 600; }
        .forgot-password-wrapper {
            display: flex;
            justify-content: flex-end; /* Đẩy chữ sang bên phải giống thiết kế */
            margin-top: -10px;         /* Thu hẹp khoảng cách với ô mật khẩu */
            margin-bottom: 20px;       /* Tạo khoảng cách thông thoáng với nút Đăng nhập */
        }
        .forgot-password-link {
            font-size: 0.85rem;
            color: #ff5722;            /* Màu cam đặc trưng của THE FOX */
            text-decoration: none;
            font-weight: 500;
            transition: 0.2s;
        }
        .forgot-password-link:hover {
            color: #e64a19;
            text-decoration: underline; /* Tạo hiệu ứng gạch chân khi di chuột vào */
        }
        @media (max-width: 768px) { .auth-container { flex-direction: column; } .auth-sidebar { display: none; } .auth-form-section { padding: 30px 20px; } }
    </style>
</head>
<body>

    <div class="auth-container">
        <div class="auth-sidebar">
            <div class="brand-text">
                <h2>THE FOX<br>BE YOUR STYLE</h2>
                <p>Đăng nhập để tiếp tục mua sắm các sản phẩm mới nhất và quản lý tủ đồ phối đồ sáng tạo của riêng bạn.</p>
            </div>
        </div>

        <div class="auth-form-section">
            <div class="form-header">
                <h3>Chào mừng trở lại!</h3>
                <p>Đăng nhập tài khoản THE FOX của bạn</p>
            </div>

            <?php if (isset($_SESSION['success_msg'])): ?>
                <div class="alert-success">
                    <?php echo $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['auth_error'])): ?>
                <div class="alert-danger">
                    <?php echo $_SESSION['auth_error']; unset($_SESSION['auth_error']); ?>
                </div>
            <?php endif; ?>

            <form action="../controllers/UserController.php?action=login" method="POST">
                <div class="input-group">
                    <i class="fa-regular fa-envelope input-icon"></i>
                    <input type="text" name="identity" placeholder="Email hoặc số điện thoại" required>
                </div>

                <div class="input-group">
                    <i class="fa-solid fa-lock input-icon"></i>
                    <input type="password" name="password" placeholder="Mật khẩu" required>
                </div>
                <div class="forgot-password-wrapper">
                    <a href="forgot-password.php" class="forgot-password-link">Quên mật khẩu?</a>
                </div>
                
                <button type="submit" class="btn-submit">Đăng nhập</button>
            </form>

            <div class="divider">hoặc đăng nhập với</div>

            <div class="social-login">
                <button class="social-btn google"><i class="fa-brands fa-google"></i> Google</button>
                <button class="social-btn facebook"><i class="fa-brands fa-facebook"></i> Facebook</button>
                <button class="social-btn apple"><i class="fa-brands fa-apple"></i> Apple</button>
            </div>

            <div class="redirect-text">
                Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a>
            </div>
        </div>
    </div>

</body>
</html>
