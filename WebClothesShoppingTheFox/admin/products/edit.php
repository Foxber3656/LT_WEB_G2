<?php
require_once __DIR__ . '/../../models/Product.php';
require_once __DIR__ . '/../../models/Category.php';

$productModel = new Product();
$categoryModel = new Category();

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    header('Location: index.php?error=ID sản phẩm không hợp lệ');
    exit;
}

$product = $productModel->getById($id);

if (!$product) {
    header('Location: index.php?error=Không tìm thấy sản phẩm');
    exit;
}

$categories = $categoryModel->getAll();

$error = '';

$name = $product['name'] ?? '';
$price = $product['price'] ?? '';
$image = $product['image'] ?? '';
$categoryId = $product['category_id'] ?? '';
$description = $product['description'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $image = trim($_POST['image'] ?? '');
    $categoryId = $_POST['category_id'] ?? '';
    $description = trim($_POST['description'] ?? '');

    if (empty($name)) {
        $error = 'Tên sản phẩm không được để trống.';
    } elseif ($price === '' || !is_numeric($price) || $price < 0) {
        $error = 'Giá sản phẩm không hợp lệ.';
    } elseif (empty($image)) {
        $error = 'Ảnh sản phẩm không được để trống.';
    } else {
        $categoryId = !empty($categoryId) ? $categoryId : null;

        $result = $productModel->update(
            $id,
            $name,
            $price,
            $image,
            $categoryId,
            $description
        );

        if ($result) {
            header('Location: index.php?message=Cập nhật sản phẩm thành công');
            exit;
        } else {
            $error = 'Cập nhật sản phẩm thất bại.';
        }
    }
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
    <title>Sửa sản phẩm</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>

<body class="admin-page">

    <main class="admin-layout">
        <section class="admin-section">

            <div class="admin-page-header">
                <div>
                    <p class="admin-breadcrumb">Admin / Products / Edit</p>
                    <h1 class="admin-page-title">Sửa sản phẩm</h1>
                    <p class="admin-page-subtitle">
                        Cập nhật thông tin sản phẩm trong hệ thống.
                    </p>
                </div>

                <a class="admin-btn admin-btn--secondary" href="index.php">
                    ← Quay lại
                </a>
            </div>

            <?php if (!empty($error)): ?>
                <div class="admin-alert admin-alert--error">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <div class="admin-panel">
                <div class="admin-panel__header">
                    <h2>Thông tin sản phẩm</h2>
                    <span>Product #<?= htmlspecialchars($id) ?></span>
                </div>

                <form class="admin-form" method="POST" action="">
                    <div class="admin-form__group">
                        <label class="admin-form__label" for="name">
                            Tên sản phẩm
                        </label>

                        <input class="admin-form__input" type="text" id="name" name="name"
                            value="<?= htmlspecialchars($name) ?>" required>
                    </div>

                    <div class="admin-form__group">
                        <label class="admin-form__label" for="price">
                            Giá sản phẩm
                        </label>

                        <input class="admin-form__input" type="number" id="price" name="price"
                            value="<?= htmlspecialchars($price) ?>" min="0" required>
                    </div>

                    <div class="admin-form__group">
                        <label class="admin-form__label" for="image">
                            Ảnh sản phẩm
                        </label>

                        <input class="admin-form__input" type="text" id="image" name="image"
                            value="<?= htmlspecialchars($image) ?>" required>

                        <p class="admin-form__hint">
                            Nhập tên file ảnh trong thư mục assets/images, ví dụ: sp1.jpg
                        </p>

                        <div class="admin-image-preview">
                            <p>Ảnh hiện tại:</p>
                            <img src="<?= htmlspecialchars(productImagePath($image)) ?>"
                                alt="<?= htmlspecialchars($name) ?>">
                        </div>
                    </div>

                    <div class="admin-form__group">
                        <label class="admin-form__label" for="category_id">
                            Danh mục
                        </label>

                        <select class="admin-form__select" id="category_id" name="category_id">
                            <option value="">Chưa chọn danh mục</option>

                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>"
                                    <?= $categoryId == $category['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="admin-form__group">
                        <label class="admin-form__label" for="description">
                            Mô tả sản phẩm
                        </label>

                        <textarea class="admin-form__textarea" id="description" name="description"
                            placeholder="Nhập mô tả sản phẩm..."><?= htmlspecialchars($description) ?></textarea>
                    </div>

                    <div class="admin-form__actions">
                        <button class="admin-btn admin-btn--primary" type="submit">
                            Cập nhật sản phẩm
                        </button>

                        <a class="admin-btn admin-btn--secondary" href="index.php">
                            Hủy
                        </a>
                    </div>
                </form>
            </div>

        </section>
    </main>

</body>

</html>