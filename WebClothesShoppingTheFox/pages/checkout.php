<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/x-icon" href="../assets/images/fashion.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!--=========================CSS==========================-->
    <link rel="stylesheet" href="../assets/css/checkout.css">
    <link rel="stylesheet" href="../assets/css/cart.css?v=1.2">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/gobal.css">

    <title>The Fox - Đặt hàng</title>
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
            <a class="fa fa-headphones" href="mailto:info@thefox.com"></a>
            <a class="fa fa-user" href="profile.php"></a>
            <a class="fa fa-shopping-bag" href="cart.php"></a>
        </div>
    </div>
</header>

<!--=========================MAIN==========================-->
<main class="site-main">
    <section class="checkout-section" style="padding-top: 120px; padding-bottom: 80px;">
        <div class="container">
            <div class="cart-process step-2">
                <div class="cart-process-item active">
                    <a href="cart.php" style="display: block; text-decoration: none; color: inherit;">
                        <span></span>
                        <p>Giỏ hàng</p>
                    </a>
                </div>
                <div class="cart-process-item active">
                    <a href="checkout.php" style="display: block; text-decoration: none; color: inherit;">
                        <span></span>
                        <p>Đặt hàng</p>
                    </a>
                </div>
                <div class="cart-process-item">
                    <a href="javascript:void(0)" style="display: block; text-decoration: none; color: inherit; cursor: default;">
                        <span></span>
                        <p>Thanh toán</p>
                    </a>
                </div>
                <div class="cart-process-item">
                    <a href="javascript:void(0)" style="display: block; text-decoration: none; color: inherit; cursor: default;">
                        <span></span>
                        <p>Hoàn thành</p>
                    </a>
                </div>
            </div>

            <div class="checkout-content row" style="display: flex; gap: 40px; margin-top: 40px; flex-wrap: wrap;">
                <!-- LEFT: FORM THÔNG TIN -->
                <div class="checkout-left" style="flex: 1; min-width: 320px;">
                    <h2 style="font-size: 20px; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px;">Thông tin giao hàng</h2>
                    
                    <form id="checkout-form">
                        <div class="form-group" style="margin-bottom: 15px;">
                            <label for="fullname" style="display: block; margin-bottom: 5px; font-weight: 500;">Họ và tên *</label>
                            <input type="text" id="fullname" placeholder="Nhập họ và tên" required style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 4px; font-size: 14px;">
                        </div>

                        <div class="form-row" style="display: flex; gap: 15px; margin-bottom: 15px;">
                            <div class="form-group" style="flex: 1;">
                                <label for="phone" style="display: block; margin-bottom: 5px; font-weight: 500;">Số điện thoại *</label>
                                <input type="tel" id="phone" placeholder="Nhập số điện thoại" required style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 4px; font-size: 14px;">
                            </div>
                            <div class="form-group" style="flex: 1;">
                                <label for="email" style="display: block; margin-bottom: 5px; font-weight: 500;">Email *</label>
                                <input type="email" id="email" placeholder="Nhập email" required style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 4px; font-size: 14px;">
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 15px;">
                            <label for="address" style="display: block; margin-bottom: 5px; font-weight: 500;">Địa chỉ giao hàng *</label>
                            <textarea id="address" placeholder="Nhập địa chỉ đầy đủ (Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố)" required style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 4px; font-size: 14px; min-height: 80px; resize: vertical;"></textarea>
                        </div>

                        <div class="form-group" style="margin-bottom: 25px;">
                            <label for="note" style="display: block; margin-bottom: 5px; font-weight: 500;">Ghi chú đơn hàng</label>
                            <textarea id="note" placeholder="Ghi chú về thời gian giao hàng, hướng dẫn chỉ đường..." style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 4px; font-size: 14px; min-height: 60px; resize: vertical;"></textarea>
                        </div>

                        <!-- PHƯƠNG THỨC GIAO HÀNG -->
                        <h2 style="font-size: 20px; margin-bottom: 15px; text-transform: uppercase; letter-spacing: 1px;">Phương thức giao hàng</h2>
                        <div class="shipping-methods" style="margin-bottom: 25px; display: flex; flex-direction: column; gap: 10px;">
                            <label class="method-option" style="display: flex; align-items: center; justify-content: space-between; padding: 15px; border: 1px solid var(--border-color); border-radius: 6px; cursor: pointer; transition: all 0.2s;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <input type="radio" name="shipping_method" value="Tiêu chuẩn" checked style="width: 18px; height: 18px;">
                                    <div>
                                        <strong style="display: block;">Giao hàng tiêu chuẩn</strong>
                                        <span style="font-size: 12px; color: #666;">Nhận hàng từ 2 - 4 ngày</span>
                                    </div>
                                </div>
                                <strong style="color: var(--primary);">30.000đ</strong>
                            </label>
                            <label class="method-option" style="display: flex; align-items: center; justify-content: space-between; padding: 15px; border: 1px solid var(--border-color); border-radius: 6px; cursor: pointer; transition: all 0.2s;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <input type="radio" name="shipping_method" value="Hỏa tốc" style="width: 18px; height: 18px;">
                                    <div>
                                        <strong style="display: block;">Giao hàng hỏa tốc</strong>
                                        <span style="font-size: 12px; color: #666;">Nhận hàng ngay trong ngày</span>
                                    </div>
                                </div>
                                <strong style="color: var(--primary);">50.000đ</strong>
                            </label>
                        </div>

                        <!-- PHƯƠNG THỨC THANH TOÁN -->
                        <h2 style="font-size: 20px; margin-bottom: 15px; text-transform: uppercase; letter-spacing: 1px;">Phương thức thanh toán</h2>
                        <div class="payment-methods" style="margin-bottom: 25px; display: flex; flex-direction: column; gap: 10px;">
                            <label class="method-option" style="display: flex; align-items: center; gap: 12px; padding: 15px; border: 1px solid var(--border-color); border-radius: 6px; cursor: pointer; transition: all 0.2s;">
                                <input type="radio" name="payment_method" value="COD" checked style="width: 18px; height: 18px;">
                                <i class="fas fa-money-bill-wave" style="color: #4caf50; font-size: 20px;"></i>
                                <div>
                                    <strong style="display: block;">Thanh toán khi nhận hàng (COD)</strong>
                                    <span style="font-size: 12px; color: #666;">Thanh toán bằng tiền mặt khi shipper giao hàng</span>
                                </div>
                            </label>
                            <label class="method-option" style="display: flex; align-items: center; gap: 12px; padding: 15px; border: 1px solid var(--border-color); border-radius: 6px; cursor: pointer; transition: all 0.2s;">
                                <input type="radio" name="payment_method" value="Chuyển khoản" style="width: 18px; height: 18px;">
                                <i class="fas fa-university" style="color: #2196f3; font-size: 20px;"></i>
                                <div>
                                    <strong style="display: block;">Chuyển khoản ngân hàng (VietQR)</strong>
                                    <span style="font-size: 12px; color: #666;">Quét mã QR chuyển khoản nhanh 24/7 ở trang tiếp theo</span>
                                </div>
                            </label>
                            <label class="method-option" style="display: flex; align-items: center; gap: 12px; padding: 15px; border: 1px solid var(--border-color); border-radius: 6px; cursor: pointer; transition: all 0.2s;">
                                <input type="radio" name="payment_method" value="MoMo" style="width: 18px; height: 18px;">
                                <i class="fas fa-wallet" style="color: #d81b60; font-size: 20px;"></i>
                                <div>
                                    <strong style="display: block;">Thanh toán Ví MoMo</strong>
                                    <span style="font-size: 12px; color: #666;">Thanh toán online qua cổng MoMo</span>
                                </div>
                            </label>
                        </div>
                    </form>
                </div>

                <!-- RIGHT: TÓM TẮT ĐƠN HÀNG -->
                <div class="checkout-right" style="width: 380px; min-width: 320px;">
                    <div style="background: #fdfdfd; padding: 25px; border: 1px solid var(--border-color); border-radius: 8px; position: sticky; top: 120px;">
                        <h2 style="font-size: 18px; margin-bottom: 20px; text-transform: uppercase; border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">Đơn hàng của bạn</h2>
                        
                        <!-- Order Items List -->
                        <div id="checkout-items-list" style="max-height: 240px; overflow-y: auto; margin-bottom: 20px; display: flex; flex-direction: column; gap: 15px; padding-right: 5px;">
                            <!-- Dynamic render items -->
                        </div>

                        <!-- Totals -->
                        <div style="display: flex; flex-direction: column; gap: 12px; border-top: 1px solid #eaeaea; padding-top: 20px; margin-bottom: 25px;">
                            <div style="display: flex; justify-content: space-between; font-size: 14px; color: #666;">
                                <span>Tạm tính</span>
                                <strong id="summary-subtotal" style="color: #222;">0đ</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-size: 14px; color: #666;">
                                <span>Phí vận chuyển</span>
                                <strong id="summary-shipping" style="color: #222;">30.000đ</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-size: 14px; color: #d32f2f;">
                                <span>Giảm giá</span>
                                <strong id="summary-discount">-0đ</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-size: 18px; font-weight: bold; border-top: 1px dashed #ddd; padding-top: 15px; color: #221f20; margin-top: 5px;">
                                <span style="font-weight: 600;">Tổng thanh toán</span>
                                <span id="summary-total" style="color: #d62828; font-size: 20px; font-weight: 700;">0đ</span>
                            </div>
                        </div>

                        <button type="submit" form="checkout-form" style="width: 100%; padding: 14px; background: var(--primary); color: #fff; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 15px; text-transform: uppercase; letter-spacing: 1px; transition: opacity 0.2s;">
                            Xác nhận đặt hàng
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

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

<script src="../assets/js/checkout.js"></script>
<script src="../assets/js/scroll.js"></script>
</html>
