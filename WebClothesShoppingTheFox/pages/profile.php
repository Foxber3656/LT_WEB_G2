<?php 
require_once '../middleware/checkUser.php'; 
/**
 * Khởi tạo định danh biến toàn cục cho trang User Profile View
 * @var string $userFullName Họ tên người dùng đăng nhập
 * @var string $userRole Vai trò tài khoản người dùng
 */
$userFullName = $userFullName ?? ($fullname ?? 'Thành viên');
$userRole = $userRole ?? ($role ?? 'user');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../assets/images/fashion.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- CSS -->
    <link rel="stylesheet" href="../assets/css/gobal.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <link rel="stylesheet" href="../assets/css/accountSidebar.css">
    <link rel="stylesheet" href="../assets/css/userProfileStrongEntity.css">
    <link rel="stylesheet" href="../assets/css/addressWeakEntity.css">
    <link rel="stylesheet" href="../assets/css/auth.css">
    <link rel="stylesheet" href="../assets/css/order.css">
    <link rel="stylesheet" href="../assets/css/wishlist.css">

    <title>Tài khoản của tôi - The Fox</title>
</head>
<body>

<!-- HEADER -->
<header id="header">
    <div class="container">
        <!-- LOGO -->
        <div class="header-logo">
            <a href="home.php">
                <img src="../assets/images/icon.png" alt="The Fox Logo" class="logo">
            </a>
        </div>
        <!-- NAVBAR -->
        <nav class="navbar">
            <ul class="menu">
                <li class="menu-items"><a href="cartegory.php?cat=nu">NỮ</a></li>
                <li class="menu-items"><a href="cartegory.php?cat=nam">NAM</a></li>
                <li class="menu-items"><a href="cartegory.php?cat=tre-em">TRẺ EM</a></li>
                <li class="menu-items"><a href="cartegory.php?cat=phu-kien">PHỤ KIỆN</a></li>
                <li class="menu-items"><a href="cartegory.php?cat=bo-suu-tap">BỘ SƯU TẬP</a></li>
                <li class="sale-menu"><a href="cartegory.php?cat=sale">SALE</a></li>
                <li class="menu-items"><a href="cartegory.php?cat=thuong-hieu">THƯƠNG HIỆU</a></li>
            </ul>
        </nav>
        <!-- HEADER ACTION -->
        <div class="header-action">
            <div class="search-box">
                <input type="text" placeholder="Tìm kiếm">
                <i class="fas fa-search"></i>
            </div>
            <a class="fa fa-headphones" href="mailto:info@thefox.com"></a>
            <a class="fa fa-user" href="profile.php"></a>
            <a class="fa fa-shopping-bag cart-icon-btn" href="javascript:void(0)"></a>
        </div>
    </div>
</header>

