<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/x-icon" href="../assets/images/fashion.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!--=========================CSS==========================-->
    <link rel="stylesheet" href="../assets/css/cart.css?v=1.2">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/gobal.css">

    <title>The Fox - Hóa đơn</title>
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
    <div class="container" style="max-width: 800px; margin: 0 auto;">
        <div class="cart-process step-4" style="margin-bottom: 30px;">
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
            <div class="cart-process-item active">
                <a href="javascript:void(0)" style="display: block; text-decoration: none; color: inherit; cursor: default;">
                    <span></span>
                    <p>Thanh toán</p>
                </a>
            </div>
            <div class="cart-process-item active">
                <a href="javascript:void(0)" style="display: block; text-decoration: none; color: inherit; cursor: default;">
                    <span></span>
                    <p>Hoàn thành</p>
                </a>
            </div>
        </div>

        <div style="background: #fff; padding: 40px; border: 1px solid var(--border-color); border-radius: 8px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05); text-align: center;">
            <i class="fas fa-check-circle" style="color: #4caf50; font-size: 64px; margin-bottom: 15px;"></i>
            <h1 style="font-size: 26px; margin-bottom: 5px;">Đặt hàng thành công!</h1>
            <p style="color: #666; font-size: 14px; margin-bottom: 30px;">Cảm ơn bạn đã lựa chọn mua sắm tại The Fox. Mã đơn hàng của bạn là: <strong id="order-code-display" style="color: var(--primary); font-size: 16px;">...</strong></p>

            <div style="text-align: left; border-top: 1px solid #f0f0f0; padding-top: 25px; margin-bottom: 30px; display: flex; gap: 30px; flex-wrap: wrap;">
                <!-- Thông tin khách hàng -->
                <div style="flex: 1; min-width: 280px; background: #fafafa; padding: 20px; border-radius: 8px; border: 1px solid #eaeaea;">
                    <h3 style="font-size: 16px; margin-bottom: 15px; text-transform: uppercase; color: #221f20; font-weight: 700; border-bottom: 2px solid #221f20; padding-bottom: 8px; display: inline-block;">Thông tin giao hàng</h3>
                    <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 10px;">
                        <div style="display: flex; justify-content: space-between; font-size: 14px; border-bottom: 1px solid #f0f0f0; padding-bottom: 8px;">
                            <span style="color: #666;">Họ tên</span>
                            <strong id="info-name" style="color: #222;">...</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 14px; border-bottom: 1px solid #f0f0f0; padding-bottom: 8px;">
                            <span style="color: #666;">Số điện thoại</span>
                            <strong id="info-phone" style="color: #222;">...</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 14px; border-bottom: 1px solid #f0f0f0; padding-bottom: 8px;">
                            <span style="color: #666;">Email</span>
                            <strong id="info-email" style="color: #222; max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">...</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 14px; border-bottom: 1px solid #f0f0f0; padding-bottom: 8px;">
                            <span style="color: #666;">Địa chỉ</span>
                            <strong id="info-address" style="color: #222; text-align: right;">...</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 14px;">
                            <span style="color: #666;">Vận chuyển</span>
                            <strong id="info-shipping" style="color: #222;">...</strong>
                        </div>
                    </div>
                </div>
                
                <!-- Thanh toán -->
                <div style="flex: 1; min-width: 280px; background: #fafafa; padding: 20px; border-radius: 8px; border: 1px solid #eaeaea;">
                    <h3 style="font-size: 16px; margin-bottom: 15px; text-transform: uppercase; color: #221f20; font-weight: 700; border-bottom: 2px solid #221f20; padding-bottom: 8px; display: inline-block;">Chi tiết thanh toán</h3>
                    <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 10px;">
                        <div style="display: flex; justify-content: space-between; font-size: 14px; border-bottom: 1px solid #f0f0f0; padding-bottom: 8px;">
                            <span style="color: #666;">Hình thức</span>
                            <strong id="info-payment" style="color: #222;">...</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 14px; border-bottom: 1px solid #f0f0f0; padding-bottom: 8px;">
                            <span style="color: #666;">Tạm tính</span>
                            <strong id="bill-subtotal" style="color: #222;">...</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 14px; border-bottom: 1px solid #f0f0f0; padding-bottom: 8px;">
                            <span style="color: #666;">Phí ship</span>
                            <strong id="bill-shipping" style="color: #222;">...</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 14px; border-bottom: 1px solid #f0f0f0; padding-bottom: 8px; color: #d32f2f;">
                            <span>Khấu trừ giảm giá</span>
                            <strong id="bill-discount">...</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 16px; font-weight: bold; padding-top: 5px;">
                            <span style="color: #221f20;">Thành tiền</span>
                            <span id="bill-total" style="color: #d62828; font-size: 18px; font-weight: 700;">...</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PAYMENT DETAIL SECTION (VietQR / COD info) -->
            <div id="payment-instruction-section" style="background: #fdfdfd; border: 1px solid #e0e0e0; border-radius: 6px; padding: 25px; margin-bottom: 30px;">
                <!-- Content generated via JS -->
            </div>

            <div style="display: flex; justify-content: center; gap: 15px; margin-top: 20px;">
                <button onclick="location.href='cartegory.php'" style="padding: 12px 25px; background: #fff; border: 1px solid var(--border-color); color: #333; border-radius: 4px; font-weight: 500; cursor: pointer; transition: background 0.2s;">Tiếp tục mua sắm</button>
                <button onclick="location.href='order.php'" style="padding: 12px 25px; background: var(--primary); border: none; color: #fff; border-radius: 4px; font-weight: bold; cursor: pointer; transition: opacity 0.2s;">Xem lịch sử đơn hàng</button>
            </div>
        </div>
    </div>
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

