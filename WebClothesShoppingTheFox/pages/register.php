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

    <title>Đăng ký tài khoản - The Fox</title>
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
            <h2>ĐĂNG KÝ</h2>
            <p>Tạo tài khoản mới để nhận nhiều ưu đãi</p>
        </div>

        <!-- Alert messages -->
        <div id="errorAlert" class="alert alert-danger"></div>
        <div id="successAlert" class="alert alert-success"></div>

        <form id="registerForm">
            <div class="form-group">
                <label for="fullname">Họ và tên</label>
                <div class="input-with-icon">
                    <i class="fa-regular fa-user input-icon"></i>
                    <input type="text" id="fullname" class="form-control" placeholder="Nguyễn Văn A" required>
                </div>
            </div>

            <div class="form-group">
                <label for="email">Địa chỉ Email</label>
                <div class="input-with-icon">
                    <i class="fa-regular fa-envelope input-icon"></i>
                    <input type="email" id="email" class="form-control" placeholder="example@thefox.com" required>
                </div>
            </div>

            <div class="form-group">
                <label for="phone">Số điện thoại</label>
                <div class="input-with-icon">
                    <i class="fa-solid fa-phone input-icon"></i>
                    <input type="tel" id="phone" class="form-control" placeholder="09xxxxxxxx" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <div class="input-with-icon">
                    <i class="fa-solid fa-lock input-icon"></i>
                    <input type="password" id="password" class="form-control" placeholder="Ít nhất 6 ký tự" required style="padding-right: 45px;">
                    <i class="fas fa-eye-slash toggle-password-btn" onclick="togglePasswordVisibility('password', this)"></i>
                </div>
            </div>

            <div class="form-group">
                <label for="confirmPassword">Xác nhận mật khẩu</label>
                <div class="input-with-icon">
                    <i class="fa-solid fa-lock input-icon"></i>
                    <input type="password" id="confirmPassword" class="form-control" placeholder="Nhập lại mật khẩu" required style="padding-right: 45px;">
                    <i class="fas fa-eye-slash toggle-password-btn" onclick="togglePasswordVisibility('confirmPassword', this)"></i>
                </div>
            </div>

            <button type="submit" class="btn-premium" style="height: 52px; border-radius: 12px 0 12px 0; background: #221f20;">
                <i class="fas fa-user-plus"></i> ĐĂNG KÝ
            </button>
        </form>

        <div class="auth-footer-link">
            Đã có tài khoản? <a href="login.php">Đăng nhập tại đây</a>
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
<script src="../assets/js/register.js"></script>
</body>
</html>
