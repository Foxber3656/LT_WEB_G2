<?php
include "../config/database.php";
include "../models/Product.php";

$db = (new Database())->getConnection();
$product = new Product($db);

$items = $product->getAll();
?>

<div class="grid">
<?php foreach($items as $p): ?>
    <div class="product-card">
        <img src="../uploads/products/<?= $p['image'] ?>">
        <h3><?= $p['name'] ?></h3>
        <p><?= $p['price'] ?>$</p>
    </div>
<?php endforeach; ?>
</div>
