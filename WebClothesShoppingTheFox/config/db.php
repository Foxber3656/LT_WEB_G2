<?php
// Cấu hình thông số kết nối Database MySQL
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); // Mặc định của XAMPP là trống
define('DB_NAME', 'web_clothes_shopping');

function getDBConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        return new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        // Trả về JSON thông báo lỗi nếu là API request
        if (strpos($_SERVER['REQUEST_URI'], '/api/') !== false) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi kết nối cơ sở dữ liệu: ' . $e->getMessage()
            ]);
            exit;
        } else {
            die("Không thể kết nối đến cơ sở dữ liệu. Vui lòng kiểm tra lại cấu hình hoặc chạy XAMPP MySQL. Chi tiết: " . $e->getMessage());
        }
    }
}
?>
