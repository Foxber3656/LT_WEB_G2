/* ==========================================================================
   THE FOX - Module Quản Lý Địa Chỉ Nhận Hàng Cá Nhân (Address Profile JS)
   Áp dụng chuẩn thiết kế phần mềm Clean Code & Senior Developer
   Tên biến/hàm: Tiếng Anh chuẩn | Chú thích (Comments): Tiếng Việt chuyên nghiệp
   ========================================================================== */

function openAddAddressModal() {
    const addressModalElement = document.getElementById('addAddressModal');
    if (addressModalElement) addressModalElement.style.display = 'flex';
}

function closeAddAddressModal() {
    const addressModalElement = document.getElementById('addAddressModal');
    if (addressModalElement) addressModalElement.style.display = 'none';
}

async function handleSaveAddress(submitEvent) {
    submitEvent.preventDefault();
    const addressFormElement = document.getElementById('addAddressForm');
    if (!addressFormElement) return;

    const addressFormData = new FormData(addressFormElement);
    const requestPayloadData = Object.fromEntries(addressFormData.entries());

    try {
        const apiResponse = await fetch('../routes/address.php?action=add_address', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(requestPayloadData),
        });

        const apiResultData = await apiResponse.json();
        if (apiResultData.success) {
            alert('Thêm địa chỉ mới thành công!');
            addressFormElement.reset();
            closeAddAddressModal();
            if (typeof window.loadUserAddresses === 'function') {
                window.loadUserAddresses();
            } else if (typeof loadProfile === 'function') {
                loadProfile();
            } else {
                location.reload();
            }
        } else {
            alert('Lỗi lưu CSDL: ' + (apiResultData.message || 'Không thể lưu địa chỉ'));
        }
    } catch (connectionError) {
        console.error('Lỗi gửi dữ liệu địa chỉ:', connectionError);
        alert('Lỗi kết nối máy chủ khi lưu địa chỉ: ' + connectionError.message);
    }
}
