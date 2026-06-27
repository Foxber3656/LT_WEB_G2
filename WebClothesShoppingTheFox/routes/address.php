<?php
/* ==========================================================================
   THE FOX - Route Định Tuyến Địa Chỉ Giao Hàng (Address Route API)
   Áp dụng chuẩn thiết kế phần mềm Clean Code & Senior Developer
   Tên biến/hàm: Tiếng Anh chuẩn | Chú thích (Comments): Tiếng Việt chuyên nghiệp
   ========================================================================== */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../config/db.php';

try {
    $databaseConnection = getDBConnection();
    
    // Tự động đảm bảo tạo bảng user_addresses nếu chưa tồn tại (Thực thể con liên kết khóa ngoại với users.id)
    $databaseConnection->exec("CREATE TABLE IF NOT EXISTS user_addresses (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        recipient_name VARCHAR(100) NOT NULL,
        recipient_phone VARCHAR(20) NOT NULL,
        city VARCHAR(100) NOT NULL,
        district VARCHAR(100) NOT NULL,
        ward VARCHAR(100) NOT NULL,
        street_address TEXT NOT NULL,
        address_type VARCHAR(20) DEFAULT 'home',
        is_default TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

} catch (Exception $exception) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Lỗi kết nối CSDL: ' . $exception->getMessage()]);
    exit();
}

$routeAction = $_GET['action'] ?? '';
$currentUserId = $_SESSION['user_id'] ?? 0;

// API 1: Lấy danh sách toàn bộ địa chỉ của người dùng
if ($routeAction === 'get_addresses') {
    if (!$currentUserId) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập.', 'data' => []]);
        exit();
    }

    try {
        $statementFetch = $databaseConnection->prepare("SELECT * FROM user_addresses WHERE user_id = ? ORDER BY is_default DESC, id DESC");
        $statementFetch->execute([$currentUserId]);
        $addressList = $statementFetch->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'data' => $addressList]);
    } catch (Exception $exception) {
        echo json_encode(['success' => false, 'message' => 'Lỗi đọc dữ liệu địa chỉ: ' . $exception->getMessage(), 'data' => []]);
    }
    exit();
}

// API 2: Thêm địa chỉ mới
if ($routeAction === 'add_address') {
    $requestInput = json_decode(file_get_contents("php://input"), true);
    
    $targetUserId = $currentUserId ? $currentUserId : (isset($requestInput['user_id']) ? intval($requestInput['user_id']) : 0);
    $recipientName = isset($requestInput['recipient_name']) ? trim($requestInput['recipient_name']) : '';
    $recipientPhone = isset($requestInput['recipient_phone']) ? trim($requestInput['recipient_phone']) : '';
    $city = isset($requestInput['city']) ? trim($requestInput['city']) : '';
    $district = isset($requestInput['district']) ? trim($requestInput['district']) : '';
    $ward = isset($requestInput['ward']) ? trim($requestInput['ward']) : '';
    $streetAddress = isset($requestInput['street_address']) ? trim($requestInput['street_address']) : '';
    $addressType = isset($requestInput['address_type']) ? trim($requestInput['address_type']) : 'home';
    $isDefaultAddress = isset($requestInput['is_default']) ? 1 : 0;

    if (empty($recipientName) || empty($recipientPhone) || empty($streetAddress)) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ các thông tin bắt buộc.']);
        exit();
    }

    if (!$targetUserId) {
        echo json_encode(['success' => false, 'message' => 'Không xác định được ID người dùng.']);
        exit();
    }

    try {
        // Kiểm tra xem đây có phải địa chỉ đầu tiên không, nếu là đầu tiên thì tự động làm mặc định
        $statementCount = $databaseConnection->prepare("SELECT COUNT(*) FROM user_addresses WHERE user_id = ?");
        $statementCount->execute([$targetUserId]);
        $existingCount = $statementCount->fetchColumn();
        if ($existingCount == 0) {
            $isDefaultAddress = 1;
        }

        // Nếu đặt làm địa chỉ mặc định, tự động hủy cờ mặc định của các địa chỉ khác
        if ($isDefaultAddress) {
            $statementResetDefault = $databaseConnection->prepare("UPDATE user_addresses SET is_default = 0 WHERE user_id = ?");
            $statementResetDefault->execute([$targetUserId]);
        }

        $statementInsertAddress = $databaseConnection->prepare("INSERT INTO user_addresses (user_id, recipient_name, recipient_phone, city, district, ward, street_address, address_type, is_default) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $statementInsertAddress->execute([$targetUserId, $recipientName, $recipientPhone, $city, $district, $ward, $streetAddress, $addressType, $isDefaultAddress]);

        // Cập nhật địa chỉ đầy đủ vào bảng chính `users.address` nếu đây là mặc định
        if ($isDefaultAddress) {
            $fullFormattedString = "$streetAddress, $ward, $district, $city";
            $statementUpdateUser = $databaseConnection->prepare("UPDATE users SET address = ? WHERE id = ?");
            $statementUpdateUser->execute([$fullFormattedString, $targetUserId]);
        }

        echo json_encode(['success' => true, 'message' => 'Thêm địa chỉ giao hàng thành công!']);
    } catch (Exception $exception) {
        echo json_encode(['success' => false, 'message' => 'Lỗi lưu dữ liệu: ' . $exception->getMessage()]);
    }
    exit();
}

