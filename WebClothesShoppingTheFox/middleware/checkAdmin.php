<?php
/* ==========================================================================
   THE FOX - Middleware Phân Quyền Quản Trị Viên (Admin Access Control)
   Áp dụng chuẩn thiết kế phần mềm Clean Code & Senior Developer
   Tên biến/hàm: Tiếng Anh chuẩn camelCase | Chú thích: Tiếng Việt chuyên nghiệp
   ========================================================================== */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra quyền RBAC: Chỉ tài khoản có vai trò 'admin' mới được phép truy cập
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: login.php");
    exit();
}

/** @var string $adminFullName Tên hiển thị của Admin quản trị viên */
$adminFullName = $_SESSION['fullname'] ?? 'Admin Manager';
$adminEmailAddress = $_SESSION['email'] ?? '';

// Hỗ trợ tương thích biến alias cho giao diện View
$fullname = $adminFullName;
$email = $adminEmailAddress;
?>
