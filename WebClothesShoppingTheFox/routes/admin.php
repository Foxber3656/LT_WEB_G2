<?php
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

// Security Check: Only Admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Từ chối truy cập. Bạn không có quyền Admin.']);
    exit();
}

require_once __DIR__ . '/../config/db.php';

try {
    $pdo = getDBConnection();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Lỗi kết nối CSDL: ' . $e->getMessage()]);
    exit();
}

$action = $_GET['action'] ?? '';

switch ($action) {
    // 1. DASHBOARD STATISTICS
    case 'get_stats':
        try {
            // Total users
            $stmtUsers = $pdo->query("SELECT COUNT(*) FROM users");
            $totalUsers = $stmtUsers->fetchColumn();

            // Total products
            $stmtProducts = $pdo->query("SELECT COUNT(*) FROM products");
            $totalProducts = $stmtProducts->fetchColumn();

            // Total orders & revenue
            $stmtOrders = $pdo->query("SELECT COUNT(*) as total_orders, COALESCE(SUM(final_total), 0) as total_revenue FROM orders");
            $orderStats = $stmtOrders->fetch(PDO::FETCH_ASSOC);

            // Recent 5 orders
            $stmtRecent = $pdo->query("SELECT id, order_code, fullname, final_total, status, created_at FROM orders ORDER BY id DESC LIMIT 5");
            $recentOrders = $stmtRecent->fetchAll(PDO::FETCH_ASSOC);

            // Total categories
            $stmtCat = $pdo->query("SELECT COUNT(*) FROM categories");
            $totalCategories = $stmtCat->fetchColumn();

            // Pending orders count
            $stmtPending = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'Chờ xác nhận'");
            $pendingOrders = $stmtPending->fetchColumn();

            echo json_encode([
                'success' => true,
                'data' => [
                    'total_users' => $totalUsers,
                    'total_products' => $totalProducts,
                    'total_orders' => $orderStats['total_orders'],
                    'total_revenue' => floatval($orderStats['total_revenue']),
                    'total_categories' => $totalCategories,
                    'pending_orders' => $pendingOrders,
                    'recent_orders' => $recentOrders
                ]
            ]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    // 2. ORDER MANAGEMENT
    case 'list_orders':
        try {
            $stmt = $pdo->query("SELECT * FROM orders ORDER BY id DESC");
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'data' => $orders]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    case 'update_order_status':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents("php://input"), true);
            $orderId = intval($input['id'] ?? 0);
            $status = trim($input['status'] ?? '');

            if (!$orderId || empty($status)) {
                echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ.']);
                break;
            }

            try {
                $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
                $stmt->execute([$status, $orderId]);
                echo json_encode(['success' => true, 'message' => 'Cập nhật trạng thái đơn hàng thành công!']);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        }
        break;

    // 3. PRODUCT CRUD
    case 'list_products':
        try {
            $stmt = $pdo->query("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC");
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'data' => $products]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    case 'save_product':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents("php://input"), true);
            $id = intval($input['id'] ?? 0);
            $name = trim($input['name'] ?? '');
            $price = floatval($input['price'] ?? 0);
            $image = trim($input['image'] ?? '../assets/images/sp1.jpg');
            $categoryId = intval($input['category_id'] ?? 0);
            $description = trim($input['description'] ?? '');

            if (empty($name) || $price <= 0) {
                echo json_encode(['success' => false, 'message' => 'Vui lòng nhập tên sản phẩm và giá hợp lệ.']);
                break;
            }

            try {
                if ($id > 0) {
                    $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, image = ?, category_id = ?, description = ? WHERE id = ?");
                    $stmt->execute([$name, $price, $image, $categoryId > 0 ? $categoryId : null, $description, $id]);
                    echo json_encode(['success' => true, 'message' => 'Cập nhật sản phẩm thành công!']);
                } else {
                    $stmt = $pdo->prepare("INSERT INTO products (name, price, image, category_id, description) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$name, $price, $image, $categoryId > 0 ? $categoryId : null, $description]);
                    echo json_encode(['success' => true, 'message' => 'Thêm sản phẩm mới thành công!']);
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        }
        break;

    case 'delete_product':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents("php://input"), true);
            $id = intval($input['id'] ?? 0);
            try {
                $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
                $stmt->execute([$id]);
                echo json_encode(['success' => true, 'message' => 'Đã xóa sản phẩm!']);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        }
        break;

    // 4. CATEGORY CRUD
    case 'list_categories':
        try {
            $stmt = $pdo->query("SELECT c.*, COUNT(p.id) as total_products FROM categories c LEFT JOIN products p ON c.id = p.category_id GROUP BY c.id ORDER BY c.id ASC");
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'data' => $categories]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    case 'save_category':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents("php://input"), true);
            $id = intval($input['id'] ?? 0);
            $name = trim($input['name'] ?? '');

            if (empty($name)) {
                echo json_encode(['success' => false, 'message' => 'Vui lòng nhập tên danh mục.']);
                break;
            }

            try {
                if ($id > 0) {
                    $stmt = $pdo->prepare("UPDATE categories SET name = ? WHERE id = ?");
                    $stmt->execute([$name, $id]);
                    echo json_encode(['success' => true, 'message' => 'Cập nhật danh mục thành công!']);
                } else {
                    $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
                    $stmt->execute([$name]);
                    echo json_encode(['success' => true, 'message' => 'Thêm danh mục mới thành công!']);
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        }
        break;

    case 'delete_category':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents("php://input"), true);
            $id = intval($input['id'] ?? 0);
            try {
                $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
                $stmt->execute([$id]);
                echo json_encode(['success' => true, 'message' => 'Đã xóa danh mục!']);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Endpoint không tồn tại.']);
        break;
}
?>
