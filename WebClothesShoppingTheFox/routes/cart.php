<?php
/* ==========================================================================
   THE FOX - Route Định Tuyến Giỏ Hàng (Cart Route API)
   Áp dụng chuẩn thiết kế phần mềm Clean Code & Senior Developer
   Tên biến/hàm: Tiếng Anh chuẩn | Chú thích (Comments): Tiếng Việt chuyên nghiệp
   ========================================================================== */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Giả lập tài khoản thử nghiệm nếu người dùng chưa đăng nhập để giữ giỏ hàng hoạt động liên tục
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
}
$currentUserId = $_SESSION['user_id'];

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../controllers/CartController.php';

try {
    $databaseConnection = getDBConnection();
} catch (Exception $exception) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Lỗi kết nối CSDL: ' . $exception->getMessage()]);
    exit();
}

$routeAction = $_GET['action'] ?? '';
$cartController = new CartController($databaseConnection);

switch ($routeAction) {
    case 'get_cart':
        $apiResponse = $cartController->getCart($currentUserId);
        echo json_encode($apiResponse);
        break;

    case 'add_to_cart':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $requestInput = json_decode(file_get_contents("php://input"), true);
            $apiResponse = $cartController->addToCart($currentUserId, $requestInput);
            echo json_encode($apiResponse);
        } else {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ']);
        }
        break;

    case 'update_cart_qty':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $requestInput = json_decode(file_get_contents("php://input"), true);
            $apiResponse = $cartController->updateQuantity($currentUserId, $requestInput);
            echo json_encode($apiResponse);
        } else {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ']);
        }
        break;

    case 'remove_cart_item':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $requestInput = json_decode(file_get_contents("php://input"), true);
            $apiResponse = $cartController->removeItem($currentUserId, $requestInput);
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