<!-- MAIN CONTENT -->
<main class="site-main account">
    <div class="container">
        <div class="account-wrapper">
            
            <!-- SIDEBAR -->
            <div class="account-sidebar">
                <input type="file" id="profAvatarFile" accept="image/*" style="display: none;" onchange="uploadSelectedAvatar(this)">
                <div class="account-user" style="flex-direction: column; text-align: center; gap: 6px; padding: 25px 15px; border-bottom: 1px solid rgba(191, 138, 73, 0.12);">
                    <div style="position: relative; width: 80px; height: 80px; border-radius: 50%; margin: 0 auto; border: 3px solid #BF8A49; background: #f0f0f0; cursor: pointer;" id="sidebarAvatarBox" onclick="document.getElementById('profAvatarFile').click()" title="Bấm để đổi ảnh đại diện">
                        <div style="width: 100%; height: 100%; border-radius: 50%; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                            <i class="fa-solid fa-circle-user" style="font-size: 80px; color: #ccc;" id="sidebarAvatarIcon"></i>
                            <img src="" id="sidebarAvatarImg" style="width: 100%; height: 100%; object-fit: cover; display: none;" alt="Avatar">
                        </div>
                        <div style="position: absolute; bottom: 0; right: 0; background: #BF8A49; color: #fff; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 11px; border: 2px solid #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">
                            <i class="fas fa-camera"></i>
                        </div>
                    </div>
                    <span id="sidebarFullname" style="font-weight: 700; font-size: 17px; color: #221f20; margin-top: 4px;">Thành viên</span>
                    <a href="javascript:void(0)" class="profile-tab-btn" data-tab="tab-profile" style="font-size: 13px; color: #BF8A49; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; font-weight: 600; padding: 0; border: none;">
                        <i class="fas fa-pen" style="font-size: 11px;"></i> Sửa hồ sơ
                    </a>
                </div>
                
                <div style="padding: 10px 0;">
                    <!-- MỤC LỚN 1: TÀI KHOẢN CỦA TÔI -->
                    <div class="sidebar-group-header" onclick="toggleSidebarGroup(this)" style="display: flex; align-items: center; justify-content: space-between; cursor: pointer; user-select: none;">
                        <span style="display: flex; align-items: center; gap: 10px;">
                            <i class="fa-regular fa-user" style="color: #2b6cb0; font-size: 19px; width: 22px; text-align: center;"></i> 
                            Tài Khoản Của Tôi
                        </span>
                        <i class="fas fa-chevron-down group-toggle-icon" style="font-size: 11px; color: #888; transition: transform 0.3s;"></i>
                    </div>
                    <ul class="sidebar-group-list" style="transition: all 0.3s ease;">
                        <li>
                            <a href="javascript:void(0)" class="profile-tab-btn active" data-tab="tab-profile">Hồ Sơ</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="profile-tab-btn" data-tab="tab-payment">Ngân Hàng</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="profile-tab-btn" data-tab="tab-address">Địa Chỉ</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="profile-tab-btn" data-tab="tab-security">Đổi Mật Khẩu</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="profile-tab-btn" data-tab="tab-privacy">Những Thiết Lập Riêng Tư</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="profile-tab-btn" data-tab="tab-personal-info">Thông Tin Cá Nhân</a>
                        </li>
                    </ul>

                    <div style="margin: 12px 0;"></div>

                    <!-- MỤC LỚN 2: ĐƠN MUA -->
                    <div class="sidebar-group-header" onclick="toggleSidebarGroup(this)" style="display: flex; align-items: center; justify-content: space-between; cursor: pointer; user-select: none;">
                        <span style="display: flex; align-items: center; gap: 10px;">
                            <i class="fa-regular fa-clipboard" style="color: #2b6cb0; font-size: 19px; width: 22px; text-align: center;"></i> 
                            Đơn Mua
                        </span>
                        <i class="fas fa-chevron-down group-toggle-icon" style="font-size: 11px; color: #888; transition: transform 0.3s;"></i>
                    </div>
                    <ul class="sidebar-group-list" style="transition: all 0.3s ease;">
                        <li>
                            <a href="javascript:void(0)" class="profile-tab-btn" data-tab="tab-orders">Tất cả đơn hàng</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="profile-tab-btn" data-tab="tab-outfits">Bộ phối đồ của tôi</a>
                        </li>
                        <li>
                            <a href="outfit-builder.php">Tạo phối đồ mới</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="profile-tab-btn" data-tab="tab-wishlist">Sản phẩm yêu thích</a>
                        </li>
                        
                        <li id="adminSidebarNavItem" style="display: none;">
                            <a href="admin.php" style="color: #BF8A49; font-weight: 600;">Trang quản trị Admin</a>
                        </li>

                        <li>
                            <a href="javascript:void(0)" id="logoutBtn" style="color: #de3b3b;">Đăng xuất</a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- CONTENT AREA -->
            <div class="account-content">
                
                <!-- TAB 1: PROFILE DETAILS & EDIT (HỒ SƠ CỦA TÔI) -->
                <div id="tab-profile" class="profile-tab-content active">
                    <h2>Hồ sơ của tôi</h2>
                    <p style="color: #666; font-size: 14px; margin-top: -25px; margin-bottom: 25px;">Quản lý thông tin hồ sơ để bảo mật tài khoản</p>
                    
                    <div id="profileError" class="alert alert-danger"></div>
                    <div id="profileSuccess" class="alert alert-success"></div>

                    <form id="profileForm" class="profile-form">
                        <input type="hidden" id="profCurrentPassword">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="profFullname">Họ và tên</label>
                                <div class="input-with-icon">
                                    <i class="fa-regular fa-user input-icon"></i>
                                    <input type="text" id="profFullname" required placeholder="Nhập họ và tên của bạn">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="profBio">Tiểu sử</label>
                                <div class="input-with-icon">
                                    <i class="fa-regular fa-comment-dots input-icon"></i>
                                    <input type="text" id="profBio" placeholder="Thiết lập tiểu sử của bạn">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="profGender">Giới tính</label>
                                <div class="input-with-icon">
                                    <i class="fa-solid fa-venus-mars input-icon"></i>
                                    <select id="profGender" style="padding-left: 48px;">
                                        <option value="Nam">Nam</option>
                                        <option value="Nữ">Nữ</option>
                                        <option value="Khác">Khác</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="profBirthday">Ngày sinh</label>
                                <div class="input-with-icon">
                                    <i class="fa-regular fa-calendar input-icon"></i>
                                    <input type="date" id="profBirthday">
                                </div>
                            </div>
                        </div>

                        <div class="form-row" style="margin-top: 15px;">
                            <div class="form-group">
                                <label for="profPhone">Số điện thoại</label>
                                <div class="input-with-icon input-with-icon-btn">
                                    <i class="fa-solid fa-phone input-icon"></i>
                                    <input type="tel" id="profPhone" disabled style="background-color: #f5f5f5; color: #666; cursor: not-allowed;">
                                    <button type="button" class="btn-lock-action" id="unlockPhoneBtn" onclick="openSecurityModal()">
                                        <i class="fas fa-lock"></i> Thay đổi
                                    </button>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="profEmail">Địa chỉ Email</label>
                                <div class="input-with-icon input-with-icon-btn">
                                    <i class="fa-regular fa-envelope input-icon"></i>
                                    <input type="email" id="profEmail" disabled style="background-color: #f5f5f5; color: #666; cursor: not-allowed;">
                                    <button type="button" class="btn-lock-action" id="unlockEmailBtn" onclick="openSecurityModal()">
                                        <i class="fas fa-lock"></i> Thay đổi
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="form-row" style="margin-top: 15px;">
                            <div class="form-group">
                                <label for="profAvatar">Ảnh đại diện (URL hoặc tải từ máy)</label>
                                <div class="input-with-icon input-with-icon-btn">
                                    <i class="fa-solid fa-image input-icon"></i>
                                    <input type="text" id="profAvatar" placeholder="Dán link ảnh hoặc chọn file từ máy..." onchange="previewProfileAvatar(this.value)">
                                    <button type="button" class="btn-lock-action" onclick="document.getElementById('profAvatarFile').click()" style="background-color: #BF8A49; color: #fff; border: none; min-width: 130px;">
                                        <i class="fas fa-upload"></i> Tải ảnh lên
                                    </button>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="profAddress">Địa chỉ nhận hàng mặc định</label>
                                <div class="input-with-icon">
                                    <i class="fa-solid fa-location-dot input-icon"></i>
                                    <input type="text" id="profAddress" placeholder="Nhập địa chỉ nhận hàng của bạn">
                                </div>
                            </div>
                        </div>

                        <div style="margin-top: 30px;">
                            <button type="submit" class="primary-btn">
                                <i class="fas fa-save"></i> LƯU THAY ĐỔI
                            </button>
                        </div>
                    </form>
                </div>

                <!-- TAB PAYMENT: PHƯƠNG THỨC THANH TOÁN (HÌNH 2) -->
                <div id="tab-payment" class="profile-tab-content">
                    <h2>Phương thức thanh toán</h2>
                    
                    <div style="margin-bottom: 30px;">
                        <h3 style="font-size: 15px; color: #888; margin-bottom: 15px; font-weight: 600;">Thẻ Tín dụng/Ghi nợ</h3>
                        <div style="background: #fff; border: 1px solid #e8e8e8; border-radius: 12px; padding: 18px 20px; margin-bottom: 12px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <i class="fa-brands fa-cc-mastercard" style="font-size: 28px; color: #eb001b;"></i>
                                <div>
                                    <div style="font-weight: bold; font-size: 16px; color: #222;">Sacombank <span style="font-weight: normal; color: #666; font-size: 14px;">*3518</span></div>
                                    <span style="display: inline-block; background: #fff1f0; color: #ff4d4f; border: 1px solid #ffa39e; font-size: 11px; padding: 2px 8px; border-radius: 4px; margin-top: 4px; font-weight: 600;">MẶC ĐỊNH</span>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right" style="color: #ccc; font-size: 14px;"></i>
                        </div>

                        <div style="background: #fafafa; border: 1px dashed #ccc; border-radius: 12px; padding: 16px; text-align: center; cursor: pointer; transition: 0.3s;" onclick="alert('Tính năng liên kết thẻ mới đang được bảo trì!')">
                            <i class="fas fa-plus" style="color: #888; margin-right: 8px;"></i> <span style="font-weight: 600; color: #555;">Thêm thẻ mới</span>
                        </div>
                    </div>

                    <div style="margin-bottom: 30px;">
                        <h3 style="font-size: 15px; color: #888; margin-bottom: 15px; font-weight: 600;">Ngân hàng liên kết</h3>
                        <div style="background: #fff; border: 1px solid #e8e8e8; border-radius: 12px; padding: 18px 20px; display: flex; align-items: center; gap: 15px; cursor: pointer;" onclick="alert('Tính năng liên kết ngân hàng đang được phát triển!')">
                            <i class="fa-solid fa-building-columns" style="font-size: 24px; color: #1890ff;"></i>
                            <span style="font-weight: 600; color: #333;">+ Thêm tài khoản ngân hàng</span>
                        </div>
                    </div>

                    <div>
                        <h3 style="font-size: 15px; color: #888; margin-bottom: 15px; font-weight: 600;">Dịch vụ liên kết Ví điện tử</h3>
                        <div style="background: #fff; border: 1px solid #e8e8e8; border-radius: 12px; padding: 18px 20px; display: flex; align-items: center; justify-content: space-between; cursor: pointer;">
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <i class="fa-solid fa-wallet" style="font-size: 24px; color: #ff4d4f;"></i>
                                <span style="font-weight: 600; color: #222;">Dịch vụ liên kết với ShopeePay / Ví The Fox</span>
                            </div>
                            <i class="fas fa-chevron-right" style="color: #ccc; font-size: 14px;"></i>
                        </div>
                    </div>
                </div>

                <!-- TAB ADDRESS: ĐỊA CHỈ CỦA TÔI (HÌNH 5) -->
                <div id="tab-address" class="profile-tab-content">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; border-bottom: 2px solid rgba(191, 138, 73, 0.1); padding-bottom: 16px;">
                        <h2 style="margin-bottom: 0; border-bottom: none; padding-bottom: 0;">Địa chỉ của Tôi</h2>
                        <button type="button" class="primary-btn" style="height: 42px; min-width: 160px; font-size: 14px; background: #ee4d2d;" onclick="openAddAddressModal()">
                            <i class="fas fa-plus"></i> Thêm Địa Chỉ Mới
                        </button>
                    </div>

                    <div id="addressListContainer" style="display: flex; flex-direction: column; gap: 15px;">
                        <p style="padding: 20px; color: var(--gray);">Đang tải danh sách địa chỉ...</p>
                    </div>
                </div>

                <!-- TAB SECURITY: TÀI KHOẢN & BẢO MẬT (HÌNH 3) -->
                <div id="tab-security" class="profile-tab-content">
                    <h2>Tài khoản & Bảo mật</h2>
                    <div id="securityError" class="alert alert-danger"></div>
                    <div id="securitySuccess" class="alert alert-success"></div>

                    <!-- KHỐI 1: THAY ĐỔI SĐT & EMAIL -->
                    <div style="background: #fafafa; border: 1px solid #eee; border-radius: 12px; padding: 22px; margin-bottom: 30px;">
                        <h3 style="font-size: 16px; color: #1e3c72; margin-bottom: 15px; border-bottom: 2px solid #1e3c72; padding-bottom: 8px; display: inline-block;">
                            <i class="fas fa-user-shield"></i> Thay đổi Email & Số điện thoại
                        </h3>
                        <form id="securityContactForm" class="profile-form">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="secPhone">Số điện thoại mới</label>
                                    <div class="input-with-icon">
                                        <i class="fa-solid fa-phone input-icon"></i>
                                        <input type="tel" id="secPhone" required placeholder="Nhập số điện thoại mới">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="secEmail">Địa chỉ Email mới</label>
                                    <div class="input-with-icon">
                                        <i class="fa-regular fa-envelope input-icon"></i>
                                        <input type="email" id="secEmail" required placeholder="Nhập địa chỉ email mới">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" style="margin-top: 15px;">
                                <label for="secContactPassword" style="color: #de3b3b;"><i class="fas fa-key"></i> Nhập Mật khẩu hiện tại để xác nhận đổi</label>
                                <div class="input-with-icon">
                                    <i class="fa-solid fa-lock input-icon"></i>
                                    <input type="password" id="secContactPassword" required placeholder="Nhập mật khẩu để xác thực thay đổi" style="padding-right: 45px;">
                                    <i class="fas fa-eye-slash toggle-password-btn" onclick="togglePasswordVisibility('secContactPassword', this)"></i>
                                </div>
                            </div>
                            <div style="margin-top: 20px;">
                                <button type="submit" class="primary-btn" style="background: #BF8A49;">
                                    <i class="fas fa-shield-alt"></i> CẬP NHẬT SĐỘ & EMAIL
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- KHỐI 2: THAY ĐỔI MẬT KHẨU -->
                    <div style="background: #fafafa; border: 1px solid #eee; border-radius: 12px; padding: 22px;">
                        <h3 style="font-size: 16px; color: #1e3c72; margin-bottom: 15px; border-bottom: 2px solid #1e3c72; padding-bottom: 8px; display: inline-block;">
                            <i class="fas fa-key"></i> Thay đổi Mật khẩu
                        </h3>
                        <form id="securityForm" class="profile-form">
                            <div class="form-group" style="margin-bottom: 20px;">
                                <label for="secCurrentPassword">Mật khẩu hiện tại</label>
                                <div class="input-with-icon">
                                    <i class="fa-solid fa-key input-icon"></i>
                                    <input type="password" id="secCurrentPassword" required placeholder="Nhập mật khẩu hiện tại của bạn" style="padding-right: 45px;">
                                    <i class="fas fa-eye-slash toggle-password-btn" onclick="togglePasswordVisibility('secCurrentPassword', this)"></i>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="secNewPassword">Mật khẩu mới</label>
                                    <div class="input-with-icon">
                                        <i class="fa-solid fa-lock input-icon"></i>
                                        <input type="password" id="secNewPassword" required placeholder="Tối thiểu 6 ký tự" style="padding-right: 45px;">
                                        <i class="fas fa-eye-slash toggle-password-btn" onclick="togglePasswordVisibility('secNewPassword', this)"></i>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="secConfirmPassword">Xác nhận mật khẩu mới</label>
                                    <div class="input-with-icon">
                                        <i class="fa-solid fa-lock input-icon"></i>
                                        <input type="password" id="secConfirmPassword" required placeholder="Nhập lại mật khẩu mới" style="padding-right: 45px;">
                                        <i class="fas fa-eye-slash toggle-password-btn" onclick="togglePasswordVisibility('secConfirmPassword', this)"></i>
                                    </div>
                                </div>
                            </div>

                            <div style="margin-top: 20px;">
                                <button type="submit" class="primary-btn" style="background: #1e3c72;">
                                    <i class="fas fa-key"></i> ĐỔI MẬT KHẨU
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- TAB PRIVACY: NHỮNG THIẾT LẬP RIÊNG TƯ -->
                <div id="tab-privacy" class="profile-tab-content">
                    <h2 style="font-size: 18px; font-weight: 500; color: #222; margin-bottom: 25px; border-bottom: 1px solid #efefef; padding-bottom: 15px; text-transform: none;">Những thiết lập riêng tư</h2>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0;">
                        <span style="font-size: 14.5px; color: #333;">Yêu cầu xóa tài khoản</span>
                        <button type="button" onclick="handleDeleteAccount()" style="padding: 10px 32px; background: #BF8A49; color: #fff; border: none; border-radius: 4px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s; box-shadow: 0 2px 5px rgba(191, 138, 73, 0.2);" onmouseover="this.style.background='#a67439'" onmouseout="this.style.background='#BF8A49'">
                            Xóa bỏ
                        </button>
                    </div>
                </div>

                <!-- TAB PERSONAL INFO: THÔNG TIN CÁ NHÂN (CCCD) -->
                <div id="tab-personal-info" class="profile-tab-content">
                    <h2 style="font-size: 18px; font-weight: 500; color: #222; margin-bottom: 8px; border-bottom: none; padding-bottom: 0; text-transform: none;">Thông tin cá nhân</h2>
                    <p style="color: #666; font-size: 13.5px; margin-bottom: 30px; line-height: 1.5;">
                        Bạn vui lòng nhập chính xác thông tin CCCD để đơn hàng được thông quan theo quy định từ ngày 9/7. Thông tin sẽ được bảo mật theo Chính sách Bảo mật The Fox
                    </p>

                    <form id="personalInfoForm" onsubmit="handleSaveCccd(event)" style="max-width: 650px; display: flex; flex-direction: column; gap: 20px;">
                        <div style="display: flex; align-items: center;">
                            <label style="width: 140px; font-size: 14px; color: #333; flex-shrink: 0; text-transform: none; font-weight: 400; margin: 0;">Họ và tên</label>
                            <input type="text" id="cccd_fullname" name="cccd_fullname" placeholder="Họ và tên đầy đủ trên CCCD" style="flex: 1; height: 42px; border: 1px solid #e0e0e0; border-radius: 4px; padding: 0 14px; font-size: 14px; outline: none; background: #fff;">
                        </div>

                        <div style="display: flex; align-items: center;">
                            <label style="width: 140px; font-size: 14px; color: #333; flex-shrink: 0; text-transform: none; font-weight: 400; margin: 0;">Số CCCD</label>
                            <input type="text" id="cccd_number" name="cccd_number" placeholder="Số định danh cá nhân trên CCCD" style="flex: 1; height: 42px; border: 1px solid #e0e0e0; border-radius: 4px; padding: 0 14px; font-size: 14px; outline: none; background: #fff;">
                        </div>

                        <div style="display: flex; align-items: flex-start;">
                            <label style="width: 140px; font-size: 14px; color: #333; flex-shrink: 0; text-transform: none; font-weight: 400; margin-top: 10px;">Địa chỉ</label>
                            <div style="flex: 1; display: flex; flex-direction: column; align-items: flex-end;">
                                <textarea id="cccd_address" name="cccd_address" rows="3" placeholder="Địa chỉ Nơi thường trú trên CCCD" maxlength="200" oninput="document.getElementById('cccd_char_count').innerText = this.value.length + '/200'" style="width: 100%; border: 1px solid #e0e0e0; border-radius: 4px; padding: 10px 14px; font-size: 14px; outline: none; resize: vertical; font-family: inherit; box-sizing: border-box; background: #fff;"></textarea>
                                <span id="cccd_char_count" style="font-size: 12px; color: #999; margin-top: 5px;">0/200</span>
                            </div>
                        </div>

                        <div style="display: flex; margin-top: 10px; padding-left: 140px;">
                            <button type="submit" style="height: 42px; padding: 0 36px; background: #BF8A49; color: #fff; border: none; border-radius: 4px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s; box-shadow: 0 2px 6px rgba(191, 138, 73, 0.25);" onmouseover="this.style.background='#a67439'" onmouseout="this.style.background='#BF8A49'">
                                Xác Nhận
                            </button>
                        </div>
                    </form>
                </div>

                <!-- TAB ORDERS: QUẢN LÝ ĐƠN HÀNG / ĐƠN MUA -->
                <div id="tab-orders" class="profile-tab-content">
                    <!-- Dải tab trạng thái đơn hàng kiểu Shopee -->
                    <div class="order-status-bar" style="display: flex; background: #fff; border-bottom: 2px solid #efefef; margin-bottom: 20px; overflow-x: auto; white-space: nowrap; border-radius: 4px 4px 0 0;">
                        <button class="order-status-tab active" data-status="all" style="flex: 1; padding: 14px 16px; border: none; background: none; font-size: 14.5px; font-weight: 600; color: #ee4d2d; border-bottom: 3px solid #ee4d2d; cursor: pointer; transition: all 0.2s; text-align: center; min-width: 100px;">Tất cả</button>
                        <button class="order-status-tab" data-status="Chờ thanh toán" style="flex: 1; padding: 14px 16px; border: none; background: none; font-size: 14.5px; font-weight: 500; color: #555; border-bottom: 3px solid transparent; cursor: pointer; transition: all 0.2s; text-align: center; min-width: 130px;">Chờ thanh toán</button>
                        <button class="order-status-tab" data-status="Đang giao hàng" style="flex: 1; padding: 14px 16px; border: none; background: none; font-size: 14.5px; font-weight: 500; color: #555; border-bottom: 3px solid transparent; cursor: pointer; transition: all 0.2s; text-align: center; min-width: 110px;">Vận chuyển</button>
                        <button class="order-status-tab" data-status="Đang xử lý" style="flex: 1; padding: 14px 16px; border: none; background: none; font-size: 14.5px; font-weight: 500; color: #555; border-bottom: 3px solid transparent; cursor: pointer; transition: all 0.2s; text-align: center; min-width: 130px;">Chờ giao hàng</button>
                        <button class="order-status-tab" data-status="Đã hoàn thành" style="flex: 1; padding: 14px 16px; border: none; background: none; font-size: 14.5px; font-weight: 500; color: #555; border-bottom: 3px solid transparent; cursor: pointer; transition: all 0.2s; text-align: center; min-width: 110px;">Hoàn thành</button>
                        <button class="order-status-tab" data-status="Đã hủy" style="flex: 1; padding: 14px 16px; border: none; background: none; font-size: 14.5px; font-weight: 500; color: #555; border-bottom: 3px solid transparent; cursor: pointer; transition: all 0.2s; text-align: center; min-width: 90px;">Đã hủy</button>
                        <button class="order-status-tab" data-status="Trả hàng" style="flex: 1; padding: 14px 16px; border: none; background: none; font-size: 14.5px; font-weight: 500; color: #555; border-bottom: 3px solid transparent; cursor: pointer; transition: all 0.2s; text-align: center; min-width: 150px;">Trả hàng/Hoàn tiền</button>
                    </div>

                    <!-- Toolbar tìm kiếm đơn hàng -->
                    <div class="order-toolbar" style="margin-bottom: 20px; background: #eaeaea; padding: 12px 15px; border-radius: 4px;">
                        <div class="order-search-wrap" style="position: relative; display: flex; align-items: center;">
                            <i class="fas fa-search search-icon" style="position: absolute; left: 15px; color: #777; font-size: 15px;"></i>
                            <input type="text" id="order-search" placeholder="Bạn có thể tìm kiếm theo tên Shop, ID đơn hàng hoặc Tên Sản phẩm" style="padding-left: 42px; height: 44px; border-radius: 3px; border: 1px solid #ccc; width: 100%; font-size: 14px; background: #fff;">
                        </div>
                    </div>

                    <!-- Bảng đơn hàng -->
                    <div class="order-table-wrap" style="overflow-x: auto;">
                        <table class="table-premium" style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="background: #fafafa; border-bottom: 2px solid #eee;">
                                    <th style="padding: 14px; text-align: left;">Mã Đơn</th>
                                    <th style="padding: 14px; text-align: left;">Ngày đặt</th>
                                    <th style="padding: 14px; text-align: left;">Khách hàng</th>
                                    <th style="padding: 14px; text-align: left;">Thành tiền</th>
                                    <th style="padding: 14px; text-align: left;">Thanh toán</th>
                                    <th style="padding: 14px; text-align: left;">Trạng thái</th>
                                    <th style="padding: 14px; text-align: center;">Hành động</th>
                                </tr>
                            </thead>
                            <tbody id="order-list-tbody">
                                <tr>
                                    <td colspan="7" style="padding: 30px; text-align: center; color: #888;">Đang tải đơn hàng...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>





                <!-- TAB 2: MY OUTFITS -->
                <div id="tab-outfits" class="profile-tab-content">
                    <h2>Bộ phối đồ đã lưu</h2>
                    <div id="outfitsList" class="row" style="gap: 20px; display: flex; flex-wrap: wrap;">
                    <!-- Sẽ được load từ Ajax -->
                    <p style="color: var(--gray);">Đang tải danh sách bộ phối đồ...</p>
                </div>
            </div>

            <!-- TAB 3: MY WISHLIST -->
            <div id="tab-wishlist" class="profile-tab-content">
                <h2>SẢN PHẨM YÊU THÍCH</h2>
                <div class="wishlist-grid" id="wishlistGrid">
                    <p style="padding: 20px; color: var(--gray);">Đang tải danh sách yêu thích...</p>
                </div>
            </div>



        </div>
    </div>
