<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Yêu cầu đăng nhập để phối đồ
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
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
    <link rel="stylesheet" href="../assets/css/builder.css">
    <link rel="stylesheet" href="../assets/css/auth.css"> <!-- for modal & buttons -->

    <title>Phối đồ thông minh (Outfit Builder) - The Fox</title>
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
<main class="site-main builder-page">
    <div class="container">
        <div class="builder-wrapper">
            
            <!-- LEFT PANEL: PREVIEW CANVAS -->
            <div class="builder-canvas">
                <div class="canvas-header">
                    <h3>Phòng thử đồ ảo</h3>
                </div>

                <div class="canvas-mannequin">
                    <!-- Accessory Slot -->
                    <div class="mannequin-slot" id="slot-accessory">
                        <div class="placeholder">
                            <i class="fas fa-gem"></i>
                            <span>Phụ kiện trống</span>
                        </div>
                    </div>

                    <!-- Top Slot -->
                    <div class="mannequin-slot" id="slot-top">
                        <div class="placeholder">
                            <i class="fas fa-tshirt"></i>
                            <span>Áo trống</span>
                        </div>
                    </div>

                    <!-- Bottom Slot -->
                    <div class="mannequin-slot" id="slot-bottom">
                        <div class="placeholder">
                            <i class="fas fa-socks"></i>
                            <span>Quần / Chân váy trống</span>
                        </div>
                    </div>
                </div>

                <div class="canvas-footer">
                    <div class="canvas-total">
                        <span>Tổng tiền:</span>
                        <span id="outfitTotalPrice">0đ</span>
                    </div>
                    <div class="canvas-actions">
                        <button class="btn-secondary" id="clearOutfitBtn" style="flex: 1;"><i class="fas fa-redo"></i> Xóa hết</button>
                        <button class="btn-premium" id="saveOutfitBtn" style="flex: 2;"><i class="fas fa-save"></i> Lưu bộ đồ</button>
                    </div>
                    <button class="btn-premium" id="addWholeOutfitToCart" style="margin-top: 10px; width: 100%;"><i class="fas fa-shopping-cart"></i> THÊM TẤT CẢ VÀO GIỎ</button>
                </div>
            </div>

            <!-- RIGHT PANEL: CATALOG SELECTOR -->
            <div class="builder-catalog">
                <div class="catalog-tabs">
                    <button class="catalog-tab-btn active" data-catalog="tops">ÁO (TOPS)</button>
                    <button class="catalog-tab-btn" data-catalog="bottoms">QUẦN / VÁY (BOTTOMS)</button>
                    <button class="catalog-tab-btn" data-catalog="accessories">PHỤ KIỆN (ACCESSORIES)</button>
                </div>

                <div id="catalog-tops" class="catalog-grid active-grid">
                    <p style="padding: 20px; color: var(--gray);">Đang tải sản phẩm...</p>
                </div>
                <div id="catalog-bottoms" class="catalog-grid" style="display: none;">
                    <p style="padding: 20px; color: var(--gray);">Đang tải sản phẩm...</p>
                </div>
                <div id="catalog-accessories" class="catalog-grid" style="display: none;">
                    <p style="padding: 20px; color: var(--gray);">Đang tải sản phẩm...</p>
                </div>
            </div>

        </div>
    </div>
</main>

<!-- MODAL: SAVE OUTFIT DETAILS -->
<div class="modal" id="saveOutfitModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Lưu bộ phối đồ của bạn</h3>
            <button class="close-btn" id="closeSaveModalBtn">&times;</button>
        </div>
        <div id="saveOutfitAlert" class="alert alert-danger"></div>
        <form id="saveOutfitForm">
            <div class="form-group">
                <label for="outfitName">Tên bộ phối đồ</label>
                <input type="text" id="outfitName" class="form-control" placeholder="Ví dụ: Dạo phố mùa hè, Công sở thanh lịch" required>
            </div>
            <div class="form-group">
                <label for="outfitDesc">Mô tả chi tiết</label>
                <textarea id="outfitDesc" class="form-control" placeholder="Mô tả ngắn gọn về phong cách, cách phối đồ này..." style="height: 100px; padding: 12px; resize: none;"></textarea>
            </div>

            <div class="btn-group">
                <button type="button" class="btn-secondary" id="cancelSaveModalBtn">Hủy</button>
                <button type="submit" class="btn-premium" style="max-width: 150px; height: auto;">Lưu lại</button>
            </div>
        </form>
    </div>
</div>

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
<script src="../assets/js/outfitBuilder.js"></script>
</body>
</html>
