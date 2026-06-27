/* ==========================================================================
   THE FOX - Module Đăng Nhập Hệ Thống (User Login JS)
   Áp dụng chuẩn thiết kế phần mềm Clean Code & Senior Developer
   Tên biến/hàm: Tiếng Anh chuẩn | Chú thích (Comments): Tiếng Việt chuyên nghiệp
   ========================================================================== */

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

document.addEventListener("DOMContentLoaded", () => {
    const loginFormElement = document.getElementById("loginForm");
    const errorAlertElement = document.getElementById("errorAlert");
    const successAlertElement = document.getElementById("successAlert");

    if (loginFormElement) {
        loginFormElement.addEventListener("submit", async (submitEvent) => {
            submitEvent.preventDefault();
            
            // Xóa thông báo lỗi cũ trước khi thực hiện xác thực mới
            if (errorAlertElement) errorAlertElement.style.display = "none";
            if (successAlertElement) successAlertElement.style.display = "none";

            const emailAddress = document.getElementById("email").value;
            const enteredPassword = document.getElementById("password").value;

            try {
                const loginResponse = await fetch("../routes/auth.php?action=login", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ email: emailAddress, password: enteredPassword })
                });

                const loginResult = await loginResponse.json();

                if (loginResult.success) {
                    if (successAlertElement) {
                        successAlertElement.textContent = loginResult.message + " Đang chuyển hướng...";
                        successAlertElement.style.display = "block";
                    }
                    
                    // Phân hướng thông minh: Tự động đưa Admin về trang quản trị admin.php hoặc người dùng về trang cá nhân profile.php
                    setTimeout(() => {
                        if (loginResult.user && loginResult.user.role === 'admin') {
                            window.location.href = "admin.php";
                        } else {
                            window.location.href = "profile.php";
                        }
                    }, 1500);
                } else {
                    if (errorAlertElement) {
                        errorAlertElement.textContent = loginResult.message;
                        errorAlertElement.style.display = "block";
                    }
                }
            } catch (error) {
                if (errorAlertElement) {
                    errorAlertElement.textContent = "Đã xảy ra lỗi kết nối. Vui lòng thử lại sau.";
                    errorAlertElement.style.display = "block";
                }
            }
        });
    }
});

/* ======================================================
   XỬ LÝ ĐĂNG NHẬP MẠNG XÃ HỘI (SOCIAL LOGIN HANDLER)
   Google & Apple — Hiển thị thông báo tích hợp đang phát triển
   Chuẩn bị sẵn hook để tích hợp OAuth2 thực tế trong tương lai
====================================================== */
window.handleSocialLoginNotice = function(providerName) {
    // Xóa popup cũ nếu đang hiển thị
    const existingPopup = document.getElementById('socialLoginPopup');
    if (existingPopup) existingPopup.remove();

    const isGoogle = providerName === 'Google';
    
    const popupElement = document.createElement('div');
    popupElement.id = 'socialLoginPopup';
    popupElement.style.cssText = `
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.55);
        backdrop-filter: blur(6px);
        z-index: 999999;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: fadeInSocial 0.25s ease forwards;
        padding: 20px;
        box-sizing: border-box;
    `;

    const iconSvg = isGoogle
        ? `<svg width="40" height="40" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>`
        : `<svg width="40" height="40" viewBox="0 0 24 24" fill="#fff"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/></svg>`;

    const bgIcon = isGoogle ? '#fff' : '#000';
    const providerColor = isGoogle ? '#4285F4' : '#000';

    popupElement.innerHTML = `
        <div style="background:#fff; border-radius:20px; padding:36px 32px; max-width:380px; width:100%; text-align:center; box-shadow:0 20px 60px rgba(0,0,0,0.25); animation:slideUpSocial 0.3s cubic-bezier(0.16,1,0.3,1) forwards; position:relative;">
            <button onclick="document.getElementById('socialLoginPopup').remove()" style="position:absolute;top:14px;right:18px;background:none;border:none;font-size:22px;color:#aaa;cursor:pointer;line-height:1;">&times;</button>
            <div style="width:70px;height:70px;border-radius:50%;background:${bgIcon};border:2px solid #f0f0f0;display:flex;align-items:center;justify-content:center;margin:0 auto 18px;">
                ${iconSvg}
            </div>
            <h3 style="font-size:19px;font-weight:700;color:#221f20;margin-bottom:8px;">Đăng nhập bằng ${providerName}</h3>
            <div style="display:inline-block;background:#fff8f0;border:1px solid #f0d9b8;border-radius:8px;padding:6px 14px;margin-bottom:16px;">
                <span style="font-size:12px;font-weight:700;color:#BF8A49;letter-spacing:0.5px;">SẮP RA MẮT</span>
            </div>
            <p style="font-size:14px;color:#666;line-height:1.6;margin-bottom:24px;">
                Tính năng đăng nhập bằng <strong>${providerName}</strong> đang được phát triển và sẽ ra mắt sớm. Vui lòng dùng email & mật khẩu để tiếp tục.
            </p>
            <button onclick="document.getElementById('socialLoginPopup').remove()" style="width:100%;height:48px;background:#221f20;color:#fff;border:none;border-radius:10px;font-size:14px;font-weight:700;cursor:pointer;transition:background 0.2s;letter-spacing:0.5px;" onmouseover="this.style.background='#BF8A49'" onmouseout="this.style.background='#221f20'">
                Dùng Email & Mật khẩu
            </button>
        </div>
        <style>
            @keyframes fadeInSocial { from { opacity:0; } to { opacity:1; } }
            @keyframes slideUpSocial { from { transform:translateY(24px);opacity:0; } to { transform:translateY(0);opacity:1; } }
        </style>
    `;

    document.body.appendChild(popupElement);

    // Đóng popup khi click ra nền mờ
    popupElement.addEventListener('click', (e) => {
        if (e.target === popupElement) popupElement.remove();
    });
};