</div>
</main>




<!-- MODAL: PREVIEW SAVED OUTFIT -->
<div class="modal" id="outfitPreviewModal">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h3 id="previewOutfitName">Chi tiết bộ phối đồ</h3>
            <button class="close-btn" onclick="closeOutfitPreviewModal()">&times;</button>
        </div>
        <div id="previewOutfitItems" class="row" style="gap: 15px; margin-bottom: 20px; justify-content: center;">
            <!-- items -->
        </div>
        <div style="text-align: right; font-size: 16px; font-weight: bold; margin-bottom: 20px;" id="previewOutfitTotal">
            Tổng giá trị: 0đ
        </div>
        <div class="btn-group">
            <button type="button" class="btn-secondary" onclick="closeOutfitPreviewModal()">Đóng</button>
            <button type="button" class="btn-premium" style="max-width: 220px;" id="addOutfitToCartBtn">
                <i class="fas fa-shopping-cart"></i> THÊM TẤT CẢ VÀO GIỎ
            </button>
        </div>
    </div>
</div>

<!-- MODAL: SECURITY IDENTITY VERIFICATION -->
<div class="modal" id="securityUnlockModal">
    <div class="modal-content" style="max-width: 420px; border-radius: 16px;">
        <div class="modal-header">
            <h3 style="display: flex; align-items: center; gap: 8px; color: #BF8A49; font-weight: 700;">
                <i class="fas fa-shield-alt"></i> XÁC MINH DANH TÍNH
            </h3>
            <button class="close-btn" onclick="closeSecurityModal()">&times;</button>
        </div>
        <div class="modal-body" style="padding: 10px 0;">
            <p style="font-size: 14px; color: #666; margin-bottom: 20px; line-height: 1.5;">
                Để bảo vệ thông tin nhạy cảm của bạn (Email/Số điện thoại) khỏi lộ lọt, vui lòng nhập mật khẩu tài khoản hiện tại để mở khóa quyền chỉnh sửa.
            </p>
            <div id="securityModalAlert" class="alert alert-danger" style="margin-bottom: 15px;"></div>
            <div class="form-group" style="position: relative;">
                <label for="securityPassword" style="font-size: 13px; font-weight: 700; color: #444;">MẬT KHẨU CỦA BẠN</label>
                <input type="password" id="securityPassword" placeholder="Nhập mật khẩu hiện tại" style="padding-right: 45px; height: 50px; border-radius: 8px; border: 1px solid #ddd; width: 100%;">
                <i class="fas fa-eye-slash toggle-password-btn" onclick="togglePasswordVisibility('securityPassword', this)" style="position: absolute; right: 15px; top: 40px;"></i>
            </div>
        </div>
        <div class="btn-group" style="margin-top: 15px;">
            <button type="button" class="btn-secondary" onclick="closeSecurityModal()">HỦY</button>
            <button type="button" class="primary-btn" id="confirmSecurityBtn" style="height: 48px; min-width: 140px; border-radius: 12px 0 12px 0; background: #BF8A49;">
                XÁC NHẬN
            </button>
        </div>
    </div>
