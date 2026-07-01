<?php
// Khởi tạo session để hiển thị thông báo lỗi nếu có
if (session_status() === PHP_SESSION_NONE) {
    session_start();
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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .login-container {
            display: flex;
            width: 100%;
            max-width: 1100px;
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            min-height: 650px;
        }

        /* --- PHẦN BANNER BÊN TRÁI --- */
        .login-sidebar {
            flex: 1.1;
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.6)), 
                        url('https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=1000') no-repeat center center/cover;
            color: #fff;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .brand-text h2 {
            font-size: 2.2rem;
            font-weight: 800;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .brand-text p {
            font-size: 0.95rem;
            line-height: 1.5;
            color: #e0e0e0;
        }

        .features-list {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            border-top: 1px solid rgba(255,255,255,0.2);
            padding-top: 20px;
        }

        .feature-item {
            text-align: center;
            font-size: 0.8rem;
        }

        .feature-item i {
            font-size: 1.5rem;
            margin-bottom: 8px;
            color: #fff;
        }

        .feature-item p strong {
            display: block;
            margin-bottom: 2px;
        }

        /* --- PHẦN FORM BÊN PHẢI --- */
        .login-form-section {
            flex: 1.3;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-header {
            margin-bottom: 30px;
        }

        .form-header h3 {
            font-size: 1.8rem;
            color: #111;
            margin-bottom: 8px;
        }

        .form-header p {
            color: #666;
            font-size: 0.9rem;
        }

        .input-group {
            position: relative;
            margin-bottom: 20px;
        }

        .input-group i.input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
        }

        .input-group input {
            width: 100%;
            padding: 14px 15px 14px 45px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 0.95rem;
            outline: none;
            transition: 0.3s;
        }

        .input-group input:focus {
            border-color: #ff5722;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #aaa;
        }

        .forgot-pass {
            text-align: right;
            margin-bottom: 25px;
        }

        .forgot-pass a {
            color: #ff5722;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background-color: #ff5722;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-submit:hover {
            background-color: #e64a19;
        }

        .divider {
            text-align: center;
            margin: 25px 0;
            position: relative;
            color: #888;
            font-size: 0.85rem;
        }

        .divider::before, .divider::after {
            content: "";
            position: absolute;
            top: 50%;
            width: 35%;
            height: 1px;
            background-color: #eee;
        }
        .divider::before { left: 0; }
        .divider::after { right: 0; }

        /* Đăng nhập MXH */
        .social-login {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 30px;
        }

        .social-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #fff;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 600;
            color: #333;
            transition: 0.3s;
        }

        .social-btn:hover {
            background-color: #f5f5f5;
        }

        .social-btn.google i { color: #db4437; }
        .social-btn.facebook i { color: #1877f2; }
        .social-btn.apple i { color: #000; }

        .signup-redirect {
            text-align: center;
            font-size: 0.9rem;
            color: #555;
        }

        .signup-redirect a {
            color: #ff5722;
            text-decoration: none;
            font-weight: 600;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                min-height: auto;
            }
            .login-sidebar {
                padding: 30px;
                min-height: 250px;
            }
            .features-list {
                display: none;
            }
            .login-form-section {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="login-sidebar">
            <div class="brand-text">
                <h2>THE FOX<br>BE YOUR STYLE</h2>
                <p>Đăng nhập để khám phá thế giới thời trang độc đáo và trải nghiệm mua sắm tuyệt vời cùng THE FOX.</p>
            </div>
            
            <div class="features-list">
                <div class="feature-item">
                    <i class="fa-solid fa-truck-fast"></i>
                    <p><strong>Miễn phí vận chuyển</strong>Đơn từ 500.000đ</p>
                </div>
                <div class="feature-item">
                    <i class="fa-solid fa-award"></i>
                    <p><strong>Sản phẩm chính hãng</strong>100% chính hãng</p>
                </div>
                <div class="feature-item">
                    <i class="fa-solid fa-rotate"></i>
                    <p><strong>Đổi trả dễ dàng</strong>Trong 7 ngày</p>
                </div>
            </div>
        </div>

        <div class="login-form-section">
            <div class="form-header">
                <h3>Chào mừng trở lại!</h3>
                <p>Đăng nhập để tiếp tục mua sắm tại THE FOX</p>
            </div>

            <?php 
            if (isset($_SESSION['auth_error'])) {
                echo '<div style="color: #db4437; background: #fff3f3; padding: 10px; border-radius: 6px; margin-bottom: 15px; font-size: 0.9rem; border: 1px solid #fecdd3; text-align: center;">' . $_SESSION['auth_error'] . '</div>';
                unset($_SESSION['auth_error']);
            }
            if (isset($_SESSION['success_msg'])) {
                echo '<div style="color: #15803d; background: #f0fdf4; padding: 10px; border-radius: 6px; margin-bottom: 15px; font-size: 0.9rem; border: 1px solid #bbf7d0; text-align: center;">' . $_SESSION['success_msg'] . '</div>';
                unset($_SESSION['success_msg']);
            }
            ?>

            <form action="controllers/UserController.php?action=login" method="POST">
                
                <div class="input-group">
                    <i class="fa-regular fa-envelope input-icon"></i>
                    <input type="text" name="identity" placeholder="Email hoặc số điện thoại" required>
                </div>

                <div class="input-group">
                    <i class="fa-solid fa-lock input-icon"></i>
                    <input type="password" name="password" placeholder="Mật khẩu" id="password" required>
                    <i class="fa-regular fa-eye toggle-password" onclick="togglePasswordVisibility()"></i>
                </div>

                <div class="forgot-pass">
                    <a href="pages/forgot-password.php">Quên mật khẩu?</a>
                </div>

                <button type="submit" class="btn-submit">Đăng nhập</button>
            </form>

            <div class="divider">hoặc đăng nhập với</div>

            <div class="social-login">
                <button class="social-btn google"><i class="fa-brands fa-google"></i> Google</button>
                <button class="social-btn facebook"><i class="fa-brands fa-facebook"></i> Facebook</button>
                <button class="social-btn apple"><i class="fa-brands fa-apple"></i> Apple</button>
            </div>

            <div class="signup-redirect">
                Chưa có tài khoản? <a href="pages/register.php">Đăng ký ngay</a>
            </div>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.querySelector('.toggle-password');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
</body>
</html>
