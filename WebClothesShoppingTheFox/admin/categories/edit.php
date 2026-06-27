<?php
require_once __DIR__ . '/../../models/Category.php';

$categoryModel = new Category();

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    header('Location: index.php?error=ID danh mục không hợp lệ');
    exit;
}

$category = $categoryModel->getById($id);

if (!$category) {
    header('Location: index.php?error=Không tìm thấy danh mục');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');

    if (empty($name)) {
        $error = 'Tên danh mục không được để trống.';
    } else {
        $result = $categoryModel->update($id, $name);

        if ($result) {
            header('Location: index.php?message=Cập nhật danh mục thành công');
            exit;
        } else {
            $error = 'Cập nhật danh mục thất bại.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Sửa danh mục</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>

<body class="admin-page">

    <main class="admin-layout">
        <section class="admin-section">

            <div class="admin-page-header">
                <div>
                    <p class="admin-breadcrumb">Admin / Categories / Edit</p>
                    <h1 class="admin-page-title">Sửa danh mục</h1>
                    <p class="admin-page-subtitle">
                        Cập nhật tên danh mục sản phẩm trong hệ thống.
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
                    <span>Category #<?= htmlspecialchars($category['id']) ?></span>
                </div>

                <form class="admin-form" method="POST" action="">
                    <div class="admin-form__group">
                        <label class="admin-form__label" for="name">
                            Tên danh mục
                        </label>

                        <input class="admin-form__input" type="text" id="name" name="name"
                            value="<?= htmlspecialchars($category['name']) ?>" required>
                    </div>

                    <div class="admin-form__actions">
                        <button class="admin-btn admin-btn--primary" type="submit">
                            Cập nhật danh mục
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