</div>

<!-- FOOTER -->
<div class="site-bottom">
    <div id="footer">
        <div class="container">
            <div class="footer-wrapper">
                <div class="footer-col">
                    <div class="footer-logo">
                        <img src="../assets/images/fashion.ico" alt="The Fox Logo">
                    </div>
                    <div class="footer-social">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                    <div class="footer-contact">
                        <p>LIÊN HỆ: <a href="mailto:info@thefox.com">info@thefox.com</a></p>
                    </div>
                </div>
                <div class="footer-col">
                    <h4>GIỚI THIỆU</h4>
                    <ul>
                        <li><a href="#">Về chúng tôi</a></li>
                        <li><a href="#">Tin tức</a></li>
                        <li><a href="#">Liên hệ</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>DỊCH VỤ KHÁCH HÀNG</h4>
                    <ul>
                        <li><a href="#">Chính sách bảo hành</a></li>
                        <li><a href="#">Chính sách đổi trả</a></li>
                        <li><a href="#">Hướng dẫn mua hàng</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>LIÊN HỆ</h4>
                    <ul>
                        <li><a href="#">Hỗ trợ khách hàng</a></li>
                        <li><a href="#">Góp ý</a></li>
                        <li><a href="#">Tuyển dụng</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <div class="footer-new">
                        <h3>ĐĂNG KÝ NHẬN TIN MỚI NHẤT</h3>
                        <form action="#" method="post">
                            <input type="email" placeholder="Nhập email của bạn" required>
                            <button type="submit">Đăng ký</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<footer id="footer-bottom">
    <div class="copy-right">
        <p>©THE FOX</p>
    </div>
