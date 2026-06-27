<?php require_once '../middleware/checkGuest.php'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../assets/images/fashion.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- CSS -->
    <link rel="stylesheet" href="../assets/css/gobal.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/auth.css">

    <title>Đăng nhập - The Fox</title>
</head>
<body>

<!-- HEADER -->
<header id="header">
    <div class="container">
        <!-- LOGO -->
        <div class="header-logo">
            <a href="home.php">
                <img src="../assets/images/icon.png" alt="The Fox Logo" class="logo">
            </a>
        </div>
        <!-- NAVBAR -->
        <nav class="navbar">
            <ul class="menu">
                <li class="menu-items"><a href="cartegory.php?cat=nu">NỮ</a></li>
                <li class="menu-items"><a href="cartegory.php?cat=nam">NAM</a></li>
                <li class="menu-items"><a href="cartegory.php?cat=tre-em">TRẺ EM</a></li>
                <li class="menu-items"><a href="cartegory.php?cat=phu-kien">PHỤ KIỆN</a></li>
                <li class="menu-items"><a href="cartegory.php?cat=bo-suu-tap">BỘ SƯU TẬP</a></li>
                <li class="sale-menu"><a href="cartegory.php?cat=sale">SALE</a></li>
                <li class="menu-items"><a href="cartegory.php?cat=thuong-hieu">THƯƠNG HIỆU</a></li>
            </ul>
        </nav>
        <!-- HEADER ACTION -->
        <div class="header-action">
            <div class="search-box">
                <input type="text" placeholder="Tìm kiếm">
                <i class="fas fa-search"></i>
            </div>
            <a class="fa fa-headphones" href="mailto:info@thefox.com"></a>
            <a class="fa fa-user" href="profile.php"></a>
            <a class="fa fa-shopping-bag cart-icon-btn" href="javascript:void(0)"></a>
        </div>
    </div>
</header>

<!-- MAIN CONTENT -->
<main class="site-main auth-page">
    <div class="auth-container">
        <div class="auth-header">
            <h2>ĐĂNG NHẬP</h2>
            <p>Chào mừng bạn trở lại với The Fox Clothes</p>
        </div>

        <!-- Alert messages -->
        <div id="errorAlert" class="alert alert-danger"></div>
        <div id="successAlert" class="alert alert-success"></div>

        <!--

            🔐 TÀI KHOẢN TEST (DEV ONLY)
            👑 Admin  : admin@thefox.com  |  MK: 123456
            👤 User   : demo@thefox.com   |  MK: 123456
        -->
        <form id="loginForm">
            <div class="form-group">
                <label for="email">Địa chỉ Email</label>
                <div class="input-with-icon">
                    <i class="fa-regular fa-envelope input-icon"></i>
                    <input type="email" id="email" class="form-control" placeholder="example@thefox.com" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <div class="input-with-icon">
                    <i class="fa-solid fa-lock input-icon"></i>
                    <input type="password" id="password" class="form-control" placeholder="••••••••" required style="padding-right: 45px;">
                    <i class="fas fa-eye-slash toggle-password-btn" onclick="togglePasswordVisibility('password', this)"></i>
                </div>
            </div>

            <button type="submit" class="btn-premium" style="height: 52px; border-radius: 12px 0 12px 0; background: #221f20;">
                <i class="fas fa-sign-in-alt"></i> ĐĂNG NHẬP
            </button>
        </form>

        <!-- ĐĂNG NHẬP BẰNG MẠNG XÃ HỘI -->
        <div class="social-login-divider">
            <span>hoặc tiếp tục với</span>
        </div>

        <div class="social-login-buttons">
            <button id="googleLoginBtn" class="btn-social btn-google" onclick="handleSocialLoginNotice('Google')">
                <svg class="social-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Đăng nhập với Google
            </button>

            <button id="appleLoginBtn" class="btn-social btn-apple" onclick="handleSocialLoginNotice('Apple')">
                <svg class="social-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z" fill="currentColor"/>
                </svg>
                Đăng nhập với Apple
            </button>
        </div>
        <div class="auth-footer-link">
            Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a>
        </div>

    </div>
</main>

<!-- FOOTER -->
<div class="site-bottom">
    <div id="footer">
        <div class="container">
            <div class="footer-wrapper">
                <div class="footer-col">
                    <div class="footer-logo">
                        <img src="../assets/images/fashion.ico" alt="The Fox Logo">
                    </div>
                    <div class="footer-social">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                    <div class="footer-contact">
                        <p>LIÊN HỆ: <a href="mailto:info@thefox.com">info@thefox.com</a></p>
                    </div>
                </div>
                <div class="footer-col">
                    <h4>GIỚI THIỆU</h4>
                    <ul>
                        <li><a href="#">Về chúng tôi</a></li>
                        <li><a href="#">Tin tức</a></li>
                        <li><a href="#">Liên hệ</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>DỊCH VỤ KHÁCH HÀNG</h4>
                    <ul>
                        <li><a href="#">Chính sách bảo hành</a></li>
                        <li><a href="#">Chính sách đổi trả</a></li>
                        <li><a href="#">Hướng dẫn mua hàng</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>LIÊN HỆ</h4>
                    <ul>
                        <li><a href="#">Hỗ trợ khách hàng</a></li>
                        <li><a href="#">Góp ý</a></li>
                        <li><a href="#">Tuyển dụng</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <div class="footer-new">
                        <h3>ĐĂNG KÝ NHẬN TIN MỚI NHẤT</h3>
                        <form action="#" method="post">
                            <input type="email" placeholder="Nhập email của bạn" required>
                            <button type="submit">Đăng ký</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<footer id="footer-bottom">
    <div class="copy-right">
        <p>©THE FOX</p>
    </div>
</footer>

<?php include 'sidebarcart.php'; ?>

<!-- Script -->
<script src="../assets/js/scroll.js"></script>
<script src="../assets/js/login.js"></script>
</body>
</html>
