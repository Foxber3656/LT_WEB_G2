<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (isset($_SESSION['user'])) { header("Location: ../index.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>THE FOX - Đăng Ký Tài Khoản</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background-color: #f5f5f5; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .auth-container { display: flex; width: 100%; max-width: 1100px; background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.03); min-height: 650px; }
        .auth-sidebar { flex: 1.1; background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1483985988355-763728e1935b?q=80&w=1000') no-repeat center center/cover; color: #fff; padding: 40px; display: flex; flex-direction: column; justify-content: space-between; }
        .brand-text h2 { font-size: 2.2rem; font-weight: 800; letter-spacing: 1px; margin-bottom: 10px; }
        .brand-text p { font-size: 0.95rem; line-height: 1.5; color: #e0e0e0; }
        .auth-form-section { flex: 1.3; padding: 40px 50px; display: flex; flex-direction: column; justify-content: center; }
        .form-header { margin-bottom: 25px; }
        .form-header h3 { font-size: 1.8rem; color: #111; margin-bottom: 8px; }
        .form-header p { color: #666; font-size: 0.9rem; }
        .input-group { position: relative; margin-bottom: 15px; }
        .input-group i.input-icon { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #aaa; }
        .input-group input { width: 100%; padding: 14px 15px 14px 45px; border: 1px solid #ddd; border-radius: 8px; font-size: 0.95rem; outline: none; transition: 0.3s; color: #111; }
        .input-group input:focus { border-color: #ff5722; }
        .btn-submit { width: 100%; padding: 14px; background-color: #ff5722; color: white; border: none; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: 0.3s; margin-top: 10px; }
        .btn-submit:hover { background-color: #e64a19; }
        .alert-danger { color: #db4437; background: #fff3f3; padding: 12px; border-radius: 8px; margin-bottom: 15px; font-size: 0.9rem; border-left: 4px solid #db4437; }
        .redirect-text { text-align: center; margin-top: 25px; font-size: 0.9rem; color: #555; }
        .redirect-text a { color: #ff5722; text-decoration: none; font-weight: 600; }
        @media (max-width: 768px) { .auth-container { flex-direction: column; } .auth-sidebar { display: none; } .auth-form-section { padding: 30px 20px; } }
    </style>
</head>
<body>

    <div class="auth-container">
        <div class="auth-sidebar">
            <div class="brand-text">
                <h2>THE FOX<br>JOIN US TODAY</h2>
                <p>Đăng ký tài khoản ngay hôm nay để nhận các ưu đãi độc quyền, tích điểm thưởng và trải nghiệm phòng thử đồ thông minh Outfit Builder.</p>
            </div>
        </div>

        <div class="auth-form-section">
            <div class="form-header">
                <h3>Tạo tài khoản mới</h3>
                <p>Cùng THE FOX định hình phong cách thời trang của bạn</p>
            </div>

            <?php if (isset($_SESSION['auth_error'])): ?>
                <div class="alert-danger">
                    <?php echo $_SESSION['auth_error']; unset($_SESSION['auth_error']); ?>
                </div>
            <?php endif; ?>

            <form action="../controllers/UserController.php?action=register" method="POST">
                <div class="input-group">
                    <i class="fa-regular fa-user input-icon"></i>
                    <input type="text" name="name" placeholder="Họ và tên của bạn" required>
                </div>
                <div class="input-group">
                    <i class="fa-regular fa-envelope input-icon"></i>
                    <input type="email" name="email" placeholder="Địa chỉ Email" required>
                </div>
                <div class="input-group">
                    <i class="fa-solid fa-phone input-icon"></i>
                    <input type="tel" name="phone" placeholder="Số điện thoại" required>
                </div>
                <div class="input-group">
                    <i class="fa-solid fa-lock input-icon"></i>
                    <input type="password" name="password" placeholder="Mật khẩu" required>
                </div>

                <button type="submit" class="btn-submit">Đăng ký ngay</button>
            </form>

            <div class="redirect-text">
                Đã có tài khoản? <a href="login.php">Đăng nhập tại đây</a>
            </div>
        </div>
    </div>

</body>
</html>
