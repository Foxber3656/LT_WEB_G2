<!--=========================CART SIDEBAR==========================-->
<link rel="stylesheet" href="../assets/css/sidebarcart.css">

<div class="cart-overlay"></div>
<section class="cart-sidebar">
    <!--=========================TOP==========================-->
    <div class="cart-top">
        <h2>
            Giỏ hàng
            <span class="cart-count">0</span>
        </h2>
        <button class="close-cart">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!--=========================PRODUCT LIST==========================-->
    <div class="cart-items">
        <!-- Items will be rendered dynamically by JavaScript from localStorage -->
    </div>
    
    <!--=========================BOTTOM==========================-->
    <div class="cart-bottom">
        <div class="cart-total">
            <h3>Tổng cộng</h3>
            <p class="cart-total-price">0đ</p>
        </div>
        <button class="view-cart" onclick="location.href='cart.php'">
            XEM GIỎ HÀNG
        </button>
    </div>
</section>

<script src="../assets/js/sidebarcart.js"></script>