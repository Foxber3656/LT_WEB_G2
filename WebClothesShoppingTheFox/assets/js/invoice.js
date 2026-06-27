/* ==========================================================================
   THE FOX - Module Giao Diện Hóa Đơn Đơn Hàng (Invoice JS)
   Áp dụng chuẩn thiết kế phần mềm Clean Code & Senior Developer
   Tên biến/hàm: Tiếng Anh chuẩn | Chú thích (Comments): Tiếng Việt chuyên nghiệp
   ========================================================================== */

document.addEventListener('DOMContentLoaded', () => {
    const urlSearchParameters = new URLSearchParams(window.location.search);
    const orderCodeFromUrl = urlSearchParameters.get('order_code');
    
    let cachedOrderDetailsData = JSON.parse(sessionStorage.getItem('last_order'));
    
    // Nếu không tìm thấy dữ liệu đơn hàng trong Session, thông báo và hiển thị liên kết quay về cửa hàng
    if (!cachedOrderDetailsData) {
        const mainContentContainer = document.querySelector('main');
        if (mainContentContainer) {
            mainContentContainer.innerHTML = `
                <div class="container" style="max-width: 600px; text-align: center; padding: 80px 0;">
                    <h2>Không tìm thấy thông tin đơn hàng</h2>
                    <a href="cartegory.php" style="color: var(--primary);">Quay lại cửa hàng</a>
                </div>
            `;
        }
        return;
    }
    
    const orderCodeDisplayElement = document.getElementById('order-code-display');
    const customerNameDisplayElement = document.getElementById('info-name');
    const customerPhoneDisplayElement = document.getElementById('info-phone');
    const customerEmailDisplayElement = document.getElementById('info-email');
    const customerAddressDisplayElement = document.getElementById('info-address');
    const shippingMethodDisplayElement = document.getElementById('info-shipping');
    const paymentMethodDisplayElement = document.getElementById('info-payment');
    
    const subtotalDisplayElement = document.getElementById('bill-subtotal');
    const shippingFeeDisplayElement = document.getElementById('bill-shipping');
    const discountDisplayElement = document.getElementById('bill-discount');
    const finalTotalDisplayElement = document.getElementById('bill-total');

    if (orderCodeDisplayElement) orderCodeDisplayElement.innerText = cachedOrderDetailsData.order_code;
    if (customerNameDisplayElement) customerNameDisplayElement.innerText = cachedOrderDetailsData.fullname;
    if (customerPhoneDisplayElement) customerPhoneDisplayElement.innerText = cachedOrderDetailsData.phone;
    if (customerEmailDisplayElement) customerEmailDisplayElement.innerText = cachedOrderDetailsData.email;
    if (customerAddressDisplayElement) customerAddressDisplayElement.innerText = cachedOrderDetailsData.address;
    if (shippingMethodDisplayElement) shippingMethodDisplayElement.innerText = `${cachedOrderDetailsData.shipping_method} (+${formatCurrencyPrice(cachedOrderDetailsData.shipping_fee)}đ)`;
    if (paymentMethodDisplayElement) paymentMethodDisplayElement.innerText = cachedOrderDetailsData.payment_method;
    
    if (subtotalDisplayElement) subtotalDisplayElement.innerText = formatCurrencyPrice(cachedOrderDetailsData.subtotal) + 'đ';
    if (shippingFeeDisplayElement) shippingFeeDisplayElement.innerText = '+' + formatCurrencyPrice(cachedOrderDetailsData.shipping_fee) + 'đ';
    if (discountDisplayElement) discountDisplayElement.innerText = '-' + formatCurrencyPrice(cachedOrderDetailsData.discount) + 'đ';
    if (finalTotalDisplayElement) finalTotalDisplayElement.innerText = formatCurrencyPrice(cachedOrderDetailsData.final_total) + 'đ';
    
    const paymentInstructionSectionElement = document.getElementById('payment-instruction-section');
    if (paymentInstructionSectionElement) {
        if (cachedOrderDetailsData.payment_method === 'Chuyển khoản') {
            const transferAmount = cachedOrderDetailsData.final_total;
            const encodedOrderCodeInfo = encodeURIComponent(cachedOrderDetailsData.order_code);
            const vietQrApiImageUrl = `https://api.vietqr.io/image/970415-0011004123456-compact.jpg?amount=${transferAmount}&addInfo=${encodedOrderCodeInfo}&accountName=THE%20FOX%20SHOP`;
            
            paymentInstructionSectionElement.innerHTML = `
                <h3 style="font-size: 16px; margin: 0 0 15px 0; color: #2196f3; text-transform: uppercase;">Hướng dẫn chuyển khoản qua mã VietQR</h3>
                <div style="display: flex; gap: 20px; align-items: center; justify-content: center; flex-wrap: wrap;">
                    <div style="border: 1px solid #ccc; padding: 10px; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <img src="${vietQrApiImageUrl}" alt="VietQR Vietcombank" style="width: 220px; height: auto; display: block;">
                    </div>
                    <div style="text-align: left; max-width: 320px;">
                        <p style="margin: 0 0 10px 0; font-size: 13px; color: #666;">Mở ứng dụng Mobile Banking của ngân hàng bạn đang dùng, chọn tính năng quét QR và thực hiện thanh toán tự động.</p>
                        <p style="margin: 0 0 6px 0; font-size: 14px;"><strong>Ngân hàng:</strong> Vietcombank (VCB)</p>
                        <p style="margin: 0 0 6px 0; font-size: 14px;"><strong>Số tài khoản:</strong> 0011004123456</p>
                        <p style="margin: 0 0 6px 0; font-size: 14px;"><strong>Tên tài khoản:</strong> THE FOX SHOP</p>
                        <p style="margin: 0 0 6px 0; font-size: 14px;"><strong>Số tiền:</strong> <strong style="color: var(--primary);">${formatCurrencyPrice(transferAmount)}đ</strong></p>
                        <p style="margin: 0 0 6px 0; font-size: 14px;"><strong>Nội dung:</strong> <strong style="color: #2196f3;">${cachedOrderDetailsData.order_code}</strong></p>
                    </div>
                </div>
            `;
        } else if (cachedOrderDetailsData.payment_method === 'MoMo') {
            paymentInstructionSectionElement.innerHTML = `
                <h3 style="font-size: 16px; margin: 0 0 12px 0; color: #d81b60; text-transform: uppercase;">Thanh toán qua Ví điện tử MoMo</h3>
                <div style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
                    <i class="fas fa-wallet" style="font-size: 40px; color: #d81b60;"></i>
                    <p style="margin: 0; font-size: 14px; max-width: 450px; line-height: 1.5;">Vui lòng chuyển khoản đúng số tiền <strong style="color: var(--primary);">${formatCurrencyPrice(cachedOrderDetailsData.final_total)}đ</strong> vào số điện thoại ví MoMo <strong>0912345678</strong> (THE FOX) với nội dung chuyển tiền: <strong>${cachedOrderDetailsData.order_code}</strong>.</p>
                </div>
            `;
        } else {
            paymentInstructionSectionElement.innerHTML = `
                <h3 style="font-size: 16px; margin: 0 0 12px 0; color: #4caf50; text-transform: uppercase;">Thanh toán khi nhận hàng (COD)</h3>
                <div style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
                    <i class="fas fa-truck-loading" style="font-size: 40px; color: #4caf50;"></i>
                    <p style="margin: 0; font-size: 14px; max-width: 450px; line-height: 1.5;">Bạn sẽ thanh toán số tiền <strong style="color: var(--primary);">${formatCurrencyPrice(cachedOrderDetailsData.final_total)}đ</strong> bằng tiền mặt cho nhân viên giao hàng khi nhận được kiện hàng.</p>
                </div>
            `;
        }
    }
    
    function formatCurrencyPrice(amountNumber) {
        return (amountNumber || 0).toLocaleString('vi-VN');
    }
});
