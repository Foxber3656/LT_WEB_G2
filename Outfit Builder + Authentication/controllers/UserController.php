<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/database.php';
require_once '../models/User.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';
$userModel = new User($conn);

switch($action) {
    case 'register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $phone = trim($_POST['phone']);
            $password = $_POST['password'];

            $result = $userModel->register($name, $email, $phone, $password);
            if ($result === true) {
                $_SESSION['success_msg'] = "Đăng ký thành công! Hãy đăng nhập.";
                header("Location: ../pages/login.php");
            } elseif ($result === "email_exists") {
                $_SESSION['auth_error'] = "Email này đã được sử dụng!";
                header("Location: ../pages/register.php");
            } else {
                $_SESSION['auth_error'] = "Đăng ký thất bại, vui lòng thử lại.";
                header("Location: ../pages/register.php");
            }
            exit();
        }
        break;

    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $identity = trim($_POST['identity']);
            $password = $_POST['password'];

            $user = $userModel->login($identity, $password);
            if ($user) {
                // Khởi tạo phiên làm việc Session thống nhất toàn hệ thống
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'phone' => $user['phone'],
                    'avatar' => $user['avatar'],
                    'role' => $user['role']
                ];
                header("Location: ../pages/outfit-builder.php"); // Sau khi đăng nhập xong thì chuyển hướng thẳng qua phòng thử đồ luôn
            } else {
                $_SESSION['auth_error'] = "Tài khoản hoặc mật khẩu không đúng!";
                header("Location: ../pages/login.php");
            }
            exit();
        }
        break;

    case 'logout':
        unset($_SESSION['user']);
        session_destroy();
        header("Location: ../pages/login.php");
        exit();
        break;

    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user'])) {
            $id = $_SESSION['user']['id'];
            $name = trim($_POST['name']);
            $phone = trim($_POST['phone']);
            $avatar_name = $_SESSION['user']['avatar']; // Giữ avatar cũ mặc định

            // Xử lý upload ảnh đại diện mới nếu có
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
                $target_dir = "../uploads/avatars/";
                
                // Tạo thư mục tự động nếu chưa có
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }

                $file_extension = pathinfo($_FILES["avatar"]["name"], PATHINFO_EXTENSION);
                $avatar_name = "avatar_user_" . $id . "_" . time() . "." . $file_extension;
                $target_file = $target_dir . $avatar_name;
                
                move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file);
            }

            if ($userModel->updateProfile($id, $name, $phone, $avatar_name)) {
                // Cập nhật lại session mới sau khi lưu thành công
                $_SESSION['user']['name'] = $name;
                $_SESSION['user']['phone'] = $phone;
                $_SESSION['user']['avatar'] = $avatar_name;
                $_SESSION['success_msg'] = "Cập nhật thông tin thành công!";
            } else {
                $_SESSION['auth_error'] = "Cập nhật thất bại.";
            }
            header("Location: ../pages/profile.php");
            exit();
        }
        break;
}
?>
