<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // Mặc định gán user_id = 1 để chạy thử
}
$userId = $_SESSION['user_id'];

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
    $pdo = getDBConnection();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Lỗi kết nối CSDL: ' . $e->getMessage()]);
    exit();
}

$action = $_GET['action'] ?? '';
$cartController = new CartController($pdo);

switch ($action) {
    case 'get_cart':
        $response = $cartController->getCart($userId);
        echo json_encode($response);
        break;

    case 'add_to_cart':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents("php://input"), true);
            $response = $cartController->addToCart($userId, $input);
            echo json_encode($response);
        } else {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ']);
        }
        break;

    case 'update_cart_qty':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents("php://input"), true);
            $response = $cartController->updateQuantity($userId, $input);
            echo json_encode($response);
        } else {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ']);
        }
        break;

    case 'remove_cart_item':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents("php://input"), true);
            $response = $cartController->removeItem($userId, $input);
            echo json_encode($response);
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
