<?php
// controllers/OutfitController.php
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}

// Bật hiển thị mọi lỗi ẩn để debug nếu có
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// 1. Kiểm tra session đăng nhập (Khớp với cấu trúc hệ thống của bạn)
if (!isset($_SESSION['user']) && !isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Bạn cần đăng nhập trước khi thực hiện chức năng này!']);
    exit();
}

// Lấy ID người dùng từ session tương ứng
$user_id = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : ($_SESSION['user_id'] ?? null);

try {
    // 2. Nạp file cấu hình và cơ sở dữ liệu
    // Sử dụng dirname(__FILE__) để đảm bảo luôn tìm thấy file cấu hình bất kể chạy từ đâu
    $config_path = dirname(__FILE__) . '/../config/database.php';
    if (!file_exists($config_path)) {
        throw new Exception("Không tìm thấy file cấu hình database.php tại: " . $config_path);
    }
    require_once $config_path;

    $model_path = dirname(__FILE__) . '/../models/Product.php';
    if (!file_exists($model_path)) {
        throw new Exception("Không tìm thấy file model Product.php tại: " . $model_path);
    }
    require_once $model_path;

    if (!isset($conn)) {
        throw new Exception("Biến kết nối \$conn chưa được khởi tạo.");
    }

    $action = isset($_GET['action']) ? $_GET['action'] : '';
    $productModel = new Product($conn);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Đọc dữ liệu JSON gửi từ JavaScript
        $input = json_decode(file_get_contents('php://input'), true);

        // --- 1. HÀNH ĐỘNG: LƯU BỘ PHỐI ĐỒ ---
        if ($action === 'save_outfit') {
            $name = !empty($input['outfit_name']) ? trim($input['outfit_name']) : 'Bộ phối của tôi';
            $top_id = $input['top_id'] ?? null;
            $bottom_id = $input['bottom_id'] ?? null;
            $accessory_id = $input['accessory_id'] ?? null;

            if (!$top_id || !$bottom_id) {
                echo json_encode(['status' => 'error', 'message' => 'Vui lòng chọn đủ áo và quần!']);
                exit();
            }

            $result = $productModel->saveOutfit($user_id, $name, $top_id, $bottom_id, $accessory_id);
            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'Đã lưu bộ đồ vào tủ đồ của bạn thành công!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Không thể ghi dữ liệu bộ phối vào database.']);
            }
            exit();
        }

        // --- 2. HÀNH ĐỘNG: THÊM YÊU THÍCH ---
        if ($action === 'add_wishlist') {
            $product_id = $input['product_id'] ?? null;
            if (!$product_id) {
                echo json_encode(['status' => 'error', 'message' => 'Sản phẩm không hợp lệ.']);
                exit();
            }

            $result = $productModel->addToWishlist($user_id, $product_id);
            if ($result === "exists") {
                echo json_encode(['status' => 'info', 'message' => 'Sản phẩm này đã có trong danh sách yêu thích!']);
            } elseif ($result) {
                echo json_encode(['status' => 'success', 'message' => 'Đã thêm vào danh sách yêu thích!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Lỗi xử lý yêu thích hệ thống.']);
            }
            exit();
        }

        // --- 3. ĐÃ BỔ SUNG HÀNH ĐỘNG: XÓA BỘ PHỐI ĐỒ ---
        if ($action === 'delete_outfit') {
            $outfit_id = isset($input['outfit_id']) ? intval($input['outfit_id']) : 0;

            if ($outfit_id > 0) {
                // Sử dụng câu lệnh SQL chuẩn hóa kết nối PDO theo cấu trúc hệ thống của bạn
                $sql = "DELETE FROM outfits WHERE id = ? AND user_id = ?";
                $stmt = $conn->prepare($sql);
                $success = $stmt->execute([$outfit_id, $user_id]);

                if ($success) {
                    echo json_encode(['success' => true, 'status' => 'success', 'message' => 'Đã xóa bộ phối đồ thành công!']);
                } else {
                    echo json_encode(['success' => false, 'status' => 'error', 'message' => 'Không thể xóa bộ phối đồ vào lúc này.']);
                }
            } else {
                echo json_encode(['success' => false, 'status' => 'error', 'message' => 'Dữ liệu bộ phối không hợp lệ.']);
            }
            exit();
        }
    }
    
    echo json_encode(['status' => 'error', 'message' => 'Hành động không hợp lệ hoặc phương thức không đúng.']);

} catch (Exception $e) {
    // Trả về thông báo lỗi chi tiết thay vì im lặng báo lỗi hệ thống chung chung
    echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
}
?>
