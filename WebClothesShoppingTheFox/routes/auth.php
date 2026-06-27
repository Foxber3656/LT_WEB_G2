<?php
/* ==========================================================================
   THE FOX - Route Định Tuyến Xác Thực & Quản Lý Người Dùng (Auth Route API)
   Áp dụng chuẩn thiết kế phần mềm Clean Code & Senior Developer
   Tên biến/hàm: Tiếng Anh chuẩn | Chú thích (Comments): Tiếng Việt chuyên nghiệp
   ========================================================================== */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cấu hình các Header chuẩn RESTful API cho phép giao tiếp Cross-Origin (CORS) an toàn
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../controllers/UserController.php';

try {
    $databaseConnection = getDBConnection();
    
    // Tự động bảo trì Schema (Auto Migration) đảm bảo các cột cần thiết cho profile luôn tồn tại
    try { $databaseConnection->exec("ALTER TABLE users ADD COLUMN address TEXT NULL;"); } catch (Exception $exception) {}
    try { $databaseConnection->exec("ALTER TABLE users ADD COLUMN avatar VARCHAR(255) NULL;"); } catch (Exception $exception) {}

    // Tự động khởi tạo bảng phân quyền RBAC (Role-Based Access Control) nếu ứng dụng lần đầu khởi chạy
    $databaseConnection->exec("CREATE TABLE IF NOT EXISTS roles (
        name VARCHAR(50) PRIMARY KEY,
        description VARCHAR(255) NOT NULL,
        permissions TEXT NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    $statementCheck = $databaseConnection->query("SELECT COUNT(*) FROM roles");
    if ($statementCheck->fetchColumn() == 0) {
        $databaseConnection->exec("INSERT INTO roles (name, description, permissions) VALUES 
        ('admin', 'Quản trị viên toàn quyền hệ thống', '[\"manage_users\", \"manage_products\", \"manage_orders\", \"manage_categories\", \"view_dashboard\"]'),
        ('user', 'Khách hàng thành viên đã đăng ký', '[\"save_outfit\", \"manage_wishlist\", \"checkout\", \"view_orders\", \"edit_profile\"]'),
        ('guest', 'Khách vãng lai chưa đăng nhập', '[\"view_products\", \"add_to_cart\", \"checkout\"]')");
    }
} catch (Exception $exception) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Lỗi kết nối CSDL: ' . $exception->getMessage()]);
    exit();
}

$routeAction = $_GET['action'] ?? '';
$userController = new UserController($databaseConnection);

switch ($routeAction) {
    case 'register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $requestInput = json_decode(file_get_contents("php://input"), true);
            $apiResponse = $userController->register($requestInput);
            echo json_encode($apiResponse);
        } else {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ']);
        }
        break;

    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $requestInput = json_decode(file_get_contents("php://input"), true);
            $apiResponse = $userController->login($requestInput);
            echo json_encode($apiResponse);
        } else {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ']);
        }
        break;

    case 'logout':
        $apiResponse = $userController->logout();
        echo json_encode($apiResponse);
        break;

    case 'check':
        if (isset($_SESSION['user_id'])) {
            echo json_encode([
                'success' => true,
                'logged_in' => true,
                'user' => [
                    'id' => $_SESSION['user_id'],
                    'fullname' => $_SESSION['fullname'] ?? '',
                    'email' => $_SESSION['email'] ?? '',
                    'role' => $_SESSION['role'] ?? 'user'
                ]
            ]);
        } else {
            echo json_encode([
                'success' => true,
                'logged_in' => false
            ]);
        }
        break;

    case 'get_profile':
        if (isset($_SESSION['user_id'])) {
            $apiResponse = $userController->getProfile($_SESSION['user_id']);
            echo json_encode($apiResponse);
        } else {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
        }
        break;

    case 'update_profile':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_SESSION['user_id'])) {
                $requestInput = json_decode(file_get_contents("php://input"), true);
                $apiResponse = $userController->updateProfile($_SESSION['user_id'], $requestInput);
                echo json_encode($apiResponse);
            } else {
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
            }
        } else {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ']);
        }
        break;

    case 'change_password':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_SESSION['user_id'])) {
                $requestInput = json_decode(file_get_contents("php://input"), true);
                $apiResponse = $userController->changePassword($_SESSION['user_id'], $requestInput);
                echo json_encode($apiResponse);
            } else {
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
            }
        } else {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ']);
        }
        break;

    case 'upload_avatar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_SESSION['user_id'])) {
                $uploadedFile = $_FILES['avatar_file'] ?? null;
                $apiResponse = $userController->uploadAvatar($_SESSION['user_id'], $uploadedFile);
                echo json_encode($apiResponse);
            } else {
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
            }
        } else {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ']);
        }
        break;

    case 'verify_password':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_SESSION['user_id'])) {
                $requestInput = json_decode(file_get_contents("php://input"), true);
                $enteredPassword = $requestInput['password'] ?? '';
                
                $statement = $databaseConnection->prepare("SELECT password FROM users WHERE id = ?");
                $statement->execute([$_SESSION['user_id']]);
                $userRecord = $statement->fetch(PDO::FETCH_ASSOC);
                
                if ($userRecord && password_verify($enteredPassword, $userRecord['password'])) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Mật khẩu xác thực không chính xác.']);
                }
            } else {
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
            }
        } else {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ']);
        }
        break;

    case 'update_cccd':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_SESSION['user_id'])) {
                $requestInput = json_decode(file_get_contents("php://input"), true);
                $apiResponse = $userController->updateCccd($_SESSION['user_id'], $requestInput);
                echo json_encode($apiResponse);
            } else {
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
            }
        } else {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ']);
        }
        break;

    case 'delete_account':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_SESSION['user_id'])) {
                $apiResponse = $userController->deleteOwnAccount($_SESSION['user_id']);
                echo json_encode($apiResponse);
            } else {
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
            }
        } else {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ']);
        }
        break;

    // Các Endpoint dành riêng cho quyền Admin kiểm soát danh sách tài khoản
    case 'admin_list_users':
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            $apiResponse = $userController->listUsers();
            echo json_encode($apiResponse);
        } else {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Từ chối truy cập.']);
        }
        break;

    case 'admin_create_user':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                $requestInput = json_decode(file_get_contents("php://input"), true);
                $apiResponse = $userController->createUser($requestInput);
                echo json_encode($apiResponse);
            } else {
                http_response_code(403);
                echo json_encode(['success' => false, 'message' => 'Từ chối truy cập.']);
            }
        } else {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ']);
        }
        break;

    case 'admin_update_user':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                $requestInput = json_decode(file_get_contents("php://input"), true);
                $apiResponse = $userController->updateUserByAdmin($requestInput);
                echo json_encode($apiResponse);
            } else {
                http_response_code(403);
                echo json_encode(['success' => false, 'message' => 'Từ chối truy cập.']);
            }
        } else {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ']);
        }
        break;

    case 'admin_delete_user':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                $requestInput = json_decode(file_get_contents("php://input"), true);
                $targetUserId = $requestInput['id'] ?? 0;
                $apiResponse = $userController->deleteUser($targetUserId);
                echo json_encode($apiResponse);
            } else {
                http_response_code(403);
                echo json_encode(['success' => false, 'message' => 'Từ chối truy cập.']);
            }
        } else {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ']);
        }
        break;

    case 'admin_list_roles':
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            try {
                $statement = $databaseConnection->query("SELECT * FROM roles");
                $allRolesList = $statement->fetchAll(PDO::FETCH_ASSOC);
                foreach ($allRolesList as &$singleRole) {
                    $singleRole['permissions'] = json_decode($singleRole['permissions'], true) ?? [];
                }
                echo json_encode(['success' => true, 'data' => $allRolesList]);
            } catch (Exception $exception) {
                echo json_encode(['success' => false, 'message' => $exception->getMessage()]);
            }
        } else {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Từ chối truy cập.']);
        }
        break;

    case 'admin_update_role_permissions':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                $requestInput = json_decode(file_get_contents("php://input"), true);
                $targetRoleName = $requestInput['role'] ?? '';
                $permissionsArray = $requestInput['permissions'] ?? [];
                
                if (empty($targetRoleName)) {
                    echo json_encode(['success' => false, 'message' => 'Tên vai trò không hợp lệ.']);
                    break;
                }
                
                try {
                    $statement = $databaseConnection->prepare("UPDATE roles SET permissions = ? WHERE name = ?");
                    $statement->execute([json_encode($permissionsArray), $targetRoleName]);
                    echo json_encode(['success' => true, 'message' => 'Cập nhật phân quyền thành công!']);
                } catch (Exception $exception) {
                    echo json_encode(['success' => false, 'message' => $exception->getMessage()]);
                }
            } else {
                http_response_code(403);
                echo json_encode(['success' => false, 'message' => 'Từ chối truy cập.']);
            }
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
