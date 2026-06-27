/* ==========================================================================
   THE FOX - Module Hồ Sơ Người Dùng (User Profile JS)
   Áp dụng chuẩn thiết kế phần mềm Clean Code & Senior Developer
   Tên biến/hàm: Tiếng Anh chuẩn | Chú thích (Comments): Tiếng Việt chuyên nghiệp
   ========================================================================== */

document.addEventListener("DOMContentLoaded", () => {
    // Cờ trạng thái cốt lõi kiểm tra quyền mở khóa thông tin bảo mật
    let isIdentityUnlocked = false;
    let rawEmailAddress = '';
    let rawPhoneNumber = '';

    // Khởi tạo sự kiện chuyển đổi giữa các Tab giao diện trong trang cá nhân
    const tabButtons = document.querySelectorAll(".profile-tab-btn[data-tab]");
    const tabContents = document.querySelectorAll(".profile-tab-content");

    tabButtons.forEach(selectedButton => {
        selectedButton.addEventListener("click", () => {
            tabButtons.forEach(button => button.classList.remove("active"));
            tabContents.forEach(content => content.classList.remove("active"));

            selectedButton.classList.add("active");
            const selectedTabId = selectedButton.getAttribute("data-tab");
            const targetTabElement = document.getElementById(selectedTabId);
            
            if (targetTabElement) {
                targetTabElement.classList.add("active");
            }

            // Cơ chế Lazy-load: Chỉ tải dữ liệu từ máy chủ khi người dùng thực sự bấm chuyển sang Tab tương ứng để tối ưu băng thông
            if (selectedTabId === "tab-outfits") {
                fetchSavedOutfits();
            } else if (selectedTabId === "tab-wishlist") {
                fetchWishlistItems();
            } else if (selectedTabId === "tab-orders") {
                if (typeof loadOrders === 'function') loadOrders();
            }
        });
    });

    // Hỗ trợ truy cập trực tiếp (Deep Link): Cho phép liên kết trực tiếp vào Tab mong muốn thông qua tham số trên URL (Ví dụ: ?tab=outfits)
    const urlSearchParameters = new URLSearchParams(window.location.search);
    const initialTabParameter = urlSearchParameters.get('tab');
    if (initialTabParameter) {
        const matchingTabButton = document.querySelector(`.profile-tab-btn[data-tab="tab-${initialTabParameter}"]`);
        if (matchingTabButton) {
            setTimeout(() => matchingTabButton.click(), 100);
        }
    }

    window.toggleSidebarGroup = function(headerElement) {
        const subMenuList = headerElement.nextElementSibling;
        const toggleIcon = headerElement.querySelector('.group-toggle-icon');
        
        if (subMenuList && subMenuList.style.display === "none") {
            subMenuList.style.display = "block";
            if (toggleIcon) toggleIcon.style.transform = "rotate(0deg)";
        } else if (subMenuList) {
            subMenuList.style.display = "none";
            if (toggleIcon) toggleIcon.style.transform = "rotate(-90deg)";
        }
    };

    window.togglePasswordVisibility = function(inputFieldId, iconElement) {
        const passwordInput = document.getElementById(inputFieldId);
        if (!passwordInput) return;

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            iconElement.classList.remove("fa-eye-slash");
            iconElement.classList.add("fa-eye");
        } else {
            passwordInput.type = "password";
            iconElement.classList.remove("fa-eye");
            iconElement.classList.add("fa-eye-slash");
        }
    };

    // Bảo mật dữ liệu cá nhân (PII): Mã hóa/ẩn bớt các ký tự Email và SĐT để tránh bị nhìn lén trên màn hình ở nơi công cộng
    function maskEmailAddress(emailAddress) {
        if (!emailAddress) return '';
        const emailComponents = emailAddress.split('@');
        if (emailComponents.length !== 2) return emailAddress;

        const localPart = emailComponents[0];
        const domainPart = emailComponents[1];
        
        if (localPart.length <= 3) {
            return localPart[0] + '***@' + domainPart;
        }
        return localPart.substring(0, 3) + '***' + localPart.substring(localPart.length - 1) + '@' + domainPart;
    }

    function maskPhoneNumber(phoneNumber) {
        if (!phoneNumber) return '';
        if (phoneNumber.length < 7) return phoneNumber;
        return phoneNumber.substring(0, 3) + '****' + phoneNumber.substring(phoneNumber.length - 3);
    }

    window.openSecurityModal = function() {
        if (isIdentityUnlocked) return;
        const passwordInput = document.getElementById("securityPassword");
        const modalAlertBox = document.getElementById("securityModalAlert");
        const securityModal = document.getElementById("securityUnlockModal");

        if (passwordInput) passwordInput.value = "";
        if (modalAlertBox) modalAlertBox.style.display = "none";
        if (securityModal) securityModal.classList.add("active");
    };

    window.closeSecurityModal = function() {
        const securityModal = document.getElementById("securityUnlockModal");
        if (securityModal) securityModal.classList.remove("active");
    };

    const confirmSecurityButton = document.getElementById("confirmSecurityBtn");
    if (confirmSecurityButton) {
        confirmSecurityButton.addEventListener("click", async () => {
            const enteredPassword = document.getElementById("securityPassword").value;
            const modalAlertBox = document.getElementById("securityModalAlert");
            
            if (!enteredPassword) {
                if (modalAlertBox) {
                    modalAlertBox.textContent = "Vui lòng nhập mật khẩu xác thực.";
                    modalAlertBox.style.display = "flex";
                }
                return;
            }

            try {
                const passwordVerificationResponse = await fetch("../routes/auth.php?action=verify_password", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ password: enteredPassword })
                });
                const verificationResult = await passwordVerificationResponse.json();
                
                if (verificationResult.success) {
                    isIdentityUnlocked = true;
                    
                    const profileCurrentPasswordInput = document.getElementById("profCurrentPassword");
                    if (profileCurrentPasswordInput) profileCurrentPasswordInput.value = enteredPassword;
                    
                    const phoneInputField = document.getElementById("profPhone");
                    const emailInputField = document.getElementById("profEmail");
                    
                    if (phoneInputField) {
                        phoneInputField.value = rawPhoneNumber;
                        phoneInputField.disabled = false;
                        phoneInputField.style.backgroundColor = "#fff";
                    }
                    
                    if (emailInputField) {
                        emailInputField.value = rawEmailAddress;
                        emailInputField.disabled = false;
                        emailInputField.style.backgroundColor = "#fff";
                    }
                    
                    const unlockPhoneButton = document.getElementById("unlockPhoneBtn");
                    const unlockEmailButton = document.getElementById("unlockEmailBtn");
                    
                    if (unlockPhoneButton) {
                        unlockPhoneButton.innerHTML = '<i class="fas fa-lock-open"></i> Đã mở';
                        unlockPhoneButton.classList.add("unlocked");
                        unlockPhoneButton.disabled = true;
                    }
                    
                    if (unlockEmailButton) {
                        unlockEmailButton.innerHTML = '<i class="fas fa-lock-open"></i> Đã mở';
                        unlockEmailButton.classList.add("unlocked");
                        unlockEmailButton.disabled = true;
                    }
                    
                    closeSecurityModal();
                } else {
                    if (modalAlertBox) {
                        modalAlertBox.textContent = verificationResult.message || "Xác minh thất bại.";
                        modalAlertBox.style.display = "flex";
                    }
                }
            } catch (error) {
                if (modalAlertBox) {
                    modalAlertBox.textContent = "Đã xảy ra lỗi kết nối.";
                    modalAlertBox.style.display = "flex";
                }
            }
        });
    }

    function updateAvatarPreview(avatarUrl) {
        const avatarImageElement = document.getElementById("sidebarAvatarImg");
        const defaultAvatarIcon = document.getElementById("sidebarAvatarIcon");
        
        if (avatarImageElement && defaultAvatarIcon) {
            if (avatarUrl && avatarUrl.trim() !== "") {
                avatarImageElement.src = avatarUrl;
                avatarImageElement.style.display = "block";
                defaultAvatarIcon.style.display = "none";
            } else {
                avatarImageElement.style.display = "none";
                defaultAvatarIcon.style.display = "block";
            }
        }
    }
    window.previewProfileAvatar = updateAvatarPreview;

    async function uploadAvatarFile(fileInputElement) {
        if (!fileInputElement.files || !fileInputElement.files[0]) return;
        
        const selectedFile = fileInputElement.files[0];
        const uploadFormData = new FormData();
        uploadFormData.append("avatar_file", selectedFile);

        const profileErrorBox = document.getElementById("profileError");
        const profileSuccessBox = document.getElementById("profileSuccess");
        if (profileErrorBox) profileErrorBox.style.display = "none";
        if (profileSuccessBox) profileSuccessBox.style.display = "none";

        try {
            const uploadResponse = await fetch("../routes/auth.php?action=upload_avatar", {
                method: "POST",
                body: uploadFormData
            });
            const uploadResult = await uploadResponse.json();
            
            if (uploadResult.success) {
                const profileAvatarInput = document.getElementById("profAvatar");
                if (profileAvatarInput) profileAvatarInput.value = uploadResult.avatar;
                updateAvatarPreview(uploadResult.avatar);
                if (profileSuccessBox) {
                    profileSuccessBox.textContent = "Tải ảnh lên thành công! Hãy bấm 'LƯU THAY ĐỔI' để hoàn tất.";
                    profileSuccessBox.style.display = "block";
                }
            } else {
                if (profileErrorBox) {
                    profileErrorBox.textContent = uploadResult.message || "Không thể tải ảnh lên.";
                    profileErrorBox.style.display = "block";
                }
            }
        } catch (error) {
            if (profileErrorBox) {
                profileErrorBox.textContent = "Lỗi kết nối khi tải ảnh lên.";
                profileErrorBox.style.display = "block";
            }
        }
    }
    window.uploadSelectedAvatar = uploadAvatarFile;

    async function fetchUserProfile() {
        try {
            const profileResponse = await fetch("../routes/auth.php?action=get_profile");
            const profileResult = await profileResponse.json();
            
            if (profileResult.success) {
                const userData = profileResult.data;
                rawEmailAddress = userData.email || "";
                rawPhoneNumber = userData.phone || "";
                
                const sidebarNameElement = document.getElementById("sidebarFullname");
                if (sidebarNameElement) sidebarNameElement.textContent = userData.fullname || "Thành viên";

                // Phân quyền giao diện nâng cao: Chỉ hiển thị thanh quản trị Admin nếu vai trò tài khoản là admin
                if (userData.role === "admin") {
                    const adminNavigationItem = document.getElementById("adminSidebarNavItem");
                    if (adminNavigationItem) adminNavigationItem.style.display = "block";
                    const adminSimulationControls = document.getElementById("adminSimControls");
                    if (adminSimulationControls) adminSimulationControls.style.display = "block";
                }

                if (document.getElementById("profFullname")) document.getElementById("profFullname").value = userData.fullname || "";
                if (document.getElementById("profPhone")) document.getElementById("profPhone").value = maskPhoneNumber(rawPhoneNumber);
                if (document.getElementById("profEmail")) document.getElementById("profEmail").value = maskEmailAddress(rawEmailAddress);
                if (document.getElementById("profAddress")) document.getElementById("profAddress").value = userData.address || "";
                if (document.getElementById("profAvatar")) document.getElementById("profAvatar").value = userData.avatar || "";
                
                if (document.getElementById("profBio")) document.getElementById("profBio").value = userData.bio || "";
                if (document.getElementById("profGender")) document.getElementById("profGender").value = userData.gender || "Nam";
                if (document.getElementById("profBirthday")) document.getElementById("profBirthday").value = userData.birthday || "";

                if (document.getElementById("cccd_fullname")) document.getElementById("cccd_fullname").value = userData.cccd_fullname || "";
                if (document.getElementById("cccd_number")) document.getElementById("cccd_number").value = userData.cccd_number || "";
                if (document.getElementById("cccd_address")) {
                    document.getElementById("cccd_address").value = userData.cccd_address || "";
                    const characterCounter = document.getElementById("cccd_char_count");
                    if (characterCounter) characterCounter.innerText = (userData.cccd_address || "").length + '/200';
                }

                if (document.getElementById("secPhone")) document.getElementById("secPhone").value = rawPhoneNumber;
                if (document.getElementById("secEmail")) document.getElementById("secEmail").value = rawEmailAddress;

                if (userData.avatar) {
                    updateAvatarPreview(userData.avatar);
                }

                window.currentProfileUserAddress = userData.address;
                window.currentProfileUserPhone = rawPhoneNumber;
                window.currentProfileUserName = userData.fullname;

                loadUserAddresses();
            }
        } catch (error) {
            console.error("Lỗi khi kết nối CSDL để lấy profile:", error);
        }
    }

    async function loadUserAddresses() {
        const addressListContainer = document.getElementById("addressListContainer");
        if (!addressListContainer) return;
        try {
            const response = await fetch("../routes/address.php?action=get_addresses");
            const result = await response.json();
            if (result.success && result.data && result.data.length > 0) {
                addressListContainer.innerHTML = "";
                result.data.forEach(addr => {
                    const formattedFullAddressString = `${addr.street_address}, ${addr.ward}, ${addr.district}, ${addr.city}`;
                    const card = document.createElement("div");
                    card.style.cssText = "background: #fff; border: 1px solid #e8e8e8; border-radius: 12px; padding: 20px; position: relative;";
                    card.innerHTML = `
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                            <div>
                                <span style="font-weight: bold; font-size: 16px; color: #222; margin-right: 10px;">${addr.recipient_name}</span>
                                <span style="color: #888; font-size: 14px;">${addr.recipient_phone}</span>
                            </div>
                            <div>
                                <button type="button" onclick="deleteUserAddress(${addr.id})" style="background: none; border: none; color: #de3b3b; cursor: pointer; font-size: 13px; margin-left: 10px;"><i class="fas fa-trash"></i> Xóa</button>
                            </div>
                        </div>
                        <p style="color: #555; font-size: 14.5px; margin-bottom: 12px; line-height: 1.5;">${formattedFullAddressString}</p>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            ${addr.is_default == 1 
                                ? '<span style="display: inline-block; background: #fff1f0; color: #BF8A49; border: 1px solid #fce8d5; font-size: 12px; padding: 2px 10px; border-radius: 4px; font-weight: 600;">Mặc định</span>' 
                                : `<button type="button" onclick="setDefaultUserAddress(${addr.id})" style="background: none; border: 1px solid #ccc; color: #555; padding: 3px 10px; border-radius: 4px; cursor: pointer; font-size: 12px;">Thiết lập mặc định</button>`}
                        </div>
                    `;
                    addressListContainer.appendChild(card);
                });
            } else {
                const defaultAddress = window.currentProfileUserAddress || "Chưa cập nhật địa chỉ giao hàng";
                const formattedPhone = window.currentProfileUserPhone || "Chưa cập nhật SĐT";
                const userName = window.currentProfileUserName || "Người dùng";
                addressListContainer.innerHTML = `
                    <div style="background: #fff; border: 1px solid #e8e8e8; border-radius: 12px; padding: 20px; position: relative;">
                        <div style="display: flex; gap: 12px; align-items: center; margin-bottom: 8px;">
                            <span style="font-weight: bold; font-size: 16px; color: #222;">${userName}</span>
                            <span style="color: #888; font-size: 14px;">(${formattedPhone})</span>
                        </div>
                        <p style="color: #555; font-size: 14.5px; margin-bottom: 12px; line-height: 1.5;">${defaultAddress}</p>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <span style="display: inline-block; background: #fff1f0; color: #BF8A49; border: 1px solid #fce8d5; font-size: 12px; padding: 2px 10px; border-radius: 4px; font-weight: 600;">Mặc định</span>
                        </div>
                    </div>
                `;
            }
        } catch (err) {
            console.error("Lỗi khi tải danh sách địa chỉ:", err);
        }
    }

    window.deleteUserAddress = async function(addressId) {
        if (!confirm("Bạn có chắc muốn xóa địa chỉ này?")) return;
        try {
            const res = await fetch("../routes/address.php?action=delete_address", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ address_id: addressId })
            });
            const data = await res.json();
            if (data.success) {
                loadUserAddresses();
            } else {
                alert(data.message || "Không thể xóa địa chỉ");
            }
        } catch(e) {
            alert("Lỗi kết nối khi xóa địa chỉ.");
        }
    };

    window.setDefaultUserAddress = async function(addressId) {
        try {
            const res = await fetch("../routes/address.php?action=set_default", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ address_id: addressId })
            });
            const data = await res.json();
            if (data.success) {
                loadUserAddresses();
            } else {
                alert(data.message || "Không thể đặt mặc định");
            }
        } catch(e) {
            alert("Lỗi kết nối.");
        }
    };

    window.loadUserAddresses = loadUserAddresses;
    window.loadProfile = fetchUserProfile;
    fetchUserProfile();

    async function deleteUserAccount() {
        // Hộp thoại xác nhận giúp bảo vệ người dùng khỏi việc xóa nhầm dữ liệu quan trọng không thể khôi phục
        if (!confirm("Bạn có chắc chắn muốn xóa vĩnh viễn tài khoản của mình? Hành động này sẽ xóa toàn bộ dữ liệu và không thể hoàn tác.")) {
            return;
        }
        try {
            const deletionResponse = await fetch("../routes/auth.php?action=delete_account", {
                method: "POST"
            });
            const deletionResult = await deletionResponse.json();
            
            if (deletionResult.success) {
                alert("Tài khoản của bạn đã được xóa thành công.");
                window.location.href = "login.php";
            } else {
                alert(deletionResult.message || "Không thể xóa tài khoản.");
            }
        } catch (error) {
            alert("Lỗi kết nối khi xóa tài khoản.");
        }
    }
    window.handleDeleteAccount = deleteUserAccount;

    async function saveIdentityCardInfo(submitEvent) {
        submitEvent.preventDefault();
        const cccdFullname = document.getElementById("cccd_fullname") ? document.getElementById("cccd_fullname").value : "";
        const cccdNumber = document.getElementById("cccd_number") ? document.getElementById("cccd_number").value : "";
        const cccdAddress = document.getElementById("cccd_address") ? document.getElementById("cccd_address").value : "";

        try {
            const saveResponse = await fetch("../routes/auth.php?action=update_cccd", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    cccd_fullname: cccdFullname,
                    cccd_number: cccdNumber,
                    cccd_address: cccdAddress
                })
            });
            const saveResult = await saveResponse.json();
            
            if (saveResult.success) {
                alert(saveResult.message);
            } else {
                alert(saveResult.message || "Lỗi cập nhật thông tin.");
            }
        } catch (error) {
            alert("Lỗi kết nối khi cập nhật thông tin.");
        }
    }
    window.handleSaveCccd = saveIdentityCardInfo;

    const profileFormElement = document.getElementById("profileForm");
    if (profileFormElement) {
        profileFormElement.addEventListener("submit", async (submitEvent) => {
            submitEvent.preventDefault();
            const profileErrorBox = document.getElementById("profileError");
            const profileSuccessBox = document.getElementById("profileSuccess");
            if (profileErrorBox) profileErrorBox.style.display = "none";
            if (profileSuccessBox) profileSuccessBox.style.display = "none";

            const fullname = document.getElementById("profFullname").value;
            const phone = rawPhoneNumber;
            const email = rawEmailAddress;
            const address = document.getElementById("profAddress").value;
            const avatar = document.getElementById("profAvatar").value;
            const bio = document.getElementById("profBio") ? document.getElementById("profBio").value : "";
            const gender = document.getElementById("profGender") ? document.getElementById("profGender").value : "Nam";
            const birthday = document.getElementById("profBirthday") ? document.getElementById("profBirthday").value : "";

            try {
                const updateResponse = await fetch("../routes/auth.php?action=update_profile", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ fullname, phone, email, address, avatar, bio, gender, birthday })
                });
                const updateResult = await updateResponse.json();
                
                if (updateResult.success) {
                    if (profileSuccessBox) {
                        profileSuccessBox.textContent = updateResult.message;
                        profileSuccessBox.style.display = "block";
                    }
                    const userNameSpan = document.querySelector(".account-user span");
                    if (userNameSpan) userNameSpan.textContent = fullname;
                    updateAvatarPreview(avatar);
                    fetchUserProfile();
                } else {
                    if (profileErrorBox) {
                        profileErrorBox.textContent = updateResult.message;
                        profileErrorBox.style.display = "block";
                    }
                }
            } catch (error) {
                if (profileErrorBox) {
                    profileErrorBox.textContent = "Lỗi kết nối máy chủ.";
                    profileErrorBox.style.display = "block";
                }
            }
        });
    }

    const securityContactFormElement = document.getElementById("securityContactForm");
    if (securityContactFormElement) {
        securityContactFormElement.addEventListener("submit", async (submitEvent) => {
            submitEvent.preventDefault();
            const securityErrorBox = document.getElementById("securityError");
            const securitySuccessBox = document.getElementById("securitySuccess");
            if (securityErrorBox) securityErrorBox.style.display = "none";
            if (securitySuccessBox) securitySuccessBox.style.display = "none";

            const newPhoneNumber = document.getElementById("secPhone").value;
            const newEmailAddress = document.getElementById("secEmail").value;
            const verificationPassword = document.getElementById("secContactPassword").value;

            try {
                const passwordVerifyResponse = await fetch("../routes/auth.php?action=verify_password", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ password: verificationPassword })
                });
                const passwordVerifyResult = await passwordVerifyResponse.json();

                if (!passwordVerifyResult.success) {
                    if (securityErrorBox) {
                        securityErrorBox.textContent = passwordVerifyResult.message || "Mật khẩu xác nhận không đúng.";
                        securityErrorBox.style.display = "block";
                    }
                    return;
                }

                const fullname = document.getElementById("profFullname").value;
                const address = document.getElementById("profAddress").value;
                const avatar = document.getElementById("profAvatar").value;

                const updateContactResponse = await fetch("../routes/auth.php?action=update_profile", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ fullname, phone: newPhoneNumber, email: newEmailAddress, address, avatar })
                });
                const updateContactResult = await updateContactResponse.json();

                if (updateContactResult.success) {
                    if (securitySuccessBox) {
                        securitySuccessBox.textContent = "Cập nhật Email và Số điện thoại thành công!";
                        securitySuccessBox.style.display = "block";
                    }
                    document.getElementById("secContactPassword").value = "";
                    fetchUserProfile();
                } else {
                    if (securityErrorBox) {
                        securityErrorBox.textContent = updateContactResult.message || "Cập nhật thất bại.";
                        securityErrorBox.style.display = "block";
                    }
                }
            } catch (error) {
                if (securityErrorBox) {
                    securityErrorBox.textContent = "Đã xảy ra lỗi kết nối.";
                    securityErrorBox.style.display = "block";
                }
            }
        });
    }

    const securityFormElement = document.getElementById("securityForm");
    if (securityFormElement) {
        securityFormElement.addEventListener("submit", async (submitEvent) => {
            submitEvent.preventDefault();
            const securityErrorBox = document.getElementById("securityError");
            const securitySuccessBox = document.getElementById("securitySuccess");
            if (securityErrorBox) securityErrorBox.style.display = "none";
            if (securitySuccessBox) securitySuccessBox.style.display = "none";

            const currentPassword = document.getElementById("secCurrentPassword").value;
            const newPassword = document.getElementById("secNewPassword").value;
            const confirmPassword = document.getElementById("secConfirmPassword").value;

            if (newPassword !== confirmPassword) {
                if (securityErrorBox) {
                    securityErrorBox.textContent = "Mật khẩu xác nhận mới không khớp.";
                    securityErrorBox.style.display = "block";
                }
                return;
            }

            try {
                const changePasswordResponse = await fetch("../routes/auth.php?action=change_password", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ current_password: currentPassword, new_password: newPassword })
                });
                const changePasswordResult = await changePasswordResponse.json();
                
                if (changePasswordResult.success) {
                    if (securitySuccessBox) {
                        securitySuccessBox.textContent = changePasswordResult.message;
                        securitySuccessBox.style.display = "block";
                    }
                    document.getElementById("secCurrentPassword").value = "";
                    document.getElementById("secNewPassword").value = "";
                    document.getElementById("secConfirmPassword").value = "";
                } else {
                    if (securityErrorBox) {
                        securityErrorBox.textContent = changePasswordResult.message;
                        securityErrorBox.style.display = "block";
                    }
                }
            } catch (error) {
                if (securityErrorBox) {
                    securityErrorBox.textContent = "Đã xảy ra lỗi kết nối khi đổi mật khẩu.";
                    securityErrorBox.style.display = "block";
                }
            }
        });
    }

    const logoutButton = document.getElementById("logoutBtn");
    if (logoutButton) {
        logoutButton.addEventListener("click", async () => {
            const logoutResponse = await fetch("../routes/auth.php?action=logout");
            const logoutResult = await logoutResponse.json();
            if (logoutResult.success) {
                window.location.href = "login.php";
            }
        });
    }

    let loadedWishlistItemsList = [];
    async function fetchWishlistItems() {
        const wishlistGridElement = document.getElementById("wishlistGrid");
        if (!wishlistGridElement) return;

        try {
            const wishlistResponse = await fetch("../routes/wishlist.php?action=get_wishlist");
            const wishlistResult = await wishlistResponse.json();
            
            if (wishlistResult.success) {
                loadedWishlistItemsList = wishlistResult.data;
                if (loadedWishlistItemsList.length === 0) {
                    wishlistGridElement.innerHTML = `
                        <div style="grid-column: 1/-1; text-align: center; padding: 40px; color: var(--gray);">
                            <i class="fa-regular fa-heart" style="font-size: 50px; color: #ccc; margin-bottom: 15px;"></i>
                            <p>Danh sách sản phẩm yêu thích của bạn đang trống.</p>
                            <a href="cartegory.php" style="color: var(--primary); font-weight: bold; margin-top: 15px; display: inline-block;">Khám phá sản phẩm ngay</a>
                        </div>`;
                    return;
                }

                wishlistGridElement.innerHTML = loadedWishlistItemsList.map(item => {
                    const imageUrl = item.image.startsWith('..') ? item.image : '../assets/images/' + item.image;
                    const safeProductName = item.product_name.replace(/'/g, "\\'");
                    return `
                        <div class="wish-item" data-id="${item.product_id}">
                            <div class="wish-image">
                                <img src="${imageUrl}" alt="${item.product_name}">
                                <a href="javascript:void(0)" onclick="removeWishlistItem(${item.product_id})" class="heart-btn">
                                    <i class="fa-solid fa-heart"></i>
                                </a>
                            </div>
                            <h3>${item.product_name}</h3>
                            <p class="product-price">${parseFloat(item.price).toLocaleString('vi-VN')}đ</p>
                            <a href="javascript:void(0)" onclick="addWishlistItemToCart(${item.product_id}, '${safeProductName}', ${item.price}, '${imageUrl}')" class="addCart-btn">
                                Thêm vào giỏ hàng
                            </a>
                            <a href="javascript:void(0)" onclick="removeWishlistItem(${item.product_id})" class="delete-btn">
                                Xóa khỏi yêu thích
                            </a>
                        </div>
                    `;
                }).join('');
            } else {
                wishlistGridElement.innerHTML = `<p style="color: red; grid-column: 1/-1;">Lỗi tải dữ liệu: ${wishlistResult.message}</p>`;
            }
        } catch (error) {
            wishlistGridElement.innerHTML = `<p style="color: red; grid-column: 1/-1;">Lỗi kết nối máy chủ.</p>`;
        }
    }
    window.loadWishlist = fetchWishlistItems;

    async function removeWishlistItem(productId) {
        if (!confirm("Bạn muốn bỏ sản phẩm này khỏi yêu thích?")) return;
        try {
            const removalResponse = await fetch("../routes/wishlist.php?action=remove_from_wishlist", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ product_id: productId })
            });
            const removalResult = await removalResponse.json();
            if (removalResult.success) {
                fetchWishlistItems();
            } else {
                alert(removalResult.message);
            }
        } catch (error) {
            alert("Lỗi kết nối.");
        }
    }
    // Gán đủ alias để onclick trong HTML động tìm thấy hàm
    window.removeWishlistItem = removeWishlistItem;
    window.removeFromWishlist = removeWishlistItem;
    window.removeWishlistItemFromPage = removeWishlistItem;


    window.addWishlistItemToCart = (productId, productName, productPrice, productImage) => {
        let matchedProductItem = loadedWishlistItemsList.find(item => parseInt(item.product_id) === parseInt(productId));
        
        const name = productName || (matchedProductItem ? matchedProductItem.product_name : 'Sản phẩm yêu thích');
        const price = productPrice || (matchedProductItem ? parseFloat(matchedProductItem.price) : 790000);
        const image = productImage || (matchedProductItem ? (matchedProductItem.image.startsWith('..') ? matchedProductItem.image : '../assets/images/' + matchedProductItem.image) : '../assets/images/sp1.jpg');

        const selectedProductPayload = {
            product_id: parseInt(productId),
            name: name,
            price: parseFloat(price),
            quantity: 1,
            color: 'Hồng Pastel',
            size: 'M',
            image: image
        };

        let localCartArray = JSON.parse(localStorage.getItem('the_fox_cart')) || [];
        const existingItemIndex = localCartArray.findIndex(item => item.name === selectedProductPayload.name && item.color === selectedProductPayload.color && item.size === selectedProductPayload.size);
        
        if (existingItemIndex > -1) {
            localCartArray[existingItemIndex].quantity += 1;
        } else {
            localCartArray.push(selectedProductPayload);
        }

        localStorage.setItem('the_fox_cart', JSON.stringify(localCartArray));

        if (typeof loadAndRenderSidebarCart === 'function') {
            loadAndRenderSidebarCart();
        }

        const sidebarCartPanel = document.querySelector('.cart-sidebar');
        const sidebarCartOverlay = document.querySelector('.cart-overlay');
        if (sidebarCartPanel && sidebarCartOverlay) {
            sidebarCartPanel.classList.add('active');
            sidebarCartOverlay.classList.add('active');
        }
    };
    window.addWishToCart = window.addWishlistItemToCart;

    async function fetchSavedOutfits() {
        const outfitsListElement = document.getElementById("outfitsList");
        if (!outfitsListElement) return;

        try {
            const outfitsResponse = await fetch("../routes/outfit.php?action=get_outfits");
            const outfitsResult = await outfitsResponse.json();
            
            if (outfitsResult.success) {
                if (outfitsResult.data.length === 0) {
                    outfitsListElement.innerHTML = `<div style="width:100%; text-align: center; padding: 40px; color: var(--gray);">
                        <i class="fas fa-magic" style="font-size: 40px; margin-bottom: 10px; color: var(--light);"></i>
                        <p>Bạn chưa lưu bộ phối đồ nào.</p>
                        <a href="outfit-builder.php" style="color: var(--primary); font-weight: bold; display: inline-block; margin-top: 10px;">Tạo phối đồ ngay</a>
                    </div>`;
                    return;
                }

                outfitsListElement.innerHTML = outfitsResult.data.map(outfit => `
                    <div style="background:#fff; border:1px solid #eee; border-radius:8px; padding:15px; width:calc(33.33% - 14px); min-width:260px; transition:0.3s; box-shadow:0 2px 6px rgba(0,0,0,0.03); display:flex; flex-direction:column; justify-content:space-between;">
                        <div>
                            <h4 style="font-size:16px; font-weight:bold; color:var(--dark); margin-bottom:8px;">${outfit.name}</h4>
                            <p style="font-size:13px; color:var(--gray); margin-bottom:12px; min-height:36px; overflow:hidden;">${outfit.description || 'Không có mô tả'}</p>
                            <span style="font-size:12px; color:#aaa;"><i class="far fa-calendar-alt"></i> ${outfit.created_at}</span>
                        </div>
                        <div style="margin-top:15px; display:flex; gap:10px;">
                            <button onclick="displayOutfitDetails(${outfit.id})" class="btn-premium" style="height:34px; font-size:12px; flex:1;"><i class="far fa-eye"></i> Xem phối đồ</button>
                            <button onclick="deleteSavedOutfit(${outfit.id})" class="btn-secondary" style="padding:0 12px; height:34px; color:#de3b3b;"><i class="fas fa-trash-alt"></i></button>
                        </div>
                    </div>
                `).join('');
            } else {
                outfitsListElement.innerHTML = `<p style="color:red; width:100%;">Lỗi: ${outfitsResult.message}</p>`;
            }
        } catch (error) {
            outfitsListElement.innerHTML = `<p style="color:red; width:100%;">Lỗi hệ thống.</p>`;
        }
    }
    window.loadOutfits = fetchSavedOutfits;

    let currentPreviewOutfitItems = [];
    async function displayOutfitDetails(outfitId) {
        try {
            const detailsResponse = await fetch(`../routes/outfit.php?action=get_outfit_details&outfit_id=${outfitId}`);
            const detailsResult = await detailsResponse.json();
            
            if (detailsResult.success) {
                const outfitData = detailsResult.data;
                currentPreviewOutfitItems = outfitData.items;

                const outfitNameHeading = document.getElementById("previewOutfitName");
                if (outfitNameHeading) outfitNameHeading.textContent = outfitData.name;
                
                let totalOutfitPrice = 0;
                const outfitItemsContainer = document.getElementById("previewOutfitItems");
                
                if (outfitItemsContainer) {
                    outfitItemsContainer.innerHTML = outfitData.items.map(item => {
                        totalOutfitPrice += parseFloat(item.price);
                        const itemImg = item.image.startsWith('..') ? item.image : '../assets/images/' + item.image;
                        return `
                            <div style="text-align:center; width:30%; min-width:130px; border:1px solid #eee; padding:10px; border-radius:6px; background:#fff;">
                                <img src="${itemImg}" alt="${item.product_name}" style="width:100%; height:120px; object-fit:cover; border-radius:4px; margin-bottom:5px;">
                                <p style="font-size:12px; font-weight:bold; overflow:hidden; white-space:nowrap; text-overflow:ellipsis; margin-bottom:3px;">${item.product_name}</p>
                                <p style="color:var(--primary); font-size:13px; font-weight:bold;">${parseFloat(item.price).toLocaleString('vi-VN')}đ</p>
                            </div>
                        `;
                    }).join('');
                }

                const outfitTotalElement = document.getElementById("previewOutfitTotal");
                if (outfitTotalElement) outfitTotalElement.textContent = "Tổng giá trị: " + totalOutfitPrice.toLocaleString('vi-VN') + "đ";

                const previewModal = document.getElementById("outfitPreviewModal");
                if (previewModal) previewModal.classList.add("active");
            } else {
                alert(detailsResult.message);
            }
        } catch (error) {
            alert("Lỗi tải chi tiết bộ phối đồ.");
        }
    }
    window.displayOutfitDetails = displayOutfitDetails;
    window.previewOutfit = displayOutfitDetails;

    window.closeOutfitPreviewModal = function() {
        const previewModal = document.getElementById("outfitPreviewModal");
        if (previewModal) previewModal.classList.remove("active");
    };

    const addOutfitToCartButton = document.getElementById("addOutfitToCartBtn");
    if (addOutfitToCartButton) {
        addOutfitToCartButton.addEventListener("click", () => {
            if (!currentPreviewOutfitItems || currentPreviewOutfitItems.length === 0) return;
            
            let localCartArray = JSON.parse(localStorage.getItem('the_fox_cart')) || [];

            currentPreviewOutfitItems.forEach(item => {
                const itemImg = item.image.startsWith('..') ? item.image : '../assets/images/' + item.image;
                const payload = {
                    product_id: parseInt(item.product_id),
                    name: item.product_name,
                    price: parseFloat(item.price),
                    quantity: 1,
                    color: 'Hồng Pastel',
                    size: 'M',
                    image: itemImg
                };

                const existingIndex = localCartArray.findIndex(cartItem => cartItem.name === payload.name && cartItem.color === payload.color && cartItem.size === payload.size);
                if (existingIndex > -1) {
                    localCartArray[existingIndex].quantity += 1;
                } else {
                    localCartArray.push(payload);
                }
            });

            localStorage.setItem('the_fox_cart', JSON.stringify(localCartArray));

            alert(`Đã thêm thành công ${currentPreviewOutfitItems.length} sản phẩm của bộ phối đồ vào giỏ hàng!`);
            closeOutfitPreviewModal();

            if (typeof loadAndRenderSidebarCart === 'function') {
                loadAndRenderSidebarCart();
            }
            const sidebarCartPanel = document.querySelector('.cart-sidebar');
            const sidebarCartOverlay = document.querySelector('.cart-overlay');
            if (sidebarCartPanel && sidebarCartOverlay) {
                sidebarCartPanel.classList.add('active');
                sidebarCartOverlay.classList.add('active');
            }
        });
    }

    async function deleteSavedOutfit(outfitId) {
        if (!confirm("Bạn có chắc chắn muốn xóa bộ phối đồ này?")) return;
        try {
            const deletionResponse = await fetch("../routes/outfit.php?action=delete_outfit", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ outfit_id: outfitId })
            });
            const deletionResult = await deletionResponse.json();
            if (deletionResult.success) {
                fetchSavedOutfits();
            } else {
                alert(deletionResult.message);
            }
        } catch (error) {
            alert("Lỗi kết nối.");
        }
    }
    window.deleteSavedOutfit = deleteSavedOutfit;
    window.deleteOutfit = deleteSavedOutfit;
});
