<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Nếu đã đăng nhập thì redirect về trang tương ứng
if (isset($_SESSION['user_id'])) {
    if (($_SESSION['role'] ?? '') === 'admin') {
        header("Location: admin.php");
    } else {
        header("Location: profile.php");
    }
    exit();
}
