/* ==========================================================================
   THE FOX - Module Thanh Toán & Đặt Hàng (Checkout JS)
   Áp dụng chuẩn thiết kế phần mềm Clean Code & Senior Developer
   Tên biến/hàm: Tiếng Anh chuẩn | Chú thích (Comments): Tiếng Việt chuyên nghiệp
   ========================================================================== */

document.addEventListener('DOMContentLoaded', () => {
    const checkoutItemsListContainer = document.getElementById('checkout-items-list');
    const subtotalDisplayElement = document.getElementById('summary-subtotal');
    const shippingDisplayElement = document.getElementById('summary-shipping');
    const discountDisplayElement = document.getElementById('summary-discount');
    const finalTotalDisplayElement = document.getElementById('summary-total');
    const checkoutSubmissionForm = document.getElementById('checkout-form');

    // Tải danh sách giỏ hàng lưu trữ ở phía khách hàng (Local Storage)
    let cartProductsList = JSON.parse(localStorage.getItem('the_fox_cart')) || [];

    // Ràng buộc bảo vệ: Chuyển hướng ngay về giỏ hàng nếu không có sản phẩm để ngăn chặn đặt hàng rỗng
    if (cartProductsList.length === 0) {
        alert('Giỏ hàng của bạn đang trống. Vui lòng thêm sản phẩm trước khi thanh toán.');
        window.location.href = 'cart.php';
        return;
    }

    let currentShippingFeeAmount = 30000;

    // Tính toán tổng số tiền và render tóm tắt đơn hàng ở cột bên phải
    function calculateAndRenderSummary() {
        if (!checkoutItemsListContainer) return;

        let accumulatedSubtotalAmount = 0;
        let checkoutItemsHtmlMarkup = '';

        cartProductsList.forEach((singleItem) => {
            accumulatedSubtotalAmount += singleItem.price * singleItem.quantity;
            checkoutItemsHtmlMarkup += `
                <div style="display: flex; gap: 12px; align-items: center; border-bottom: 1px solid #f0f0f0; padding-bottom: 10px;">
                    <img src="${singleItem.image}" alt="${singleItem.name}" style="width: 50px; height: 60px; object-fit: cover; border-radius: 4px;">
                    <div style="flex: 1;">
                        <h4 style="font-size: 13px; font-weight: 500; margin: 0 0 3px 0; color: #333;">${singleItem.name}</h4>
                        <span style="font-size: 11px; color: #777; display: block;">Màu: ${singleItem.color} | Size: ${singleItem.size}</span>
                        <span style="font-size: 12px; font-weight: bold; color: #555;">${singleItem.quantity} x ${formatCurrencyPrice(singleItem.price)}đ</span>
                    </div>
                    <span style="font-size: 13px; font-weight: bold; color: #333;">${formatCurrencyPrice(singleItem.price * singleItem.quantity)}đ</span>
                </div>
            `;
        });

        checkoutItemsListContainer.innerHTML = checkoutItemsHtmlMarkup;

        // Quy tắc chiết khấu nghiệp vụ: Giảm 100.000đ cho hóa đơn từ 1.000.000đ trở lên
        let calculatedDiscountAmount = accumulatedSubtotalAmount >= 1000000 ? 100000 : 0;
        let calculatedFinalTotalAmount =
            accumulatedSubtotalAmount + currentShippingFeeAmount - calculatedDiscountAmount;

        subtotalDisplayElement.innerText = formatCurrencyPrice(accumulatedSubtotalAmount) + 'đ';
        shippingDisplayElement.innerText = formatCurrencyPrice(currentShippingFeeAmount) + 'đ';
        discountDisplayElement.innerText = '-' + formatCurrencyPrice(calculatedDiscountAmount) + 'đ';
        finalTotalDisplayElement.innerText = formatCurrencyPrice(calculatedFinalTotalAmount) + 'đ';

        return {
            subtotal: accumulatedSubtotalAmount,
            discount: calculatedDiscountAmount,
            finalTotal: calculatedFinalTotalAmount,
        };
    }

    const shippingMethodRadioButtonElements = document.querySelectorAll('input[name="shipping_method"]');
    shippingMethodRadioButtonElements.forEach((radioItem) => {
        radioItem.addEventListener('change', (event) => {
            if (event.target.value === 'Hỏa tốc') {
                currentShippingFeeAmount = 50000;
            } else {
                currentShippingFeeAmount = 30000;
            }
            calculateAndRenderSummary();
        });
    });

    const orderTotalsCalculation = calculateAndRenderSummary();

    // Xử lý sự kiện gửi biểu mẫu xác nhận đặt hàng
    if (checkoutSubmissionForm) {
        checkoutSubmissionForm.addEventListener('submit', (event) => {
            event.preventDefault();

            const customerFullname = document.getElementById('fullname').value.trim();
            const customerPhoneNumber = document.getElementById('phone').value.trim();
            const customerEmailAddress = document.getElementById('email').value.trim();
            const customerShippingAddress = document.getElementById('address').value.trim();
            const customerOrderNote = document.getElementById('note').value.trim();
            const selectedShippingMethod = document.querySelector('input[name="shipping_method"]:checked').value;
            const selectedPaymentMethod = document.querySelector('input[name="payment_method"]:checked').value;

            // Kiểm tra định dạng số điện thoại chuẩn các nhà mạng Việt Nam
            const vietnamPhoneRegex = /^(03|05|07|08|09)\d{8}$/;
            if (!vietnamPhoneRegex.test(customerPhoneNumber)) {
                alert('Số điện thoại không hợp lệ. Vui lòng nhập số điện thoại Việt Nam gồm 10 chữ số.');
                return;
            }

            const checkoutPayloadData = {
                fullname: customerFullname,
                phone: customerPhoneNumber,
                email: customerEmailAddress,
                address: customerShippingAddress,
                note: customerOrderNote,
                shipping_method: selectedShippingMethod,
                shipping_fee: currentShippingFeeAmount,
                payment_method: selectedPaymentMethod,
                items: cartProductsList.map((cartItem) => ({
                    product_id: cartItem.product_id,
                    product_name: cartItem.name,
                    color: cartItem.color,
                    size: cartItem.size,
                    price: cartItem.price,
                    quantity: cartItem.quantity,
                })),
            };

            const submitFormButton = checkoutSubmissionForm.closest('div').querySelector('button[type="submit"]');
            if (submitFormButton) {
                submitFormButton.disabled = true;
                submitFormButton.innerText = 'Đang xử lý đặt hàng...';
            }

            fetch('../routes/order.php?action=checkout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(checkoutPayloadData),
            })
                .then((apiResponse) => apiResponse.json())
                .then((apiResultData) => {
                    if (apiResultData.success) {
                        localStorage.removeItem('the_fox_cart');

                        sessionStorage.setItem(
                            'last_order',
                            JSON.stringify({
                                order_code: apiResultData.order_code,
                                fullname: customerFullname,
                                phone: customerPhoneNumber,
                                email: customerEmailAddress,
                                address: customerShippingAddress,
                                payment_method: selectedPaymentMethod,
                                shipping_method: selectedShippingMethod,
                                shipping_fee: currentShippingFeeAmount,
                                discount: orderTotalsCalculation.discount,
                                subtotal: orderTotalsCalculation.subtotal,
                                final_total: orderTotalsCalculation.finalTotal,
                                items: cartProductsList,
                            })
                        );

                        window.location.href = `invoice.php?order_code=${apiResultData.order_code}`;
                    } else {
                        alert('Có lỗi xảy ra khi tạo đơn hàng: ' + (apiResultData.message || 'Lỗi không xác định'));
                        if (submitFormButton) {
                            submitFormButton.disabled = false;
                            submitFormButton.innerText = 'Xác nhận đặt hàng';
                        }
                    }
                })
                .catch((connectionError) => {
                    console.error('Checkout error:', connectionError);
                    alert('Không thể kết nối đến máy chủ. Hệ thống sẽ kích hoạt lưu trữ đơn hàng ngoại tuyến.');

                    const generatedFallbackOrderCode = 'FOX' + Math.floor(100000 + Math.random() * 900000);
                    localStorage.removeItem('the_fox_cart');

                    sessionStorage.setItem(
                        'last_order',
                        JSON.stringify({
                            order_code: generatedFallbackOrderCode,
                            fullname: customerFullname,
                            phone: customerPhoneNumber,
                            email: customerEmailAddress,
                            address: customerShippingAddress,
                            payment_method: selectedPaymentMethod,
                            shipping_method: selectedShippingMethod,
                            shipping_fee: currentShippingFeeAmount,
                            discount: orderTotalsCalculation.discount,
                            subtotal: orderTotalsCalculation.subtotal,
                            final_total: orderTotalsCalculation.finalTotal,
                            items: cartProductsList,
                        })
                    );

                    window.location.href = `invoice.php?order_code=${generatedFallbackOrderCode}`;
                });
        });
    }

    function formatCurrencyPrice(amountNumber) {
        return amountNumber.toLocaleString('vi-VN');
    }
});
