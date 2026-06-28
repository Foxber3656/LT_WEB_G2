<?php
// pages/outfit-builder.php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once '../config/config.php';
require_once '../config/database.php';
require_once '../models/Product.php';

$productModel = new Product($conn);

// Tìm sản phẩm thực tế từ DB, nếu trống sẽ fallback sang mảng rỗng
$tops = $productModel->getProductsByType('top') ?: [];
$bottoms = $productModel->getProductsByType('bottom') ?: [];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Outfit Builder - THE FOX</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background-color: #f8f9fa; color: #111; padding: 20px; }
        .builder-container { display: flex; max-width: 1200px; margin: 30px auto; gap: 30px; }
        
        /* Cột trái - Khu vực Canvas Preview */
        .preview-section { flex: 1; background: #fff; border-radius: 16px; padding: 30px; display: flex; flex-direction: column; align-items: center; box-shadow: 0 4px 20px rgba(0,0,0,0.02); position: sticky; top: 20px; height: fit-content; }
        .canvas-box { width: 320px; height: 460px; background: #fdfdfd; border: 1px dashed #ddd; border-radius: 12px; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 15px; gap: 10px; position: relative; }
        .canvas-box img { max-width: 100%; max-height: 210px; object-fit: contain; transition: 0.3s ease; }
        .canvas-box .empty-slot { color: #aaa; font-size: 0.9rem; font-style: italic; }
        .outfit-meta { margin-top: 20px; width: 100%; text-align: center; }
        .outfit-name-input { width: 80%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; text-align: center; font-size: 1rem; outline: none; margin-bottom: 15px; }
        .outfit-name-input:focus { border-color: #ff5722; }
        .btn-action { width: 80%; padding: 12px; background: #ff5722; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.3s; font-size: 1rem; }
        .btn-action:hover { background: #e64a19; }

        /* Cột phải - Danh sách quần áo lựa chọn */
        .selector-section { flex: 1.5; background: #fff; border-radius: 16px; padding: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); }
        .tabs { display: flex; border-bottom: 2px solid #eee; margin-bottom: 25px; gap: 20px; }
        .tab-item { padding-bottom: 12px; font-weight: 600; cursor: pointer; color: #777; position: relative; }
        .tab-item.active { color: #ff5722; }
        .tab-item.active::after { content: ''; position: absolute; bottom: -2px; left: 0; width: 100%; height: 2px; background: #ff5722; }
        .products-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; display: none; }
        .products-grid.active { display: grid; }
        
        .product-card { border: 1px solid #eee; border-radius: 12px; padding: 15px; text-align: center; background: #fff; transition: 0.3s; position: relative; }
        .product-card:hover { border-color: #ff5722; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.02); }
        .product-card img { height: 140px; object-fit: contain; margin-bottom: 12px; }
        .product-card h4 { font-size: 0.95rem; margin-bottom: 6px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .product-card .price { color: #ff5722; font-weight: 700; font-size: 0.9rem; margin-bottom: 12px; }
        .card-actions { display: flex; gap: 8px; justify-content: center; }
        .btn-select { padding: 8px 16px; background: #111; color: #fff; border: none; border-radius: 6px; font-size: 0.85rem; font-weight: 600; cursor: pointer; }
        .btn-select:hover { background: #ff5722; }
        .btn-wishlist { background: #f5f5f5; color: #333; border: none; width: 33px; height: 33px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
        .btn-wishlist:hover { color: #db4437; background: #fff3f3; }
        /* --- CẤU HÌNH RESPONSIVE TOÀN DIỆN CHO OUTFIT BUILDER --- */
@media (max-width: 992px) {
    /* Khi màn hình nhỏ (Tablet & Mobile), chuyển từ dạng 2 cột ngang thành 1 cột dọc */
    .builder-container {
        display: flex !important;
        flex-direction: column !important;
        gap: 20px !important;
        padding: 10px !important;
    }

    /* Khung preview (Canvas) sẽ chiếm full chiều rộng màn hình */
    .preview-section {
        width: 100% !important;
        position: static !important; /* Bỏ cố định vị trí để không bị đè lên phần khác */
        margin-bottom: 10px;
    }

    /* Khung chọn quần áo bên phải cũng chiếm full chiều rộng */
    .products-section {
        width: 100% !important;
    }

    /* Điều chỉnh lưới hiển thị danh sách áo/quần trên di động */
    .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)) !important; /* Hiển thị 2 cột sản phẩm đều nhau trên mobile */
        gap: 15px !important;
    }

    /* Thu nhỏ nhẹ khung Canvas để vừa vặn màn hình điện thoại */
    .canvas-box {
        height: 380px !important;
        padding: 10px !important;
    }
    
    .canvas-box img {
        max-height: 160px !important;
    }
}

@media (max-width: 480px) {
    /* Tối ưu riêng cho điện thoại màn hình nhỏ */
    body {
        padding: 5px !important;
    }
    
    h2 {
        font-size: 1.5rem !important;
    }
    
    /* Thu nhỏ bớt các nút tab "Chọn Áo", "Chọn Quần" để không bị tràn dòng */
    .tab-menu button {
        padding: 8px 12px !important;
        font-size: 0.9rem !important;
    }
}
    </style>
</head>
<body>

    <h2 style="text-align: center; margin-top: 20px; font-weight: 800; letter-spacing: 0.5px;">OUTFIT BUILDER</h2>
    <p style="text-align: center; color: #666; font-size: 0.95rem;">Tự do phối hợp những trang phục thời thượng nhất cùng THE FOX</p>

    <div style="text-align: center; margin-top: 15px; margin-bottom: 5px;">
        <a href="my-outfits.php" style="display: inline-block; padding: 10px 20px; background: #111; color: #fff; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 0.9rem; transition: 0.3s;">
            <i class="fa-solid fa-shirt"></i> Xem tủ đồ đã lưu của tôi
        </a>
    </div>

    <div class="builder-container">
        <div class="preview-section">
            <div class="canvas-box" id="canvas">
                <div id="slot-top" class="empty-slot">Chưa chọn áo</div>
                <div id="slot-bottom" class="empty-slot">Chưa chọn quần</div>
            </div>
            <div class="outfit-meta">
                <input type="text" id="outfit-name" class="outfit-name-input" value="Bộ phối cá tính của tôi">
                <button class="btn-action" onclick="submitOutfit()">Lưu bộ phối đồ này</button>
            </div>
        </div>

        <div class="selector-section">
            <div class="tabs">
                <div class="tab-item active" onclick="switchTab('tops')">Chọn Áo (Tops)</div>
                <div class="tab-item" onclick="switchTab('bottoms')">Chọn Quần/Váy (Bottoms)</div>
            </div>

            <div id="panel-tops" class="products-grid active">
                <?php foreach($tops as $item): ?>
                <div class="product-card" data-id="<?php echo $item['id']; ?>">
                    <?php 
                    $src_image = (strpos($item['image'], 'http') === 0) ? $item['image'] : "../uploads/products/" . $item['image'];
                    ?>
                    <img src="<?php echo $src_image; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                    <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                    <div class="price"><?php echo number_format($item['price']); ?>đ</div>
                    <div class="card-actions">
                        <button class="btn-select" onclick="selectItem('top', <?php echo $item['id']; ?>, '<?php echo $src_image; ?>')">Mặc thử</button>
                        <button class="btn-wishlist" onclick="addToWishlist(<?php echo $item['id']; ?>)"><i class="fa-regular fa-heart"></i></button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div id="panel-bottoms" class="products-grid">
                <?php foreach($bottoms as $item): ?>
                <div class="product-card" data-id="<?php echo $item['id']; ?>">
                    <?php 
                    $src_image = (strpos($item['image'], 'http') === 0) ? $item['image'] : "../uploads/products/" . $item['image'];
                    ?>
                    <img src="<?php echo $src_image; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                    <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                    <div class="price"><?php echo number_format($item['price']); ?>đ</div>
                    <div class="card-actions">
                        <button class="btn-select" onclick="selectItem('bottom', <?php echo $item['id']; ?>, '<?php echo $src_image; ?>')">Mặc thử</button>
                        <button class="btn-wishlist" onclick="addToWishlist(<?php echo $item['id']; ?>)"><i class="fa-regular fa-heart"></i></button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
        // Đọc tham số truyền từ trang gợi ý (nếu có)
        const urlParams = new URLSearchParams(window.location.search);
        
        let currentOutfit = {
            top: urlParams.get('preload_top') ? parseInt(urlParams.get('preload_top')) : null,
            bottom: urlParams.get('preload_bottom') ? parseInt(urlParams.get('preload_bottom')) : null,
            accessory: null
        };

        // Tự động hiển thị đồ phối khi nhảy từ trang gợi ý sang
        window.addEventListener('DOMContentLoaded', () => {
            if(currentOutfit.top) {
                const topCard = document.querySelector(`.product-card[data-id="${currentOutfit.top}"]`);
                if(topCard) {
                    const imgUrl = topCard.querySelector('img').src;
                    selectItem('top', currentOutfit.top, imgUrl);
                }
            }
            if(currentOutfit.bottom) {
                const bottomCard = document.querySelector(`.product-card[data-id="${currentOutfit.bottom}"]`);
                if(bottomCard) {
                    const imgUrl = bottomCard.querySelector('img').src;
                    selectItem('bottom', currentOutfit.bottom, imgUrl);
                }
            }
        });

        function switchTab(tabName) {
            document.getElementById('panel-tops').classList.remove('active');
            document.getElementById('panel-bottoms').classList.remove('active');
            document.getElementById('panel-' + tabName).classList.add('active');

            const tabs = document.querySelectorAll('.tab-item');
            tabs.forEach(tab => tab.classList.remove('active'));
            if (tabName === 'tops') tabs[0].classList.add('active');
            else tabs[1].classList.add('active');
        }

        function selectItem(type, id, imgSrc) {
            if (type === 'top') {
                currentOutfit.top = id;
                document.getElementById('slot-top').innerHTML = `<img src="${imgSrc}" alt="Áo" style="max-width:100%; max-height:210px; object-fit:contain;">`;
            } else if (type === 'bottom') {
                currentOutfit.bottom = id;
                document.getElementById('slot-bottom').innerHTML = `<img src="${imgSrc}" alt="Quần" style="max-width:100%; max-height:210px; object-fit:contain;">`;
            }
        }

        function submitOutfit() {
            if (!currentOutfit.top || !currentOutfit.bottom) {
                alert('Vui lòng chọn đầy đủ cả Áo và Quần trước khi lưu nhé!');
                return;
            }

            const outfitNameInput = document.getElementById('outfit-name');
            const outfitName = outfitNameInput ? outfitNameInput.value.trim() : 'Bộ phối của tôi';

            fetch('../controllers/OutfitController.php?action=save_outfit', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    outfit_name: outfitName,
                    top_id: currentOutfit.top,
                    bottom_id: currentOutfit.bottom,
                    accessory_id: currentOutfit.accessory
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => { throw new Error(text) });
                }
                return response.json();
            })
            .then(data => {
                alert(data.message);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Phản hồi hệ thống lỗi:\n' + error.message);
            });
        }

        function addToWishlist(productId) {
            fetch('../controllers/OutfitController.php?action=add_wishlist', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ product_id: productId })
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => { throw new Error(text) });
                }
                return response.json();
            })
            .then(data => {
                alert(data.message);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Phản hồi hệ thống lỗi:\n' + error.message);
            });
        }
    </script>
</body>
</html>
