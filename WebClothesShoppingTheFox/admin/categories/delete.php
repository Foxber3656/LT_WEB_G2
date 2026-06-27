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

$productCount = $categoryModel->countProducts($id);

if ($productCount > 0) {
    header('Location: index.php?error=Không thể xóa danh mục vì vẫn còn sản phẩm thuộc danh mục này');
    exit;
}

$result = $categoryModel->delete($id);

if ($result) {
    header('Location: index.php?message=Xóa danh mục thành công');
    exit;
}

header('Location: index.php?error=Xóa danh mục thất bại');
exit;
