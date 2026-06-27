<?php
/* ==========================================================================
   THE FOX - Controller Quản Lý Người Dùng & Tài Khoản (User Controller)
   Áp dụng chuẩn thiết kế phần mềm Clean Code & Senior Developer
   Tên biến/hàm: Tiếng Anh chuẩn | Chú thích (Comments): Tiếng Việt chuyên nghiệp
   ========================================================================== */

require_once __DIR__ . '/../models/User.php';

class UserController {
    private $databaseConnection;
    private $userModel;

    public function __construct($databaseConnection) {
        $this->databaseConnection = $databaseConnection;
        $this->userModel = new User($databaseConnection);
    }

    // Đăng ký tài khoản người dùng mới và tự động khởi tạo phiên đăng nhập (Session)
    public function register($requestData) {
        try {
            if (empty($requestData['fullname']) || empty($requestData['email']) || empty($requestData['phone']) || empty($requestData['password'])) {
                return ['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin bắt buộc.'];
            }

            $fullname = trim($requestData['fullname']);
            $emailAddress = trim($requestData['email']);
            $phoneNumber = trim($requestData['phone']);
            $plainPassword = $requestData['password'];

            if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
                return ['success' => false, 'message' => 'Email không hợp lệ.'];
            }

            // Tiêu chuẩn độ dài mật khẩu tối thiểu để bảo vệ tài khoản khỏi các cuộc tấn công Brute-force đơn giản
            if (strlen($plainPassword) < 6) {
                return ['success' => false, 'message' => 'Mật khẩu phải có ít nhất 6 ký tự.'];
            }

            $newUserId = $this->userModel->register($fullname, $emailAddress, $phoneNumber, $plainPassword);
            
            // Khởi tạo phiên đăng nhập ngay lập tức nhằm nâng cao trải nghiệm người dùng (UX) sau khi đăng ký
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['user_id'] = $newUserId;
            $_SESSION['email'] = $emailAddress;
            $_SESSION['fullname'] = $fullname;
            $_SESSION['role'] = 'user';

            return ['success' => true, 'message' => 'Đăng ký tài khoản thành công.', 'user_id' => $newUserId];
        } catch (Exception $exception) {
            return ['success' => false, 'message' => $exception->getMessage()];
        }
    }

    // Xác thực thông tin đăng nhập và thiết lập Session vai trò
    public function login($requestData) {
        try {
            if (empty($requestData['email']) || empty($requestData['password'])) {
                return ['success' => false, 'message' => 'Vui lòng nhập email và mật khẩu.'];
            }

            $emailAddress = trim($requestData['email']);
            $plainPassword = $requestData['password'];

            $authenticatedUser = $this->userModel->login($emailAddress, $plainPassword);
            if ($authenticatedUser) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['user_id'] = $authenticatedUser['id'];
                $_SESSION['email'] = $authenticatedUser['email'];
                $_SESSION['fullname'] = $authenticatedUser['fullname'];
                $_SESSION['role'] = $authenticatedUser['role'];

                return [
                    'success' => true, 
                    'message' => 'Đăng nhập thành công.', 
                    'user' => [
                        'id' => $authenticatedUser['id'],
                        'fullname' => $authenticatedUser['fullname'],
                        'email' => $authenticatedUser['email'],
                        'role' => $authenticatedUser['role']
                    ]
                ];
            } else {
                return ['success' => false, 'message' => 'Email hoặc mật khẩu không chính xác.'];
            }
        } catch (Exception $exception) {
            return ['success' => false, 'message' => 'Lỗi hệ thống: ' . $exception->getMessage()];
        }
    }

    // Đăng xuất và hủy bỏ toàn bộ dữ liệu phiên làm việc hiện tại
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = array();
        session_destroy();
        return ['success' => true, 'message' => 'Đăng xuất thành công.'];
    }

    // Lấy chi tiết hồ sơ người dùng theo ID định danh
    public function getProfile($userId) {
        try {
            $userProfileData = $this->userModel->getById($userId);
            if ($userProfileData) {
                return ['success' => true, 'data' => $userProfileData];
            } else {
                return ['success' => false, 'message' => 'Không tìm thấy thông tin người dùng.'];
            }
        } catch (Exception $exception) {
            return ['success' => false, 'message' => $exception->getMessage()];
        }
    }

    // Cập nhật thông tin cá nhân và đồng bộ lại trạng thái Session hiển thị trên giao diện
    public function updateProfile($userId, $requestData) {
        try {
            if (empty($requestData['fullname']) || empty($requestData['email']) || empty($requestData['phone'])) {
                return ['success' => false, 'message' => 'Vui lòng nhập đầy đủ họ tên, email và số điện thoại.'];
            }

            $fullname = trim($requestData['fullname']);
            $emailAddress = trim($requestData['email']);
            $phoneNumber = trim($requestData['phone']);
            $shippingAddress = isset($requestData['address']) ? trim($requestData['address']) : null;
            $avatarUrl = isset($requestData['avatar']) ? trim($requestData['avatar']) : null;
            $gender = isset($requestData['gender']) ? trim($requestData['gender']) : null;
            $birthdayDate = !empty($requestData['birthday']) ? trim($requestData['birthday']) : null;
            $personalBio = isset($requestData['bio']) ? trim($requestData['bio']) : null;

            $this->userModel->update($userId, $fullname, $emailAddress, $phoneNumber, $shippingAddress, $avatarUrl, null, $gender, $birthdayDate, $personalBio);

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['fullname'] = $fullname;
            $_SESSION['email'] = $emailAddress;
            if ($avatarUrl) $_SESSION['avatar'] = $avatarUrl;

            return ['success' => true, 'message' => 'Cập nhật thông tin cá nhân thành công.'];
        } catch (Exception $exception) {
            return ['success' => false, 'message' => $exception->getMessage()];
        }
    }

    // Thay đổi mật khẩu cá nhân kèm bước xác minh mật khẩu hiện tại
    public function changePassword($userId, $requestData) {
        try {
            if (empty($requestData['current_password']) || empty($requestData['new_password'])) {
                return ['success' => false, 'message' => 'Vui lòng nhập đầy đủ mật khẩu hiện tại và mật khẩu mới.'];
            }

            $currentPassword = $requestData['current_password'];
            $newPassword = $requestData['new_password'];

            if (strlen($newPassword) < 6) {
                return ['success' => false, 'message' => 'Mật khẩu mới phải có ít nhất 6 ký tự.'];
            }

            $statementCheck = $this->databaseConnection->prepare("SELECT password FROM users WHERE id = ?");
            $statementCheck->execute([$userId]);
            $userRecord = $statementCheck->fetch(PDO::FETCH_ASSOC);

            if (!$userRecord || !password_verify($currentPassword, $userRecord['password'])) {
                return ['success' => false, 'message' => 'Mật khẩu hiện tại không chính xác.'];
            }

            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
            $statementUpdate = $this->databaseConnection->prepare("UPDATE users SET password = ? WHERE id = ?");
            $statementUpdate->execute([$hashedPassword, $userId]);

            return ['success' => true, 'message' => 'Thay đổi mật khẩu thành công!'];
        } catch (Exception $exception) {
            return ['success' => false, 'message' => $exception->getMessage()];
        }
    }

    // Cập nhật dữ liệu Căn cước công dân (CCCD)
    public function updateCccd($userId, $requestData) {
        try {
            $cccdFullname = isset($requestData['cccd_fullname']) ? trim($requestData['cccd_fullname']) : '';
            $cccdNumber = isset($requestData['cccd_number']) ? trim($requestData['cccd_number']) : '';
            $cccdAddress = isset($requestData['cccd_address']) ? trim($requestData['cccd_address']) : '';

            $this->userModel->updateCccd($userId, $cccdFullname, $cccdNumber, $cccdAddress);
            return ['success' => true, 'message' => 'Cập nhật thông tin cá nhân (CCCD) thành công!'];
        } catch (Exception $exception) {
            return ['success' => false, 'message' => $exception->getMessage()];
        }
    }

    // Xóa vĩnh viễn tài khoản của chính mình và hủy phiên đăng nhập
    public function deleteOwnAccount($userId) {
        try {
            $isDeletionSuccessful = $this->userModel->delete($userId);
            if ($isDeletionSuccessful) {
                $this->logout();
                return ['success' => true, 'message' => 'Đã xóa tài khoản vĩnh viễn thành công.'];
            } else {
                return ['success' => false, 'message' => 'Không thể xóa tài khoản.'];
            }
        } catch (Exception $exception) {
            return ['success' => false, 'message' => $exception->getMessage()];
        }
    }

    // Tải tệp ảnh đại diện lên máy chủ kèm xử lý kiểm tra bảo mật file
    public function uploadAvatar($userId, $fileDataArray) {
        try {
            if (!isset($fileDataArray) || $fileDataArray['error'] !== UPLOAD_ERR_OK) {
                return ['success' => false, 'message' => 'Vui lòng chọn một tập tin ảnh hợp lệ.'];
            }

            // Kiểm tra danh sách trắng định dạng ảnh (Whitelist validation) nhằm ngăn chặn tải lên các tệp mã độc (.php, .exe)
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            $fileExtension = strtolower(pathinfo($fileDataArray['name'], PATHINFO_EXTENSION));

            if (!in_array($fileExtension, $allowedExtensions)) {
                return ['success' => false, 'message' => 'Định dạng file không được hỗ trợ. Chỉ chấp nhận JPG, PNG, WEBP, GIF.'];
            }

            // Giới hạn dung lượng tải lên 5MB để bảo vệ bộ nhớ máy chủ
            if ($fileDataArray['size'] > 5 * 1024 * 1024) {
                return ['success' => false, 'message' => 'Dung lượng ảnh không được vượt quá 5MB.'];
            }

            $uploadDirectory = __DIR__ . '/../assets/images/avatars/';
            if (!file_exists($uploadDirectory)) {
                mkdir($uploadDirectory, 0777, true);
            }

            $generatedFileName = 'avatar_user_' . $userId . '_' . time() . '.' . $fileExtension;
            $targetFilePath = $uploadDirectory . $generatedFileName;

            if (move_uploaded_file($fileDataArray['tmp_name'], $targetFilePath)) {
                $avatarPublicUrl = '../assets/images/avatars/' . $generatedFileName;
                return ['success' => true, 'message' => 'Tải ảnh đại diện lên thành công!', 'avatar' => $avatarPublicUrl];
            } else {
                return ['success' => false, 'message' => 'Không thể lưu file ảnh vào hệ thống.'];
            }
        } catch (Exception $exception) {
            return ['success' => false, 'message' => 'Lỗi tải ảnh: ' . $exception->getMessage()];
        }
    }

    // Truy xuất toàn bộ danh sách tài khoản (Dành cho Admin quản lý)
    public function listUsers() {
        try {
            $allUsersList = $this->userModel->getAll();
            return ['success' => true, 'data' => $allUsersList];
        } catch (Exception $exception) {
            return ['success' => false, 'message' => $exception->getMessage()];
        }
    }

    // Xóa một tài khoản người dùng xác định (Quyền Admin)
    public function deleteUser($targetUserId) {
        try {
            $isDeletionSuccessful = $this->userModel->delete($targetUserId);
            return [
                'success' => $isDeletionSuccessful,
                'message' => $isDeletionSuccessful ? 'Đã xóa người dùng thành công.' : 'Không tìm thấy người dùng để xóa.'
            ];
        } catch (Exception $exception) {
            return ['success' => false, 'message' => $exception->getMessage()];
        }
    }

    // Khởi tạo một tài khoản người dùng mới trực tiếp từ trang Admin
    public function createUser($requestData) {
        try {
            if (empty($requestData['fullname']) || empty($requestData['email']) || empty($requestData['phone']) || empty($requestData['password']) || empty($requestData['role'])) {
                return ['success' => false, 'message' => 'Vui lòng nhập đầy đủ thông tin.'];
            }

            $fullname = trim($requestData['fullname']);
            $emailAddress = trim($requestData['email']);
            $phoneNumber = trim($requestData['phone']);
            $plainPassword = $requestData['password'];
            $assignedRole = trim($requestData['role']);

            if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
                return ['success' => false, 'message' => 'Email không hợp lệ.'];
            }

            $this->userModel->createByAdmin($fullname, $emailAddress, $phoneNumber, $plainPassword, $assignedRole);
            return ['success' => true, 'message' => 'Thêm người dùng mới thành công.'];
        } catch (Exception $exception) {
            return ['success' => false, 'message' => $exception->getMessage()];
        }
    }

    // Cập nhật thông tin cá nhân và quyền hạn người dùng từ phía Admin
    public function updateUserByAdmin($requestData) {
        try {
            if (empty($requestData['id']) || empty($requestData['fullname']) || empty($requestData['email']) || empty($requestData['phone']) || empty($requestData['role'])) {
                return ['success' => false, 'message' => 'Dữ liệu đầu vào không hợp lệ.'];
            }

            $targetUserId = intval($requestData['id']);
            $fullname = trim($requestData['fullname']);
            $emailAddress = trim($requestData['email']);
            $phoneNumber = trim($requestData['phone']);
            $shippingAddress = isset($requestData['address']) ? trim($requestData['address']) : null;
            $avatarUrl = isset($requestData['avatar']) ? trim($requestData['avatar']) : null;
            $assignedRole = trim($requestData['role']);
            $newPassword = !empty($requestData['password']) ? $requestData['password'] : null;

            $this->userModel->update($targetUserId, $fullname, $emailAddress, $phoneNumber, $shippingAddress, $avatarUrl, $newPassword);
            $this->userModel->updateRole($targetUserId, $assignedRole);

            return ['success' => true, 'message' => 'Cập nhật thông tin thành công.'];
        } catch (Exception $exception) {
            return ['success' => false, 'message' => $exception->getMessage()];
        }
    }
}
?>
