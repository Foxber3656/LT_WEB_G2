<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
// Bảo vệ trang: Chưa đăng nhập bắt buộc chuyển hướng về trang Login
if (!isset($_SESSION['user'])) { header("Location: login.php"); exit(); }
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tài Khoản Của Tôi - THE FOX</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background-color: #f8f9fa; color: #111; }
        .container { max-width: 1000px; margin: 40px auto; padding: 0 20px; }
        .profile-card { background: #fff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); overflow: hidden; display: flex; }
        .profile-sidebar { flex: 1; background: #2D3748; color: #fff; padding: 40px 20px; text-align: center; display: flex; flex-direction: column; align-items: center; }
        .avatar-box { position: relative; width: 130px; height: 130px; margin-bottom: 20px; }
        .avatar-box img { width: 100%; height: 100%; border-radius: 50%; object-fit: cover; border: 3px solid #ff5722; }
        .avatar-upload-btn { position: absolute; bottom: 0; right: 0; background: #ff5722; color: #fff; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 2px solid #2D3748; }
        .profile-sidebar h4 { font-size: 1.2rem; margin-bottom: 5px; font-weight: 600; }
        .profile-sidebar p { font-size: 0.85rem; color: #a0aec0; }
        .profile-main { flex: 2.5; padding: 40px; }
        .profile-main h3 { font-size: 1.5rem; margin-bottom: 25px; font-weight: 700; border-bottom: 2px solid #f5f5f5; padding-bottom: 10px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
        .form-group { display: flex; flex-direction: column; }
        .form-group label { font-size: 0.85rem; font-weight: 600; margin-bottom: 8px; color: #4a5568; }
        .form-group input { padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.95rem; outline: none; transition: 0.3s; background-color: #fff; }
        .form-group input:focus { border-color: #ff5722; }
        .form-group input[disabled] { background-color: #f7fafc; color: #a0aec0; cursor: not-allowed; }
        .btn-save { padding: 12px 30px; background: #ff5722; color: #fff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.3s; width: fit-content; }
        .btn-save:hover { background: #e64a19; }
        .alert-success { color: #2e7d32; background: #edf7ed; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 0.9rem; border-left: 4px solid #2e7d32; }
        .btn-logout { margin-top: auto; color: #fc8181; text-decoration: none; font-size: 0.9rem; font-weight: 600; display: flex; align-items: center; gap: 8px; }
        .btn-logout:hover { color: #e53e3e; }
    </style>
</head>
<body>

    <div class="container">
        <form action="../controllers/UserController.php?action=update" method="POST" enctype="multipart/form-data">
            <div class="profile-card">
                
                <div class="profile-sidebar">
                    <div class="avatar-box">
                        <?php 
                            $avatar_path = (file_exists("../uploads/avatars/" . $user['avatar']) && $user['avatar'] != 'default-avatar.png') 
                                           ? "../uploads/avatars/" . $user['avatar'] 
                                           : "../assets/images/icons/default-avatar.png";
                        ?>
                        <img src="<?php echo $avatar_path; ?>" alt="Avatar">
                        <label for="avatar-input" class="avatar-upload-btn">
                            <i class="fa-solid fa-camera"></i>
                        </label>
                        <input type="file" id="avatar-input" name="avatar" style="display:none;" accept="image/*">
                    </div>
                    <h4><?php echo htmlspecialchars($user['name']); ?></h4>
                    <p><?php echo ucfirst($user['role']); ?></p>

                    <a href="../controllers/UserController.php?action=logout" class="btn-logout">
                        <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
                    </a>
                </div>

                <div class="profile-main">
                    <h3>Thông tin tài khoản</h3>

                    <?php if (isset($_SESSION['success_msg'])): ?>
                        <div class="alert-success">
                            <?php echo $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?>
                        </div>
                    <?php endif; ?>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Họ và tên</label>
                            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Số điện thoại</label>
                            <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Địa chỉ Email (Không thể thay đổi)</label>
                            <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                        </div>
                    </div>

                    <button type="submit" class="btn-save">Lưu thay đổi</button>
                </div>

            </div>
        </form>
    </div>

</body>
</html>
