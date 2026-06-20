<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/x-icon" href="../assets/images/fashion.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!--=========================CSS==========================-->
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/gobal.css">

    <title>The Fox - Quản lý đơn hàng</title>
</head>

<body>
<!--=========================HEADER==========================-->
<header id="header">
    <div class="container">
        <!-- LOGO -->
        <div class="header-logo">
            <a href="../index.php">
                <img src="../assets/images/icon.png" alt="The Fox Logo" class="logo">
            </a>
        </div>
        <!--=========================NAVBAR==========================-->
        <nav class="navbar">
            <ul class="menu">
                <li class="menu-items"><a href="cartegory.php">NỮ</a></li>
                <li class="menu-items"><a href="cartegory.php">NAM</a></li>
                <li class="menu-items"><a href="cartegory.php">TRẺ EM</a></li>
                <li class="menu-items"><a href="cartegory.php">PHỤ KIỆN</a></li>
                <li class="menu-items"><a href="#">BỘ SƯU TẬP</a></li>
                <li class="sale-menu"><a href="#">SALE</a></li>
                <li class="menu-items"><a href="#">THƯƠNG HIỆU</a></li>
            </ul>
        </nav>
        <!--=========================HEADER ACTION==========================-->
        <div class="header-action">
            <div class="search-box">
                <input type="text" placeholder="Tìm kiếm">
                <i class="fas fa-search"></i>
            </div>
            <a class="fa fa-headphones" href="#"></a>
            <a class="fa fa-user" href="#"></a>
            <a class="fa fa-shopping-bag" href="cart.php"></a>
        </div>
    </div>
</header>

<!--=========================MAIN==========================-->
<main class="site-main" style="padding-top: 120px; padding-bottom: 80px; background: #fafafa;">
    <div class="container" style="max-width: 1000px;">
        <h1 style="font-size: 24px; margin-bottom: 30px; text-transform: uppercase; letter-spacing: 1px;">Lịch sử đơn hàng</h1>
        
        <!-- Order history container -->
        <div style="background: #fff; padding: 25px; border: 1px solid var(--border-color); border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
                <p style="color: #666; font-size: 14px; margin: 0;">Danh sách tất cả các đơn hàng đã đặt trên hệ thống.</p>
                <!-- Search -->
                <div style="position: relative;">
                    <input type="text" id="order-search" placeholder="Tìm mã đơn hàng..." style="padding: 10px 15px; border: 1px solid var(--border-color); border-radius: 4px; font-size: 14px; width: 220px; outline: none;">
                    <i class="fas fa-search" style="position: absolute; right: 12px; top: 12px; color: #aaa;"></i>
                </div>
            </div>

            <!-- Table -->
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
                    <thead>
                        <tr style="border-bottom: 2px solid #eee; background: #fdfdfd;">
                            <th style="padding: 15px 10px; font-weight: 600;">Mã Đơn</th>
                            <th style="padding: 15px 10px; font-weight: 600;">Ngày đặt</th>
                            <th style="padding: 15px 10px; font-weight: 600;">Khách hàng</th>
                            <th style="padding: 15px 10px; font-weight: 600;">Thành tiền</th>
                            <th style="padding: 15px 10px; font-weight: 600;">Thanh toán</th>
                            <th style="padding: 15px 10px; font-weight: 600;">Trạng thái</th>
                            <th style="padding: 15px 10px; font-weight: 600; text-align: center;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody id="order-list-tbody">
                        <!-- Dynamic content -->
                        <tr>
                            <td colspan="7" style="padding: 30px; text-align: center; color: #888;">Đang tải đơn hàng...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<!--=========================DETAIL MODAL==========================-->
<div id="order-detail-modal" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 10000; display: none; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: #fff; max-width: 600px; width: 100%; border-radius: 8px; box-shadow: 0 5px 25px rgba(0,0,0,0.2); overflow: hidden; display: flex; flex-direction: column; max-height: 90vh;">
        <!-- Modal Header -->
        <div style="padding: 20px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; background: #fafafa;">
            <h3 style="margin: 0; font-size: 18px; text-transform: uppercase;">Chi Tiết Đơn Hàng <span id="modal-order-code" style="color: var(--primary);"></span></h3>
            <button id="close-modal-btn" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #999;"><i class="fas fa-times"></i></button>
        </div>
        <!-- Modal Content -->
        <div id="modal-body-content" style="padding: 20px; overflow-y: auto; flex: 1; font-size: 14px; line-height: 1.6;">
            <!-- Render details -->
        </div>
        <!-- Modal Footer -->
        <div style="padding: 15px 20px; border-top: 1px solid #eee; background: #fafafa; display: flex; justify-content: space-between; align-items: center;">
            <div id="simulation-controls" style="display: flex; gap: 8px; align-items: center;">
                <span style="font-size: 12px; color: #d32f2f; font-weight: bold;"><i class="fas fa-tools"></i> Giả lập trạng thái:</span>
                <select id="sim-status-select" style="padding: 6px; border: 1px solid var(--border-color); border-radius: 4px; font-size: 13px;">
                    <option value="Chờ xác nhận">Chờ xác nhận</option>
                    <option value="Đang xử lý">Đang xử lý</option>
                    <option value="Đang giao hàng">Đang giao hàng</option>
                    <option value="Đã hoàn thành">Đã hoàn thành</option>
                    <option value="Đã hủy">Đã hủy</option>
                </select>
            </div>
            <button id="close-modal-footer-btn" style="padding: 8px 18px; background: #666; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Đóng</button>
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
</body>

<script src="../assets/js/order.js"></script>
<script src="../assets/js/scroll.js"></script>
</html>
