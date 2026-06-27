<?php
require_once __DIR__ . '/../../models/Product.php';

$productModel = new Product();

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

$result = $productModel->delete($id);

if ($result) {
    header('Location: index.php?message=Xóa sản phẩm thành công');
    exit;
}

header('Location: index.php?error=Xóa sản phẩm thất bại');
exit;
