<?php
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Category.php';

$productModel = new Product();
$categoryModel = new Category();

$filters = [
    'search' => $_GET['search'] ?? '',
    'category_id' => $_GET['category_id'] ?? '',
    'min_price' => $_GET['min_price'] ?? '',
    'max_price' => $_GET['max_price'] ?? '',
    'sort' => $_GET['sort'] ?? ''
];

$products = $productModel->getAll($filters);
$categories = $categoryModel->getAll();

$currentCategoryName = 'TẤT CẢ SẢN PHẨM';

if (!empty($filters['category_id'])) {
    $currentCategory = $categoryModel->getById($filters['category_id']);

    if ($currentCategory) {
        $currentCategoryName = $currentCategory['name'];
    }
} elseif (!empty($filters['search'])) {
    $currentCategoryName = 'Kết quả tìm kiếm: "' . $filters['search'] . '"';
} elseif (!empty($filters['min_price']) && !empty($filters['max_price'])) {
    $currentCategoryName = 'Sản phẩm từ ' . formatPrice($filters['min_price']) . ' đến ' . formatPrice($filters['max_price']);
} elseif (!empty($filters['min_price'])) {
    $currentCategoryName = 'Sản phẩm từ ' . formatPrice($filters['min_price']) . ' trở lên';
} elseif (!empty($filters['max_price'])) {
    $currentCategoryName = 'Sản phẩm dưới ' . formatPrice($filters['max_price']);
}

function formatPrice($price)
{
    return number_format($price, 0, ',', '.') . 'đ';
}

function productImagePath($image)
{
    if (empty($image)) {
        return '../assets/images/no-image.png';
    }

    $image = trim($image);
    $image = str_replace('\\', '/', $image);

    if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
        return $image;
    }

    $fileName = basename($image);

    return '../assets/images/' . $fileName;
}
function getCategoryIdByName($categories, $name)
{
    foreach ($categories as $category) {
        if ($category['name'] === $name) {
            return $category['id'];
        }
    }

    return '';
}
?>









<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/x-icon" href="../Images/fashion.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!--=========================CSS==========================-->

    <!-- <link rel="stylesheet" href="../assets/css/cartegory.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/gobal.css"> -->
    <link rel="stylesheet" href="../assets/css/gobal.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/cartegory.css?v=2">

    <title>The Fox</title>
</head>

