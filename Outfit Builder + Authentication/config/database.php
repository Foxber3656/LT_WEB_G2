<?php
// Kết nối Database bằng PDO gọn gàng, an toàn
$host = "localhost";
$db_name = "fashionshop";
$username = "root";
$password = ""; // Mặc định của XAMPP là rỗng
$conn = null;

try {
    $conn = new PDO("mysql:host=" . $host . ";dbname=" . $db_name . ";charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $exception) {
    echo "Lỗi kết nối cơ sở dữ liệu: " . $exception->getMessage();
    exit();
}
?>
