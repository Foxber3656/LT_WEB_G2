/* ==========================================================================
   THE FOX - Module Đăng Ký Tài Khoản (User Registration JS)
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
    const registrationFormElement = document.getElementById("registerForm");
    const errorAlertElement = document.getElementById("errorAlert");
    const successAlertElement = document.getElementById("successAlert");

    if (registrationFormElement) {
        registrationFormElement.addEventListener("submit", async (submitEvent) => {
            submitEvent.preventDefault();
            
            // Xóa thông báo lỗi cũ để đảm bảo phản hồi giao diện chính xác cho lượt gửi mới
            if (errorAlertElement) errorAlertElement.style.display = "none";
            if (successAlertElement) successAlertElement.style.display = "none";

            const fullname = document.getElementById("fullname").value;
            const emailAddress = document.getElementById("email").value;
            const phoneNumber = document.getElementById("phone").value;
            const enteredPassword = document.getElementById("password").value;
            const confirmedPassword = document.getElementById("confirmPassword").value;

            // Xác thực Client-side nhanh để tránh gửi request không cần thiết lên máy chủ khi mật khẩu nhập lại bị sai
            if (enteredPassword !== confirmedPassword) {
                if (errorAlertElement) {
                    errorAlertElement.textContent = "Mật khẩu xác nhận không khớp.";
                    errorAlertElement.style.display = "block";
                }
                return;
            }

            try {
                const registrationResponse = await fetch("../routes/auth.php?action=register", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ 
                        fullname, 
                        email: emailAddress, 
                        phone: phoneNumber, 
                        password: enteredPassword 
                    })
                });

                const registrationResult = await registrationResponse.json();

                if (registrationResult.success) {
                    if (successAlertElement) {
                        successAlertElement.textContent = registrationResult.message + " Đang chuyển hướng đăng nhập...";
                        successAlertElement.style.display = "block";
                    }
                    
                    // Trì hoãn chuyển hướng 1.5 giây để người dùng đọc kịp thông báo đăng ký thành công
                    setTimeout(() => {
                        window.location.href = "profile.php";
                    }, 1500);
                } else {
                    if (errorAlertElement) {
                        errorAlertElement.textContent = registrationResult.message;
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