<body>
    <!--=========================HEADER==========================-->
    <header id="header">
        <div class="container">
            <!-- LOGO -->
            <div class="header-logo">
                <a href="#">
                    <img src="../assets/images/icon.png" alt="The Fox Logo" class="logo">
                </a>
            </div>
            <!--=========================NAVBAR==========================-->
            <nav class="navbar">
                <ul class="menu">

                    <!-- WOMEN -->
                    <li class="menu-items">
                        <a href="#">NỮ</a>
                        <div class="mega-menu">
                            <div class="mega-col">
                                <h4>
                                    <a
                                        href="cartegory.php?category_id=<?= getCategoryIdByName($categories, 'Thời trang Nữ') ?>">
                                        TẤT CẢ SẢN PHẨM NỮ
                                    </a>
                                </h4>
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
                                    <li><a href="#">Đồ lót</a></li>
                                </ul>
                            </div>
                            <div class="mega-col">
                                <h4><a href="#">QUẦN</a></h4>
                                <ul>
                                    <li><a href="#">Quần jean</a></li>
                                    <li><a href="#">Quần tây</a></li>
                                    <li><a href="#">Quần short</a></li>
                                    <li><a href="#">Quần legging</a></li>
                                    <li><a href="#">Jumpsuit</a></li>
                                </ul>
                            </div>
                            <div class="mega-col">
                                <h4><a href="#">ĐẦM</a></h4>

                                <ul>
                                    <li><a href="#">Đầm thun</a></li>
                                    <li><a href="#">Áo dài</a></li>
                                    <li><a href="#">Đầm dạ hội</a></li>
                                    <li><a href="#">Đầm công sở</a></li>
                                    <li><a href="#">Chân váy</a></li>
                                </ul>
                            </div>
                        </div>
                    </li>
                    <!-- MEN -->
                    <li class="menu-items">
                        <a href="#">NAM</a>
                        <div class="mega-menu">
                            <div class="mega-col">
                                <h4>
                                    <a
                                        href="cartegory.php?category_id=<?= getCategoryIdByName($categories, 'Thời trang Nam') ?>">
                                        TẤT CẢ SẢN PHẨM NAM
                                    </a>
                                </h4>
                                <ul>
                                    <li><a href="#">NEW ARRIVALS</a></li>
                                </ul>
                            </div>
                            <div class="mega-col">
                                <h4> <a href="#">ÁO</a></h4>
                                <ul>
                                    <li><a href="#">Áo thun</a></li>
                                    <li><a href="#">Áo sơ mi</a></li>
                                    <li><a href="#">Áo polo</a></li>
                                    <li><a href="#">Áo len</a></li>
                                    <li><a href="#">Đồ lót</a></li>
                                </ul>
                            </div>
                            <div class="mega-col">
                                <h4> <a href="#">QUẦN</a></h4>
                                <ul>
                                    <li><a href="#">Quần jean</a></li>
                                    <li><a href="#">Quần tây</a></li>
                                    <li><a href="#">Quần short</a></li>
                                </ul>
                            </div>
                        </div>
                    </li>
                    <!-- CHILDREN -->

                    <li class="menu-items">
                        <a href="#">TRẺ EM</a>
                        <div class="mega-menu">
                            <div class="mega-col">
                                <h4>
                                    <a
                                        href="cartegory.php?category_id=<?= getCategoryIdByName($categories, 'Trẻ Em') ?>">
                                        TẤT CẢ SẢN PHẨM TRẺ EM
                                    </a>
                                </h4>
                                <ul>
                                    <li><a href="#">NEW ARRIVALS</a></li>
                                </ul>
                            </div>
                            <div class="mega-col">
                                <h4> <a href="#">ÁO</a></h4>
                                <ul>
                                    <li><a href="#">Áo thun</a></li>
                                    <li><a href="#">Áo sơ mi</a></li>
                                    <li><a href="#">Áo polo</a></li>
                                    <li><a href="#">Áo len</a></li>
                                    <li><a href="#">Đồ lót</a></li>
                                </ul>
                            </div>
                            <div class="mega-col">
                                <h4> <a href="#">QUẦN</a></h4>
                                <ul>
                                    <li><a href="#">Quần jean</a></li>
                                    <li><a href="#">Quần tây</a></li>
                                    <li><a href="#">Quần short</a></li>
                                </ul>
                            </div>
                        </div>
                    </li>
                    <!-- ACCESSORIES -->

                    <li class="menu-items">
                        <a href="#">PHỤ KIỆN</a>
                        <div class="mega-menu">
                            <div class="mega-col">
                                <h4>
                                    <a
                                        href="cartegory.php?category_id=<?= getCategoryIdByName($categories, 'Phụ Kiện') ?>">
                                        TẤT CẢ PHỤ KIỆN
                                    </a>
                                </h4>
                                <ul>
                                    <li><a href="#">NEW ARRIVALS</a></li>
                                </ul>
                            </div>
                            <div class="mega-col">
                                <h4> <a href="#">VÒNG TAY</a></h4>
                                <ul>
                                    <li><a href="#">Vòng tay bạc</a></li>
                                    <li><a href="#">Vòng tay vàng</a></li>
                                    <li><a href="#">Vòng tay đá</a></li>
                                </ul>
                            </div>
                            <div class="mega-col">
                                <h4> <a href="#">DÂY CHUYỀN</a></h4>
                                <ul>
                                    <li><a href="#">Dây chuyền bạc</a></li>
                                    <li><a href="#">Dây chuyền vàng</a></li>
                                    <li><a href="#">Dây chuyền đá</a></li>
                                </ul>
                            </div>
                            <div class="mega-col">
                                <h4><a href="#">NHẪN</a></h4>
                                <ul>
                                    <li><a href="#">Nhẫn bạc</a></li>
                                    <li><a href="#">Nhẫn vàng</a></li>
                                    <li><a href="#">Nhẫn đá</a></li>
                                </ul>
                            </div>
                        </div>
                    </li>
                    <!-- COLLECTION -->

                    <li class="menu-items">
                        <a href="#">BỘ SƯU TẬP</a>
                        <div class="mega-menu">
                            <div class="mega-col">
                                <h4> <a href="#">BỘ SƯU TẬP MỚI</a></h4>
                                <ul>
                                    <li><a href="#">Bộ sưu tập Xuân Hè 2026</a></li>
                                    <li><a href="#">Bộ sưu tập Thu Đông 2026</a></li>
                                </ul>
                            </div>
                        </div>
                    </li>
                    <!-- SALE -->

                    <li class="sale-menu">
                        <a href="#">SALE</a>
                        <div class="mega-menu">
                            <div class="mega-col">
                                <h4><a href="#">OUTLET đồ NỮ từ 159k</a></h4>
                                <h4><a href="#">OUTLET đồ NAM từ 159k</a></h4>
                                <h4><a href="#">OUTLET đồ TRẺ EM từ 159k</a></h4>
                                <h4><a href="#">OUTLET PHỤ KIỆN từ 159k</a></h4>
                            </div>
                        </div>
                    </li>
                    <!-- BRAND -->

                    <li class="menu-items">
                        <a href="#">THƯƠNG HIỆU</a>
                        <div class="mega-menu">
                            <div class="mega-col">
                                <h4><a href="#">THE FOX</a></h4>
                                <h4><a href="#">THE FOX KIDS</a></h4>
                            </div>
                        </div>
                    </li>
                </ul>
            </nav>
            <!--=========================HEADER ACTION==========================-->
            <div class="header-action">
                <!-- SEARCH -->
                <form class="search-box" method="GET" action="cartegory.php">
                    <input type="text" name="search" placeholder="Tìm kiếm"
                        value="<?= htmlspecialchars($filters['search']) ?>">
                    <button type="submit" style="background: none; border: none; cursor: pointer;">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
                <a class="fa fa-headphones" href="#"></a>
                <a class="fa fa-user" href="#"></a>
                <a class="fa fa-shopping-bag cart-icon-btn" href="javascript:void(0)"></a>
            </div>
        </div>
    </header>
    <!--=========================CATEGORY==========================-->
    <section class="cartegory">
        <div class="container">
            <div class="cartegory-top">
                <p>TRANG CHỦ</p>
                <span>&#8594;</span>
                <p><?= htmlspecialchars($currentCategoryName) ?></p>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <!--=========================CATEGORY LEFT==========================-->
                <div class="cartegory-left">
                    <ul>
                        <li class="cartegory-left-li">
                            <div class="cartegory-title">
                                <a href="cartegory.php">TẤT CẢ SẢN PHẨM</a>
                            </div>
                        </li>

                        <?php foreach ($categories as $category): ?>
                            <?php
                            $categoryName = $category['name'];

                            $subCategories = [
                                'Phụ Kiện' => ['Túi xách', 'Giày dép', 'Mũ nón'],
                                'Trẻ Em' => ['Áo trẻ em', 'Quần trẻ em', 'Váy trẻ em'],
                                'Thời trang Nữ' => ['Áo nữ', 'Váy', 'Chân váy', 'Quần nữ'],
                                'Thời trang Nam' => ['Áo nam', 'Quần nam', 'Áo sơ mi nam']
                            ];

                            $children = $subCategories[$categoryName] ?? [];

                            $isActive = !empty($filters['category_id']) && $filters['category_id'] == $category['id'];
                            ?>

                            <li class="cartegory-left-li <?= $isActive ? 'active' : '' ?>">
                                <div class="cartegory-title">
                                    <a href="cartegory.php?category_id=<?= $category['id'] ?>">
                                        <?= htmlspecialchars($categoryName) ?>
                                    </a>

                                    <?php if (!empty($children)): ?>
                                        <span>+</span>
                                    <?php endif; ?>
                                </div>

                                <?php if (!empty($children)): ?>
                                    <ul>
                                        <?php foreach ($children as $child): ?>
                                            <li>
                                                <a href="cartegory.php?category_id=<?= $category['id'] ?>">
                                                    <?= htmlspecialchars($child) ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!--=========================
            CATEGORY RIGHT
            ==========================-->
                <div class="cartegory-right">
                    <!-- TOP -->
                    <div class="cartegory-right-top row">
                        <div class="cartegory-right-top-item">
                            <p><?= htmlspecialchars($currentCategoryName) ?></p>
                        </div>
                        <div class="cartegory-right-top-item filter-wrapper">
                            <button type="button" id="filterToggle">
                                <span>Bộ lọc</span>
                                <i class="fas fa-chevron-down"></i>
                            </button>

                            <form class="filter-dropdown" method="GET" action="cartegory.php">
                                <input type="hidden" name="search" value="<?= htmlspecialchars($filters['search']) ?>">
                                <input type="hidden" name="sort" value="<?= htmlspecialchars($filters['sort']) ?>">

                                <div class="filter-group">
                                    <label>Danh mục</label>
                                    <select name="category_id">
                                        <option value="">Tất cả danh mục</option>

                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?= $category['id'] ?>"
                                                <?= $filters['category_id'] == $category['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($category['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="filter-group">
                                    <label>Giá từ</label>
                                    <input type="number" name="min_price" placeholder="Ví dụ: 100000"
                                        value="<?= htmlspecialchars($filters['min_price']) ?>">
                                </div>

                                <div class="filter-group">
                                    <label>Giá đến</label>
                                    <input type="number" name="max_price" placeholder="Ví dụ: 1000000"
                                        value="<?= htmlspecialchars($filters['max_price']) ?>">
                                </div>

                                <div class="filter-actions">
                                    <button type="submit">Áp dụng</button>
                                    <a href="cartegory.php">Reset</a>
                                </div>
                            </form>
                        </div>
                        <div class="cartegory-right-top-item">
                            <form method="GET" action="cartegory.php">
                                <input type="hidden" name="search" value="<?= htmlspecialchars($filters['search']) ?>">
                                <input type="hidden" name="category_id"
                                    value="<?= htmlspecialchars($filters['category_id']) ?>">
                                <input type="hidden" name="min_price"
                                    value="<?= htmlspecialchars($filters['min_price']) ?>">
                                <input type="hidden" name="max_price"
                                    value="<?= htmlspecialchars($filters['max_price']) ?>">

                                <select name="sort" onchange="this.form.submit()">
                                    <option value="">Sắp xếp</option>
                                    <option value="price_desc"
                                        <?= $filters['sort'] === 'price_desc' ? 'selected' : '' ?>>
                                        Giá: Cao đến thấp
                                    </option>
                                    <option value="price_asc" <?= $filters['sort'] === 'price_asc' ? 'selected' : '' ?>>
                                        Giá: Thấp đến cao
                                    </option>
                                    <option value="name_asc" <?= $filters['sort'] === 'name_asc' ? 'selected' : '' ?>>
                                        Tên A-Z
                                    </option>
                                    <option value="newest" <?= $filters['sort'] === 'newest' ? 'selected' : '' ?>>
                                        Mới nhất
                                    </option>
                                </select>
                            </form>
                        </div>
                    </div>

                    <!--=========================
                PRODUCT CONTENT
                ==========================-->
                    <div class="cartegory-right-content row">
                        <?php if (!empty($products)): ?>
                            <?php foreach ($products as $product): ?>
                                <a href="product.php?id=<?= $product['id'] ?>" class="cartegory-right-content-item"
                                    style="text-decoration: none; color: inherit;">
                                    <img src="<?= htmlspecialchars(productImagePath($product['image'])) ?>"
                                        alt="<?= htmlspecialchars($product['name']) ?>">
                                    <h1>
                                        <?= htmlspecialchars($product['name']) ?>
                                    </h1>
                                    <p>
                                        <?= formatPrice($product['price']) ?>
                                    </p>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Không tìm thấy sản phẩm nào.</p>
                        <?php endif; ?>
                    </div>
                    <div class="cartegory-right-bottom">
                        <div class="pagination">
                            <a href="#" class="page-btn">
                                &laquo;
                            </a>
                            <a href="#" class="page-btn active">1</a>
                            <a href="#" class="page-btn">2</a>
                            <a href="#" class="page-btn">3</a>
                            <a href="#" class="page-btn">4</a>
                            <a href="#" class="page-btn">5</a>
                            <a href="#" class="page-btn">&raquo;</a>
                            <a href="#" class="page-btn last">Trang cuối</a>
                        </div>
                    </div>
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


    <script src="../assets/js/animation.js"></script>
    <script src="../assets/js/scroll.js"></script>
    <script src="../assets/js/cartegory.js?v=2"></script>
</body>

</html>