<script>
document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const orderCodeUrl = urlParams.get('order_code');
    
    let orderData = JSON.parse(sessionStorage.getItem('last_order'));
    
    if (!orderData) {
        document.querySelector('main').innerHTML = `
            <div class="container" style="max-width: 600px; text-align: center; padding: 80px 0;">
                <h2>Không tìm thấy thông tin đơn hàng</h2>
                <a href="cartegory.php" style="color: var(--primary);">Quay lại cửa hàng</a>
            </div>
        `;
        return;
    }
    
    document.getElementById('order-code-display').innerText = orderData.order_code;
    document.getElementById('info-name').innerText = orderData.fullname;
    document.getElementById('info-phone').innerText = orderData.phone;
    document.getElementById('info-email').innerText = orderData.email;
    document.getElementById('info-address').innerText = orderData.address;
    document.getElementById('info-shipping').innerText = `${orderData.shipping_method} (+${formatPrice(orderData.shipping_fee)}đ)`;
    document.getElementById('info-payment').innerText = orderData.payment_method;
    
    document.getElementById('bill-subtotal').innerText = formatPrice(orderData.subtotal) + 'đ';
    document.getElementById('bill-shipping').innerText = '+' + formatPrice(orderData.shipping_fee) + 'đ';
    document.getElementById('bill-discount').innerText = '-' + formatPrice(orderData.discount) + 'đ';
    document.getElementById('bill-total').innerText = formatPrice(orderData.final_total) + 'đ';
    
    const instructSection = document.getElementById('payment-instruction-section');
    if (orderData.payment_method === 'Chuyển khoản') {
        const amount = orderData.final_total;
        const addInfo = encodeURIComponent(orderData.order_code);
        const qrUrl = `https://api.vietqr.io/image/970415-0011004123456-compact.jpg?amount=${amount}&addInfo=${addInfo}&accountName=THE%20FOX%20SHOP`;
        
        instructSection.innerHTML = `
            <h3 style="font-size: 16px; margin: 0 0 15px 0; color: #2196f3; text-transform: uppercase;">Hướng dẫn chuyển khoản qua mã VietQR</h3>
            <div style="display: flex; gap: 20px; align-items: center; justify-content: center; flex-wrap: wrap;">
                <div style="border: 1px solid #ccc; padding: 10px; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                    <img src="${qrUrl}" alt="VietQR Vietcombank" style="width: 220px; height: auto; display: block;">
                </div>
                <div style="text-align: left; max-width: 320px;">
                    <p style="margin: 0 0 10px 0; font-size: 13px; color: #666;">Mở ứng dụng Mobile Banking của ngân hàng bạn đang dùng, chọn tính năng quét QR và thực hiện thanh toán tự động.</p>
                    <p style="margin: 0 0 6px 0; font-size: 14px;"><strong>Ngân hàng:</strong> Vietcombank (VCB)</p>
                    <p style="margin: 0 0 6px 0; font-size: 14px;"><strong>Số tài khoản:</strong> 0011004123456</p>
                    <p style="margin: 0 0 6px 0; font-size: 14px;"><strong>Tên tài khoản:</strong> THE FOX SHOP</p>
                    <p style="margin: 0 0 6px 0; font-size: 14px;"><strong>Số tiền:</strong> <strong style="color: var(--primary);">${formatPrice(amount)}đ</strong></p>
                    <p style="margin: 0 0 6px 0; font-size: 14px;"><strong>Nội dung:</strong> <strong style="color: #2196f3;">${orderData.order_code}</strong></p>
                </div>
            </div>
        `;
    } else if (orderData.payment_method === 'MoMo') {
        instructSection.innerHTML = `
            <h3 style="font-size: 16px; margin: 0 0 12px 0; color: #d81b60; text-transform: uppercase;">Thanh toán qua Ví điện tử MoMo</h3>
            <div style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
                <i class="fas fa-wallet" style="font-size: 40px; color: #d81b60;"></i>
                <p style="margin: 0; font-size: 14px; max-width: 450px; line-height: 1.5;">Vui lòng chuyển khoản đúng số tiền <strong style="color: var(--primary);">${formatPrice(orderData.final_total)}đ</strong> vào số điện thoại ví MoMo <strong>0912345678</strong> (THE FOX) với nội dung chuyển tiền: <strong>${orderData.order_code}</strong>.</p>
            </div>
        `;
    } else {
        instructSection.innerHTML = `
            <h3 style="font-size: 16px; margin: 0 0 12px 0; color: #4caf50; text-transform: uppercase;">Thanh toán khi nhận hàng (COD)</h3>
            <div style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
                <i class="fas fa-truck-loading" style="font-size: 40px; color: #4caf50;"></i>
                <p style="margin: 0; font-size: 14px; max-width: 450px; line-height: 1.5;">Bạn sẽ thanh toán số tiền <strong style="color: var(--primary);">${formatPrice(orderData.final_total)}đ</strong> bằng tiền mặt cho nhân viên giao hàng khi nhận được kiện hàng.</p>
            </div>
        `;
    }
    
    function formatPrice(number) {
        return number.toLocaleString('vi-VN');
    }
});
</script>
<script src="../assets/js/scroll.js"></script>
</body>
</html>
