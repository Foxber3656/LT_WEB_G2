<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Yêu cầu đăng nhập để truy cập trang sản phẩm yêu thích
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$fullname = $_SESSION['fullname'] ?? 'Thành viên';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/x-icon" href="../assets/images/fashion.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- CSS Design System & Custom Modules -->
    <link rel="stylesheet" href="../assets/css/gobal.css">
    <link rel="stylesheet" href="../assets/css/accountSidebar.css">
    <link rel="stylesheet" href="../assets/css/wishlist.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/footer.css">

    <title>Sản phẩm yêu thích - The Fox</title>
</head>

<body>
<!--=========================HEADER==========================-->
<header id="header">
    <div class="container">
        <!-- LOGO -->
        <div class="header-logo">
            <a href="home.php">
                <img src="../assets/images/icon.png" alt="The Fox Logo" class="logo">
            </a>
        </div>
        <!--=========================NAVBAR==========================-->
        <nav class="navbar">
            <ul class="menu">
                <!-- WOMEN -->
                <li class="menu-items">
                    <a href="cartegory.php?cat=nu">NỮ</a>
                    <div class="mega-menu">
                        <div class="mega-col">
                            <h4><a href="#">ALL ITEMS</a></h4>
                            <ul>
                                <li><a href="#">NEW ARRIVALS</a></li>
                                <li><a href="#">SALE | CHỈ CÓ TẠI ONLINE</a></li>
                            </ul>
                        </div>
                        <div class="mega-col">
                            <h4><a href="#">ÁO</a></h4>
                            <ul>
                                <li><a href="#">Áo thun</a></li>
                                <li><a href="#">Áo sơ mi</a></li>
                                <li><a href="#">Áo croptop</a></li>
                                <li><a href="#">Áo len</a></li>
                            </ul>
                        </div>
                        <div class="mega-col">
                            <h4><a href="#">QUẦN</a></h4>
                            <ul>
                                <li><a href="#">Quần jean</a></li>
                                <li><a href="#">Quần tây</a></li>
                                <li><a href="#">Quần short</a></li>
                            </ul>
                        </div>
                    </div>
                </li>
                <!-- MEN -->
                <li class="menu-items">
                    <a href="cartegory.php?cat=nam">NAM</a>
                </li>
                <!-- CHILDREN -->
                <li class="menu-items">
                    <a href="cartegory.php?cat=tre-em">TRẺ EM</a>
                </li>
                <!-- ACCESSORIES -->
                <li class="menu-items">
                    <a href="cartegory.php?cat=phu-kien">PHỤ KIỆN</a>
                </li>
            </ul>
        </nav>
        <!--=========================HEADER ACTION==========================-->
        <div class="header-action">
            <!-- SEARCH -->
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

<!--=========================Account==========================-->
<section class="account">
    <div class="container">
        <div class="account-wrapper">

            <div class="account-sidebar">
                <div class="account-user">
                    <i class="fa-solid fa-circle-user"></i>
                    <span><?php echo htmlspecialchars($fullname); ?></span>
                </div>
                <ul>
                    <li>
                        <a href="profile.php">
                            <i class="fa-regular fa-user"></i>
                            Thông tin tài khoản
                        </a>
                    </li>
                    <li>
                        <a href="profile.php?tab=outfits">
                            <i class="fa-solid fa-shirt"></i>
                            Phối đồ của tôi
                        </a>
                    </li>
                    <li>
                        <a href="wishlist.php" class="active">
                            <i class="fa-regular fa-heart"></i>
                            Sản phẩm yêu thích
                        </a>
                    </li>
                    <li>
                        <a href="order.php">
                            <i class="fa-solid fa-rotate"></i>
                            Quản lý đơn hàng
                        </a>
                    </li>
                    <li>
                        <a href="outfit-builder.php">
                            <i class="fa-solid fa-magic"></i>
                            Tạo phối đồ
                        </a>
                    </li>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <li>
                        <a href="profile.php?tab=admin-users" style="color: var(--color-primary, #BF8A49);">
                            <i class="fa-solid fa-users-gear"></i>
                            Quản lý thành viên (Admin)
                        </a>
                    </li>
                    <?php endif; ?>
                    <li>
                        <a href="javascript:void(0)" id="logoutBtn" style="color: var(--color-danger, #de3b3b);">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                            Đăng xuất
                        </a>
                    </li>
                </ul>
            </div>

            <div class="account-content">
                <h2>SẢN PHẨM YÊU THÍCH</h2>
                <div class="wishlist-grid" id="wishlistGrid">
                    <!-- Sẽ nạp động bằng Ajax -->
                    <p style="padding: 20px; color: var(--color-text-sub, #666);">Đang tải danh sách yêu thích...</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!--=========================FOOTER==========================-->
<div class="site-bottom">
    <div id="footer">
    <div class="container">
        <div class="footer-wrapper">
            <div class="footer-col">
                <!--LOGO-->
                <div class="footer-logo">
                <img src="../assets/images/fashion.ico" alt="The Fox Logo">
                </div>
                <!--SOCIAL MEDIA-->
                <div class="footer-social">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
                <!--CONTACT-->
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
                <!--NEW-->
                <div class="footer-new">
                <h3>ĐĂNG KÝ NHẬN TIN MỚI NHẤT TỪ THE FOX</h3>
                    <form action="#" method="post">
                        <input type="email" placeholder="Nhập email của bạn" required>
                        <button type="submit">Đăng ký</button>
                    </form>
                </div>
                <!--Dowload-->
                <div class="footer-download">
                    <div class="footer-download-app">
                        <h4>TẢI ỨNG DỤNG</h4>
                        <a href="#"><img src="../assets/images/appstore.png" alt="App Store"></a>
                        <a href="#"><img src="../assets/images/googleplay.png" alt="Google Play"></a>
                    </div>
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
<script src="../assets/js/wishlist.js"></script>
</body>
</html>