// API 3: Xóa địa chỉ
if ($routeAction === 'delete_address') {
    $requestInput = json_decode(file_get_contents("php://input"), true);
    $addressId = isset($requestInput['address_id']) ? intval($requestInput['address_id']) : 0;

    if (!$addressId || !$currentUserId) {
        echo json_encode(['success' => false, 'message' => 'Tham số không hợp lệ.']);
        exit();
    }

    try {
        $statementDelete = $databaseConnection->prepare("DELETE FROM user_addresses WHERE id = ? AND user_id = ?");
        $statementDelete->execute([$addressId, $currentUserId]);
        echo json_encode(['success' => true, 'message' => 'Xóa địa chỉ thành công!']);
    } catch (Exception $exception) {
        echo json_encode(['success' => false, 'message' => 'Lỗi khi xóa địa chỉ: ' . $exception->getMessage()]);
    }
    exit();
}

// API 4: Đặt làm mặc định
if ($routeAction === 'set_default') {
    $requestInput = json_decode(file_get_contents("php://input"), true);
    $addressId = isset($requestInput['address_id']) ? intval($requestInput['address_id']) : 0;

    if (!$addressId || !$currentUserId) {
        echo json_encode(['success' => false, 'message' => 'Tham số không hợp lệ.']);
        exit();
    }

    try {
        $statementReset = $databaseConnection->prepare("UPDATE user_addresses SET is_default = 0 WHERE user_id = ?");
        $statementReset->execute([$currentUserId]);

        $statementSet = $databaseConnection->prepare("UPDATE user_addresses SET is_default = 1 WHERE id = ? AND user_id = ?");
        $statementSet->execute([$addressId, $currentUserId]);

        // Cập nhật bảng users
        $statementGet = $databaseConnection->prepare("SELECT * FROM user_addresses WHERE id = ?");
        $statementGet->execute([$addressId]);
        $addr = $statementGet->fetch(PDO::FETCH_ASSOC);
        if ($addr) {
            $fullStr = "{$addr['street_address']}, {$addr['ward']}, {$addr['district']}, {$addr['city']}";
            $statementUpdateUser = $databaseConnection->prepare("UPDATE users SET address = ? WHERE id = ?");
            $statementUpdateUser->execute([$fullStr, $currentUserId]);
        }

        echo json_encode(['success' => true, 'message' => 'Đã thiết lập địa chỉ mặc định!']);
    } catch (Exception $exception) {
        echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $exception->getMessage()]);
    }
    exit();
}

http_response_code(404);
echo json_encode(['success' => false, 'message' => 'API Endpoint không tồn tại']);
?>
