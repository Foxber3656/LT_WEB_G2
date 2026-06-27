<?php
require_once __DIR__ . '/../../models/Category.php';

$categoryModel = new Category();
$categories = $categoryModel->getAll();

$message = $_GET['message'] ?? '';
$error = $_GET['error'] ?? '';
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý danh mục</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>

<body class="admin-page">

    <main class="admin-layout">
        <section class="admin-section">

            <div class="admin-page-header">
                <div>
                    <p class="admin-breadcrumb">Admin / Categories</p>
                    <h1 class="admin-page-title">Quản lý danh mục</h1>
                    <p class="admin-page-subtitle">
                        Thêm, chỉnh sửa và quản lý các danh mục sản phẩm.
                    </p>
                </div>

                <a class="admin-btn admin-btn--primary" href="create.php">
                    + Thêm danh mục
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

            <div class="admin-panel">
                <div class="admin-panel__header">
                    <h2>Danh sách danh mục</h2>
                    <span><?= count($categories) ?> danh mục</span>
                </div>

                <div class="admin-table-wrapper">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên danh mục</th>
                                <th class="admin-table__actions">Hành động</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (!empty($categories)): ?>
                                <?php foreach ($categories as $category): ?>
                                    <tr>
                                        <td>#<?= htmlspecialchars($category['id']) ?></td>
                                        <td>
                                            <strong><?= htmlspecialchars($category['name']) ?></strong>
                                        </td>
                                        <td>
                                            <div class="admin-actions">
                                                <a class="admin-btn admin-btn--secondary"
                                                    href="edit.php?id=<?= $category['id'] ?>">
                                                    Sửa
                                                </a>

                                                <a class="admin-btn admin-btn--danger"
                                                    href="delete.php?id=<?= $category['id'] ?>"
                                                    onclick="return confirm('Bạn có chắc muốn xóa danh mục này không?')">
                                                    Xóa
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="admin-empty">
                                        Chưa có danh mục nào.
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