</footer>
<!--=========================DETAIL MODAL==========================-->
<div class="order-modal-overlay" id="order-detail-modal">
    <div class="order-modal-box">
        <div class="order-modal-head">
            <h3>Chi Tiết Đơn Hàng <span id="modal-order-code" style="color: #BF8A49;"></span></h3>
            <button class="order-modal-close" id="close-modal-btn" title="Đóng">&times;</button>
        </div>
        <div id="modal-body-content" class="order-modal-body">
            <!-- Nội dung render bởi order.js -->
        </div>
        <div class="order-modal-foot">
            <div class="sim-controls" id="adminSimControls" style="display: none;">
                <span class="sim-label"><i class="fas fa-tools"></i> Giả lập trạng thái:</span>
                <select id="sim-status-select">
                    <option value="Chờ xác nhận">Chờ xác nhận</option>
                    <option value="Đang xử lý">Đang xử lý</option>
                    <option value="Đang giao hàng">Đang giao hàng</option>
                    <option value="Đã hoàn thành">Đã hoàn thành</option>
                    <option value="Đã hủy">Đã hủy</option>
                </select>
            </div>
            <button type="button" class="btn-secondary" id="close-modal-footer-btn">Đóng</button>
        </div>
    </div>
</div>

<?php include 'sidebarcart.php'; ?>

<!-- Script -->
<script src="../assets/js/order.js"></script>
<script src="../assets/js/scroll.js"></script>
<script src="../assets/js/userProfile.js"></script>

<?php include 'addAddressProfile.php'; ?>
</body>
</html>
