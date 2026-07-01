<?php
// config/config.php

// Định nghĩa URL gốc của dự án
define('BASE_URL', 'http://localhost/WebClothesShoppingTheFox/');

// Kiểm tra nếu session đã lỡ chạy trước, tạm thời đóng lại để thiết lập cấu hình cookie
if (session_status() === PHP_SESSION_ACTIVE) {
    session_write_close();
}

// Cấu hình thời gian sống của session cookie (1 ngày)
ini_set('session.cookie_lifetime', 86400); 

// Khởi động lại session sau khi cấu hình hoàn tất
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
