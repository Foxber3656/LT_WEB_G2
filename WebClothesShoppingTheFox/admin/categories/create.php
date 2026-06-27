<?php
require_once __DIR__ . '/../../models/Category.php';

$categoryModel = new Category();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');

    if (empty($name)) {
        $error = 'Tên danh mục không được để trống.';
    } else {
        $result = $categoryModel->create($name);

        if ($result) {
            header('Location: index.php?message=Thêm danh mục thành công');
            exit;
        } else {
            $error = 'Thêm danh mục thất bại.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Thêm danh mục</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>

<body class="admin-page">

    <main class="admin-layout">
        <section class="admin-section">

            <div class="admin-page-header">
                <div>
                    <p class="admin-breadcrumb">Admin / Categories / Create</p>
                    <h1 class="admin-page-title">Thêm danh mục</h1>
                    <p class="admin-page-subtitle">
                        Tạo danh mục mới để phân loại sản phẩm trong cửa hàng.
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
                    <h2>Thông tin danh mục</h2>
                    <span>Category Create</span>
                </div>

                <form class="admin-form" method="POST" action="">
                    <div class="admin-form__group">
                        <label class="admin-form__label" for="name">
                            Tên danh mục
                        </label>

                        <input class="admin-form__input" type="text" id="name" name="name"
                            placeholder="Ví dụ: Áo, Quần, Phụ kiện..." required>
                    </div>

                    <div class="admin-form__actions">
                        <button class="admin-btn admin-btn--primary" type="submit">
                            Thêm danh mục
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