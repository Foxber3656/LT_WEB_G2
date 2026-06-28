<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Yêu cầu đăng nhập để truy cập trang quản lý đơn hàng
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$fullname = $_SESSION['fullname'] ?? 'Thành viên';
$isAdmin  = ($_SESSION['role'] ?? '') === 'admin';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/x-icon" href="../assets/images/fashion.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="../assets/css/accountSidebar.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/gobal.css">
    <link rel="stylesheet" href="../assets/css/order.css">

    <title>Quản lý đơn hàng - The Fox</title>
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
                <li class="menu-items"><a href="cartegory.php?cat=nu">NỮ</a></li>
                <li class="menu-items"><a href="cartegory.php?cat=nam">NAM</a></li>
                <li class="menu-items"><a href="cartegory.php?cat=tre-em">TRẺ EM</a></li>
                <li class="menu-items"><a href="cartegory.php?cat=phu-kien">PHỤ KIỆN</a></li>
            </ul>
        </nav>
        <!--=========================HEADER ACTION==========================-->
        <div class="header-action">
            <div class="search-box">
                <input type="text" placeholder="Tìm kiếm">
                <i class="fas fa-search"></i>
            </div>
            <a class="fa fa-headphones" href="#"></a>
            <a class="fa fa-user" href="profile.php"></a>
            <a class="fa fa-shopping-bag cart-icon-btn" href="javascript:void(0)"></a>
        </div>
    </div>
</header>

<!--=========================Account Layout==========================-->
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
                        <a href="wishlist.php">
                            <i class="fa-regular fa-heart"></i>
                            Sản phẩm yêu thích
                        </a>
                    </li>
                    <li>
                        <a href="order.php" class="active">
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
                    <?php if ($isAdmin): ?>
                    <li>
                        <a href="profile.php?tab=admin-users" class="admin-link">
                            <i class="fa-solid fa-users-gear"></i>
                            Quản lý thành viên (Admin)
                        </a>
                    </li>
                    <?php endif; ?>
                    <li>
                        <a href="javascript:void(0)" id="logoutBtn" class="logout-link">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                            Đăng xuất
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Content Area -->
            <div class="account-content">
                <h2>Lịch sử đơn hàng</h2>
                <p class="order-subtitle">Danh sách tất cả các đơn hàng đã đặt trên hệ thống của bạn.</p>

                <!-- Toolbar tìm kiếm -->
                <div class="order-toolbar">
                    <div class="order-search-wrap">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="order-search" placeholder="Tìm mã đơn hàng...">
                    </div>
                </div>

                <!-- Bảng đơn hàng -->
                <div class="order-table-wrap">
                    <table class="table-premium" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Mã Đơn</th>
                                <th>Ngày đặt</th>
                                <th>Khách hàng</th>
                                <th>Thành tiền</th>
                                <th>Thanh toán</th>
                                <th>Trạng thái</th>
                                <th style="text-align: center;">Hành động</th>
                            </tr>
                        </thead>
                        <tbody id="order-list-tbody">
                            <tr>
                                <td colspan="7" style="padding: 30px; text-align: center; color: #888;">Đang tải đơn hàng...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!--=========================DETAIL MODAL==========================-->
<div class="order-modal-overlay" id="order-detail-modal">
    <div class="order-modal-box">
        <div class="order-modal-head">
            <h3>Chi Tiết Đơn Hàng <span id="modal-order-code" style="color: #BF8A49;"></span></h3>
            <button class="order-modal-close" id="close-modal-btn" title="Đóng">&times;</button>
        </div>
        <div id="modal-body-content" class="order-modal-body">
            <!-- Nội dung render bởi order.js -->
        </div>
        <div class="order-modal-foot">
            <?php if ($isAdmin): ?>
            <!-- Giả lập trạng thái — chỉ Admin thấy -->
            <div class="sim-controls">
                <span class="sim-label"><i class="fas fa-tools"></i> Giả lập trạng thái:</span>
                <select id="sim-status-select">
                    <option value="Chờ xác nhận">Chờ xác nhận</option>
                    <option value="Đang xử lý">Đang xử lý</option>
                    <option value="Đang giao hàng">Đang giao hàng</option>
                    <option value="Đã hoàn thành">Đã hoàn thành</option>
                    <option value="Đã hủy">Đã hủy</option>
                </select>
            </div>
            <?php else: ?>
            <div></div>
            <?php endif; ?>
            <button type="button" class="btn-secondary" id="close-modal-footer-btn">Đóng</button>
        </div>
    </div>
</div>

<!--=========================FOOTER==========================-->
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
                </ul>
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

<!-- Scripts -->
<script src="../assets/js/order.js?v=2"></script>
<script src="../assets/js/scroll.js"></script>
</body>
</html>
