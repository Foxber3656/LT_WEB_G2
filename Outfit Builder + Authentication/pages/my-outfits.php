<?php
// pages/my-outfits.php
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}
require_once '../config/config.php';
require_once '../config/database.php';
require_once '../models/Product.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user']) && !isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Hoặc index.php tùy cấu trúc của bạn
    exit();
}

$user_id = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : ($_SESSION['user_id'] ?? null);

$productModel = new Product($conn);
$myOutfits = $productModel->getUserOutfits($user_id);

// --- TÍNH NĂNG GỢI Ý THÔNG MINH ---
// Lấy danh sách ngẫu nhiên 4 áo và 4 quần để làm gợi ý phối đồ hot xu hướng
$allTops = $productModel->getProductsByType('top') ?: [];
$allBottoms = $productModel->getProductsByType('bottom') ?: [];

shuffle($allTops);
shuffle($allBottoms);

$suggestedOutfits = [];
// Tạo ra 3 bộ phối gợi ý tự động từ kho sản phẩm
for ($i = 0; $i < min(3, count($allTops), count($allBottoms)); $i++) {
    $suggestedOutfits[] = [
        'name' => 'Gợi ý Xu hướng #' . ($i + 1),
        'top' => $allTops[$i],
        'bottom' => $allBottoms[$i]
    ];
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tủ Đồ Của Tôi - THE FOX</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background-color: #f8f9fa; color: #111; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        
        .header-title { text-align: center; margin-bottom: 40px; }
        .header-title h2 { font-weight: 800; letter-spacing: 0.5px; font-size: 2rem; margin-bottom: 10px; }
        .header-title p { color: #666; }

        .section-title { font-size: 1.4rem; font-weight: 700; margin-bottom: 20px; border-left: 4px solid #ff5722; padding-left: 10px; display: flex; justify-content: space-between; align-items: center; }
        .btn-back { font-size: 0.9rem; background: #111; color: #fff; padding: 8px 16px; border-radius: 6px; text-decoration: none; transition: 0.3s; }
        .btn-back:hover { background: #ff5722; }

        /* Lưới hiển thị các bộ phối đồ */
        .outfit-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 30px; margin-bottom: 60px; }
        .outfit-card { background: #fff; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #eee; padding: 20px; display: flex; flex-direction: column; transition: 0.3s; position: relative; }
        .outfit-card:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.07); border-color: #ff5722; }
        
        /* Cấu trúc thanh tiêu đề bộ phối gồm Tên + Nút xóa */
        .outfit-card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .outfit-card h3 { font-size: 1.1rem; color: #333; font-weight: 700; margin: 0; text-align: left; }
        .btn-delete-outfit { background: none; border: none; color: #ff4d4d; cursor: pointer; font-size: 1.1rem; transition: 0.2s; padding: 2px 6px; border-radius: 4px; }
        .btn-delete-outfit:hover { color: #cc0000; background-color: #fff1f1; }
        
        /* Khu vực hiển thị áo lồng quần */
        .outfit-preview-box { background: #fafafa; border-radius: 12px; padding: 15px; display: flex; flex-direction: column; gap: 10px; align-items: center; justify-content: center; height: 320px; border: 1px dashed #e0e0e0; }
        .outfit-preview-box img { max-height: 140px; max-width: 100%; object-fit: contain; }
        .item-separator { width: 40px; height: 1px; background: #ddd; margin: 5px 0; }

        .outfit-total-price { text-align: center; margin-top: 15px; font-weight: 700; color: #ff5722; font-size: 1.05rem; }
        
        .no-data { text-align: center; padding: 40px; background: #fff; border-radius: 16px; color: #777; grid-column: 1 / -1; border: 1px dashed #ccc; }

        /* Style cho phần gợi ý */
        .suggestion-section { background: #fff3ed; border-radius: 20px; padding: 30px; border: 1px solid #ffd8c4; }
        .suggestion-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 30px; }
        .btn-mix-again { width: 100%; margin-top: 15px; padding: 10px; background: transparent; border: 2px solid #ff5722; color: #ff5722; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.3s; }
        .btn-mix-again:hover { background: #ff5722; color: #fff; }
        /* --- CẤU HÌNH RESPONSIVE CHO DI ĐỘNG --- */
@media (max-width: 768px) {
    body {
        padding: 10px; /* Giảm khoảng cách viền ngoài trên điện thoại */
    }
    .container {
        padding: 5px;
    }
    .header-title h2 {
        font-size: 1.6rem; /* Thu nhỏ tiêu đề chính một chút cho vừa màn hình */
    }
    .section-title {
        font-size: 1.1rem;
        flex-direction: column; /* Đẩy nút "Vào phòng phối đồ" xuống dưới tiêu đề nếu màn hình hẹp */
        align-items: flex-start;
        gap: 10px;
    }
    .btn-back {
        width: 100%; /* Nút bấm rộng hết cỡ trên mobile để dễ ấn bằng ngón tay */
        text-align: center;
    }
    /* Chỉnh lại lưới hiển thị để ôm sát màn hình điện thoại nhỏ */
    .outfit-grid, .suggestion-grid {
        grid-template-columns: repeat(auto-fill, minmax(100%, 1fr)); 
        gap: 20px;
    }
    .outfit-preview-box {
        height: 280px; /* Giảm nhẹ chiều cao khung preview để đỡ cuộn trang nhiều */
    }
    .outfit-preview-box img {
        max-height: 120px;
    }
    .suggestion-section {
        padding: 15px; /* Thu nhỏ khoảng đệm của vùng gợi ý */
    }
}
    </style>
</head>
<body>

    <div class="container">
        <div class="header-title">
            <h2>TỦ ĐỒ PHỐI CỦA BẠN</h2>
            <p>Nơi lưu giữ những phong cách mang đậm dấu ấn cá nhân của tài khoản: <strong><?php echo htmlspecialchars($_SESSION['user']['email'] ?? 'Thành viên'); ?></strong></p>
        </div>

        <div class="section-title">
            <span>Bộ trang phục bạn đã lưu</span>
            <a href="outfit-builder.php" class="btn-back"><i class="fa-solid fa-wand-magic-sparkles"></i> Vào phòng phối đồ</a>
        </div>

        <div class="outfit-grid">
            <?php if (empty($myOutfits)): ?>
                <div class="no-data">
                    <i class="fa-regular fa-folder-open" style="font-size: 3rem; color: #ccc; margin-bottom: 10px; display:block;"></i>
                    Bạn chưa lưu bộ phối đồ nào cả. Hãy vào phòng phối đồ để tự tạo phong cách nhé!
                </div>
            <?php else: ?>
                <?php foreach ($myOutfits as $outfit): 
                    $topImg = (strpos($outfit['top_image'], 'http') === 0) ? $outfit['top_image'] : "../uploads/products/" . $outfit['top_image'];
                    $bottomImg = (strpos($outfit['bottom_image'], 'http') === 0) ? $outfit['bottom_image'] : "../uploads/products/" . $outfit['bottom_image'];
                    $totalPrice = ($outfit['top_price'] ?? 0) + ($outfit['bottom_price'] ?? 0);
                ?>
                <div class="outfit-card" id="outfit-row-<?php echo $outfit['id']; ?>">
                    <div class="outfit-card-header">
                        <h3><?php echo htmlspecialchars($outfit['outfit_name']); ?></h3>
                        <button class="btn-delete-outfit" onclick="deleteOutfit(<?php echo $outfit['id']; ?>)" title="Xóa bộ phối này">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>

                    <div class="outfit-preview-box">
                        <?php if ($outfit['top_image']): ?>
                            <img src="<?php echo $topImg; ?>" alt="Áo">
                        <?php endif; ?>
                        
                        <div class="item-separator"></div>
                        
                        <?php if ($outfit['bottom_image']): ?>
                            <img src="<?php echo $bottomImg; ?>" alt="Quần">
                        <?php endif; ?>
                    </div>
                    <div class="outfit-total-price">
                        Tổng cộng: <?php echo number_format($totalPrice); ?>đ
                    </div>
                    <p style="text-align: center; font-size: 0.8rem; color: #999; margin-top: 5px;">
                        Ngày lưu: <?php echo date('d/m/Y H:i', strtotime($outfit['created_at'])); ?>
                    </p>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="suggestion-section">
            <div class="section-title" style="border-left-color: #e64a19; margin-bottom: 10px;">
                <span style="color: #e64a19;"><i class="fa-solid fa-fire"></i> Gợi ý phối đồ hôm nay dành cho bạn</span>
            </div>
            <p style="color: #666; font-size: 0.9rem; margin-bottom: 25px;">Hệ thống THE FOX đã tự động phối ngẫu nhiên các sản phẩm hot nhất để bạn tham khảo:</p>
            
            <div class="suggestion-grid">
                <?php foreach ($suggestedOutfits as $sug): 
                    $sTopImg = (strpos($sug['top']['image'], 'http') === 0) ? $sug['top']['image'] : "../uploads/products/" . $sug['top']['image'];
                    $sBottomImg = (strpos($sug['bottom']['image'], 'http') === 0) ? $sug['bottom']['image'] : "../uploads/products/" . $sug['bottom']['image'];
                    $sTotalPrice = $sug['top']['price'] + $sug['bottom']['price'];
                ?>
                <div class="outfit-card" style="border-color: #ffd8c4;">
                    <h3 style="color: #e64a19;"><?php echo $sug['name']; ?></h3>
                    <div class="outfit-preview-box" style="background: #fff;">
                        <img src="<?php echo $sTopImg; ?>" title="<?php echo htmlspecialchars($sug['top']['name']); ?>" alt="Áo">
                        <div class="item-separator"></div>
                        <img src="<?php echo $sBottomImg; ?>" title="<?php echo htmlspecialchars($sug['bottom']['name']); ?>" alt="Quần">
                    </div>
                    <div class="outfit-total-price" style="color: #111;">
                        Giá set: <?php echo number_format($sTotalPrice); ?>đ
                    </div>
                    <button class="btn-mix-again" onclick="redirectToBuilder(<?php echo $sug['top']['id']; ?>, <?php echo $sug['bottom']['id']; ?>)">
                        Thử phối lại bộ này
                    </button>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
        // Hàm hỗ trợ khách bấm một cái là tự động đưa ID áo và quần sang phòng thử đồ phối lại
        function redirectToBuilder(topId, bottomId) {
            window.location.href = `outfit-builder.php?preload_top=${topId}&preload_bottom=${bottomId}`;
        }

        // HÀM XỬ LÝ XÓA BỘ PHỐI ĐỒ BẰNG AJAX
        function deleteOutfit(outfitId) {
            if (confirm('Bạn có chắc chắn muốn xóa bộ phối đồ này khỏi tủ đồ không?')) {
                fetch('../controllers/OutfitController.php?action=delete_outfit', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ outfit_id: outfitId })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => { throw new Error(text) });
                    }
                    return response.json();
                })
                .then(data => {
                    alert(data.message);
                    // Reload lại trang để cập nhật danh sách tủ đồ một cách chính xác nhất
                    window.location.reload(); 
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Hệ thống xử lý lỗi hoặc hành động xóa chưa được cấu hình tại Controller.');
                });
            }
        }
    </script>
</body>
</html>
