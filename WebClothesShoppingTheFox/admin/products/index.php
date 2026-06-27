<?php
require_once __DIR__ . '/../../models/Product.php';
require_once __DIR__ . '/../../models/Category.php';

$productModel = new Product();
$categoryModel = new Category();

$filters = [
    'search' => $_GET['search'] ?? '',
    'category_id' => $_GET['category_id'] ?? '',
    'sort' => $_GET['sort'] ?? ''
];

$products = $productModel->getAll($filters);
$categories = $categoryModel->getAll();

$message = $_GET['message'] ?? '';
$error = $_GET['error'] ?? '';

function formatPrice($price)
{
    return number_format($price, 0, ',', '.') . 'đ';
}

function productImagePath($image)
{
    if (empty($image)) {
        return '../../assets/images/no-image.png';
    }

    $image = trim($image);
    $image = str_replace('\\', '/', $image);

    if (strpos($image, 'http://') === 0 || strpos($image, 'https://') === 0) {
        return $image;
    }

    $fileName = basename($image);

    return '../../assets/images/' . $fileName;
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý sản phẩm</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>

<body class="admin-page">

    <main class="admin-layout">
        <section class="admin-section">

            <div class="admin-page-header">
                <div>
                    <p class="admin-breadcrumb">Admin / Products</p>
                    <h1 class="admin-page-title">Quản lý sản phẩm</h1>
                    <p class="admin-page-subtitle">
                        Quản lý danh sách sản phẩm, tìm kiếm, lọc và sắp xếp sản phẩm.
                    </p>
                </div>

                <a class="admin-btn admin-btn--primary" href="create.php">
                    + Thêm sản phẩm
                </a>
            </div>

            <?php if (!empty($message)): ?>
                <div class="admin-alert admin-alert--success">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="admin-alert admin-alert--error">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <div class="admin-panel admin-filter-panel">
                <form class="admin-filter-form" method="GET" action="index.php">
                    <div class="admin-form__group">
                        <label class="admin-form__label" for="search">Tìm kiếm</label>
                        <input class="admin-form__input" type="text" id="search" name="search"
                            placeholder="Nhập tên sản phẩm..." value="<?= htmlspecialchars($filters['search']) ?>">
                    </div>

                    <div class="admin-form__group">
                        <label class="admin-form__label" for="category_id">Danh mục</label>
                        <select class="admin-form__select" id="category_id" name="category_id">
                            <option value="">Tất cả danh mục</option>

                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>"
                                    <?= $filters['category_id'] == $category['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="admin-form__group">
                        <label class="admin-form__label" for="sort">Sắp xếp</label>
                        <select class="admin-form__select" id="sort" name="sort">
                            <option value="">Mặc định</option>
                            <option value="newest" <?= $filters['sort'] === 'newest' ? 'selected' : '' ?>>
                                Mới nhất
                            </option>
                            <option value="price_asc" <?= $filters['sort'] === 'price_asc' ? 'selected' : '' ?>>
                                Giá tăng dần
                            </option>
                            <option value="price_desc" <?= $filters['sort'] === 'price_desc' ? 'selected' : '' ?>>
                                Giá giảm dần
                            </option>
                            <option value="name_asc" <?= $filters['sort'] === 'name_asc' ? 'selected' : '' ?>>
                                Tên A-Z
                            </option>
                        </select>
                    </div>

                    <div class="admin-filter-form__actions">
                        <button class="admin-btn admin-btn--primary" type="submit">
                            Lọc
                        </button>

                        <a class="admin-btn admin-btn--secondary" href="index.php">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="admin-panel">
                <div class="admin-panel__header">
                    <h2>Danh sách sản phẩm</h2>
                    <span><?= count($products) ?> sản phẩm</span>
                </div>

                <div class="admin-table-wrapper">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Ảnh</th>
                                <th>Tên sản phẩm</th>
                                <th>Danh mục</th>
                                <th>Giá</th>
                                <th>Ngày tạo</th>
                                <th class="admin-table__actions">Hành động</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (!empty($products)): ?>
                                <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td>#<?= htmlspecialchars($product['id']) ?></td>

                                        <td>
                                            <img class="admin-product-image"
                                                src="<?= htmlspecialchars(productImagePath($product['image'])) ?>"
                                                alt="<?= htmlspecialchars($product['name']) ?>">
                                        </td>

                                        <td>
                                            <strong><?= htmlspecialchars($product['name']) ?></strong>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($product['category_name'] ?? 'Chưa có danh mục') ?>
                                        </td>

                                        <td>
                                            <?= formatPrice($product['price']) ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($product['created_at'] ?? '') ?>
                                        </td>

                                        <td>
                                            <div class="admin-actions">
                                                <a class="admin-btn admin-btn--secondary"
                                                    href="edit.php?id=<?= $product['id'] ?>">
                                                    Sửa
                                                </a>

                                                <a class="admin-btn admin-btn--danger"
                                                    href="delete.php?id=<?= $product['id'] ?>"
                                                    onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này không?')">
                                                    Xóa
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="admin-empty">
                                        Không tìm thấy sản phẩm nào.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <a class="admin-back-link" href="../dashboard.php">
                ← Quay lại Dashboard
            </a>

        </section>
    </main>

</body>

</html>