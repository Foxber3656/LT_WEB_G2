<?php
/* ==========================================================================
   THE FOX - Route Định Tuyến Bộ Phối Đồ (Outfit Route API)
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
require_once __DIR__ . '/../controllers/OutfitController.php';

try {
    $databaseConnection = getDBConnection();
} catch (Exception $exception) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Lỗi kết nối CSDL: ' . $exception->getMessage()]);
    exit();
}

$routeAction = $_GET['action'] ?? '';
$outfitController = new OutfitController($databaseConnection);

// Chức năng lấy sản phẩm cho Outfit Builder cho phép khách vãng lai trải nghiệm thử mà không bắt buộc đăng nhập
if ($routeAction === 'get_builder_products') {
    try {
        $statement = $databaseConnection->query("SELECT id, name, price, image, category_id, description FROM products");
        $allProductsList = $statement->fetchAll(PDO::FETCH_ASSOC);

        // Hàm ánh xạ đường dẫn ảnh chính xác dựa theo TÊN sản phẩm để đảm bảo hiển thị hình ảnh đồng bộ
        function normalizeImagePathByName($productName, $originalImage) {
            $productNameLowercase = mb_strtolower($productName, 'UTF-8');

            if (strpos($productNameLowercase, 'sơ mi') !== false || strpos($productNameLowercase, 'oxford') !== false || strpos($productNameLowercase, 'thun nam') !== false || strpos($productNameLowercase, 'basic nam') !== false) {
                return '../assets/images/sp3.jpg';
            }
            if (strpos($productNameLowercase, 'croptop') !== false || strpos($productNameLowercase, 'áo kiểu') !== false) {
                return '../assets/images/sp1.jpg';
            }
            if (strpos($productNameLowercase, 'váy') !== false || strpos($productNameLowercase, 'đầm') !== false) {
                return '../assets/images/sp2.jpg';
            }
            if (strpos($productNameLowercase, 'quần') !== false || strpos($productNameLowercase, 'short') !== false || strpos($productNameLowercase, 'kaki') !== false || strpos($productNameLowercase, 'tây') !== false) {
                return '../assets/images/sp4.jpg';
            }
            if (strpos($productNameLowercase, 'kính') !== false) {
                return '../assets/images/sp1.jpg';
            }
            if (strpos($productNameLowercase, 'vòng') !== false) {
                return '../assets/images/sp2.jpg';
            }

            if (empty($originalImage)) return '../assets/images/sp1.jpg';
            if (strpos($originalImage, '../assets/') === 0) return $originalImage;
            if (strpos($originalImage, 'assets/') === 0) return '../' . $originalImage;
            if (strpos($originalImage, '/') === false) return '../assets/images/' . $originalImage;
            return $originalImage;
        }

        foreach ($allProductsList as &$singleProduct) {
            $singleProduct['image'] = normalizeImagePathByName($singleProduct['name'], $singleProduct['image']);
        }
        unset($singleProduct);

        $topsCategoryList = [];
        $bottomsCategoryList = [];
        $accessoriesCategoryList = [];

        foreach ($allProductsList as $singleProduct) {
            $productNameLowercase = mb_strtolower($singleProduct['name'], 'UTF-8');

            if ($singleProduct['category_id'] == 4 ||
                strpos($productNameLowercase, 'kính') !== false ||
                strpos($productNameLowercase, 'vòng') !== false ||
                strpos($productNameLowercase, 'dây chuyền') !== false ||
                strpos($productNameLowercase, 'nhẫn') !== false ||
                strpos($productNameLowercase, 'phụ kiện') !== false) {
                $accessoriesCategoryList[] = $singleProduct;
            }
            elseif (strpos($productNameLowercase, 'quần') !== false ||
                    strpos($productNameLowercase, 'short') !== false ||
                    strpos($productNameLowercase, 'jean') !== false ||
                    strpos($productNameLowercase, 'kaki') !== false ||
                    strpos($productNameLowercase, 'tây') !== false ||
                    strpos($productNameLowercase, 'váy') !== false) {
                $bottomsCategoryList[] = $singleProduct;
            }
            else {
                $topsCategoryList[] = $singleProduct;
            }
        }

        echo json_encode([
            'success' => true,
            'data' => [
                'tops'        => $topsCategoryList,
                'bottoms'     => $bottomsCategoryList,
                'accessories' => $accessoriesCategoryList
            ]
        ]);
    } catch (Exception $exception) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Lỗi lấy danh sách sản phẩm: ' . $exception->getMessage()]);
    }
    exit();
}

// Các Action thao tác lưu trữ cá nhân bắt buộc phải xác thực đăng nhập
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập để sử dụng chức năng phối đồ.']);
    exit();
}

$currentUserId = $_SESSION['user_id'];

switch ($routeAction) {
    case 'save_outfit':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $requestInput = json_decode(file_get_contents("php://input"), true);
            $apiResponse = $outfitController->saveOutfit($currentUserId, $requestInput);
            echo json_encode($apiResponse);
        } else {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ']);
        }
        break;

    case 'get_outfits':
        $apiResponse = $outfitController->getUserOutfits($currentUserId);
        echo json_encode($apiResponse);
        break;

    case 'get_outfit_details':
        $targetOutfitId = isset($_GET['outfit_id']) ? intval($_GET['outfit_id']) : 0;
        $apiResponse = $outfitController->getOutfitDetails($currentUserId, $targetOutfitId);
        echo json_encode($apiResponse);
        break;

    case 'delete_outfit':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $requestInput = json_decode(file_get_contents("php://input"), true);
            $targetOutfitId = isset($requestInput['outfit_id']) ? intval($requestInput['outfit_id']) : 0;
            $apiResponse = $outfitController->deleteOutfit($currentUserId, $targetOutfitId);
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
