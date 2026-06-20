<?php
// Quản lý Session & phân quyền người dùng
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Hàm kiểm tra người dùng đã đăng nhập chưa
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Hàm kiểm tra quyền Admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Hàm yêu cầu đăng nhập
function requireLogin() {
    if (!isLoggedIn()) {
        $redir = defined('BASE_URL') ? BASE_URL . 'pages/login.php' : '../pages/login.php';
        header('Location: ' . $redir);
        exit;
    }
}

// Hàm yêu cầu quyền Admin
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        $redir = defined('BASE_URL') ? BASE_URL . 'index.php' : '../index.php';
        header('Location: ' . $redir);
        exit;
    }
}
?>
