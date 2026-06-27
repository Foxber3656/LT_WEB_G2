<?php
/* ==========================================================================
   THE FOX - Route Định Tuyến Danh Sách Yêu Thích (Wishlist Route API)
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

// Bắt buộc xác thực tài khoản đăng nhập để quản lý danh sách yêu thích cá nhân
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập để sử dụng chức năng yêu thích.']);
    exit();
}

$currentUserId = $_SESSION['user_id'];

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../controllers/WishlistController.php';

try {
    $databaseConnection = getDBConnection();
} catch (Exception $exception) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Lỗi kết nối CSDL: ' . $exception->getMessage()]);
    exit();
}

$routeAction = $_GET['action'] ?? '';
$wishlistController = new WishlistController($databaseConnection);

switch ($routeAction) {
    case 'get_wishlist':
        $apiResponse = $wishlistController->getWishlist($currentUserId);
        echo json_encode($apiResponse);
        break;

    case 'add_to_wishlist':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $requestInput = json_decode(file_get_contents("php://input"), true);
            $apiResponse = $wishlistController->addToWishlist($currentUserId, $requestInput);
            echo json_encode($apiResponse);
        } else {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ']);
        }
        break;

    case 'remove_from_wishlist':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $requestInput = json_decode(file_get_contents("php://input"), true);
            $apiResponse = $wishlistController->removeFromWishlist($currentUserId, $requestInput);
            echo json_encode($apiResponse);
        } else {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ']);
        }
        break;

    case 'toggle_wishlist':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $requestInput = json_decode(file_get_contents("php://input"), true);
            $apiResponse = $wishlistController->toggleWishlist($currentUserId, $requestInput);
            echo json_encode($apiResponse);
        } else {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ']);
        }
        break;

    case 'check_status':
        $targetProductId = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;
        $apiResponse = $wishlistController->checkStatus($currentUserId, $targetProductId);
        echo json_encode($apiResponse);
        break;

    case 'toggle_by_name':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $requestInput = json_decode(file_get_contents("php://input"), true);
            $targetProductName = $requestInput['product_name'] ?? '';
            $apiResponse = $wishlistController->toggleWishlistByName($currentUserId, $targetProductName);
            echo json_encode($apiResponse);
        } else {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ']);
        }
        break;

    case 'check_status_by_name':
        $targetProductName = $_GET['product_name'] ?? '';
        $apiResponse = $wishlistController->checkStatusByName($currentUserId, $targetProductName);
        echo json_encode($apiResponse);
        break;

    default:
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'API Endpoint không tồn tại']);
        break;
}
?>
