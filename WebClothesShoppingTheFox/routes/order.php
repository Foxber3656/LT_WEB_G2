<?php
/* ==========================================================================
   THE FOX - Route Định Tuyến Đơn Hàng & Thanh Toán (Order Route API)
   Áp dụng chuẩn thiết kế phần mềm Clean Code & Senior Developer
   Tên biến/hàm: Tiếng Anh chuẩn | Chú thích (Comments): Tiếng Việt chuyên nghiệp
   ========================================================================== */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Bắt buộc xác thực tài khoản đăng nhập để ngăn chặn các truy vấn giả mạo vô danh
if (!isset($_SESSION['user_id'])) {
    header("Content-Type: application/json; charset=UTF-8");
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập.']);
    exit();
}

$currentUserId = (int)$_SESSION['user_id'];
$currentUserRole = $_SESSION['role'] ?? 'user';

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../controllers/OrderController.php';

try {
    $databaseConnection = getDBConnection();
} catch (Exception $exception) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Lỗi kết nối CSDL: ' . $exception->getMessage()]);
    exit();
}

$routeAction = $_GET['action'] ?? '';
$orderController = new OrderController($databaseConnection);

switch ($routeAction) {
    case 'checkout':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $requestInput = json_decode(file_get_contents("php://input"), true);
            $requestInput['user_id'] = $currentUserId;
            $apiResponse = $orderController->checkout($requestInput);
            echo json_encode($apiResponse);
        } else {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ']);
        }
        break;

    case 'get_orders':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Truyền ID và Vai trò (RBAC): Admin xem toàn bộ danh sách đơn hàng, User chỉ xem đơn hàng của chính mình
            $apiResponse = $orderController->getOrdersByUser($currentUserId, $currentUserRole);
            echo json_encode($apiResponse);
        } else {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ']);
        }
        break;

    case 'update_status':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $requestInput = json_decode(file_get_contents("php://input"), true);
            $apiResponse = $orderController->updateStatus($requestInput);
            echo json_encode($apiResponse);
        } else {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ']);
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'API Endpoint không tồn tại']);
        break;
}
?>
