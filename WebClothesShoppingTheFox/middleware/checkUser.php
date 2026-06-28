<?php
/* ==========================================================================
   THE FOX - Middleware Phân Quyền Khách Hàng (User Access Control)
   Áp dụng chuẩn thiết kế phần mềm Clean Code & Senior Developer
   Tên biến/hàm: Tiếng Anh chuẩn camelCase | Chú thích: Tiếng Việt chuyên nghiệp
   ========================================================================== */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra trạng thái xác thực: Yêu cầu người dùng đã đăng nhập Session
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

/** @var string $userRole Vai trò tài khoản người dùng */
$userRole = $_SESSION['role'] ?? 'user';
/** @var string $userFullName Họ và tên của thành viên */
$userFullName = $_SESSION['fullname'] ?? 'Thành viên';
/** @var string $userEmailAddress Địa chỉ Email thành viên */
$userEmailAddress = $_SESSION['email'] ?? '';

// Hỗ trợ tương thích biến alias cho giao diện View
$role = $userRole;
$fullname = $userFullName;
$email = $userEmailAddress;
?>
