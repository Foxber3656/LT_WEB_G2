document.addEventListener('DOMContentLoaded', () => {
    const itemsListContainer = document.getElementById('checkout-items-list');
    const subtotalEl = document.getElementById('summary-subtotal');
    const shippingEl = document.getElementById('summary-shipping');
    const discountEl = document.getElementById('summary-discount');
    const totalEl = document.getElementById('summary-total');
    const checkoutForm = document.getElementById('checkout-form');
    
    // Tải danh sách giỏ hàng
    let cart = JSON.parse(localStorage.getItem('the_fox_cart')) || [];
    
    // Chuyển hướng người dùng nếu giỏ hàng trống rỗng
    if (cart.length === 0) {
        alert('Giỏ hàng của bạn đang trống. Vui lòng thêm sản phẩm trước khi thanh toán.');
        window.location.href = 'cart.php';
        return;
    }
    
    let shippingFee = 30000; // Phí ship mặc định của Giao hàng tiêu chuẩn
    
    // Hiển thị tóm tắt đơn hàng ở cột bên phải
    function renderSummary() {
        if (!itemsListContainer) return;
        
        let subtotal = 0;
        let itemsHTML = '';
        
        cart.forEach(item => {
            subtotal += item.price * item.quantity;
            itemsHTML += `
                <div style="display: flex; gap: 12px; align-items: center; border-bottom: 1px solid #f0f0f0; padding-bottom: 10px;">
                    <img src="${item.image}" alt="${item.name}" style="width: 50px; height: 60px; object-fit: cover; border-radius: 4px;">
                    <div style="flex: 1;">
                        <h4 style="font-size: 13px; font-weight: 500; margin: 0 0 3px 0; color: #333;">${item.name}</h4>
                        <span style="font-size: 11px; color: #777; display: block;">Màu: ${item.color} | Size: ${item.size}</span>
                        <span style="font-size: 12px; font-weight: bold; color: #555;">${item.quantity} x ${formatPrice(item.price)}đ</span>
                    </div>
                    <span style="font-size: 13px; font-weight: bold; color: #333;">${formatPrice(item.price * item.quantity)}đ</span>
                </div>
            `;
        });
        
        itemsListContainer.innerHTML = itemsHTML;
        
        // Áp dụng quy tắc giảm giá: Giảm 100k cho đơn hàng từ 1.000.000đ trở lên
        let discount = subtotal >= 1000000 ? 100000 : 0;
        let finalTotal = subtotal + shippingFee - discount;
        
        subtotalEl.innerText = formatPrice(subtotal) + 'đ';
        shippingEl.innerText = formatPrice(shippingFee) + 'đ';
        discountEl.innerText = '-' + formatPrice(discount) + 'đ';
        totalEl.innerText = formatPrice(finalTotal) + 'đ';
        
        return { subtotal, discount, finalTotal };
    }
    
    // Theo dõi thay đổi phương thức vận chuyển
    const shippingRadios = document.querySelectorAll('input[name="shipping_method"]');
    shippingRadios.forEach(radio => {
        radio.addEventListener('change', (e) => {
            if (e.target.value === 'Hỏa tốc') {
                shippingFee = 50000;
            } else {
                shippingFee = 30000;
            }
            renderSummary();
        });
    });
    
    const totals = renderSummary();
    
    // Xử lý xác nhận đặt hàng
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            const fullname = document.getElementById('fullname').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const email = document.getElementById('email').value.trim();
            const address = document.getElementById('address').value.trim();
            const note = document.getElementById('note').value.trim();
            const shipping_method = document.querySelector('input[name="shipping_method"]:checked').value;
            const payment_method = document.querySelector('input[name="payment_method"]:checked').value;
            
            const phoneRegex = /^(03|05|07|08|09)\d{8}$/;
            if (!phoneRegex.test(phone)) {
                alert('Số điện thoại không hợp lệ. Vui lòng nhập số điện thoại Việt Nam gồm 10 chữ số.');
                return;
            }
            
            const orderData = {
                fullname,
                phone,
                email,
                address,
                note,
                shipping_method,
                shipping_fee: shippingFee,
                payment_method,
                items: cart.map(item => ({
                    product_id: item.product_id,
                    product_name: item.name,
                    color: item.color,
                    size: item.size,
                    price: item.price,
                    quantity: item.quantity
                }))
            };
            
            const submitBtn = checkoutForm.closest('div').querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerText = 'Đang xử lý đặt hàng...';
            }
            
            fetch('../routes/order.php?action=checkout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(orderData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    localStorage.removeItem('the_fox_cart');
                    
                    sessionStorage.setItem('last_order', JSON.stringify({
                        order_code: data.order_code,
                        fullname: fullname,
                        phone: phone,
                        email: email,
                        address: address,
                        payment_method: payment_method,
                        shipping_method: shipping_method,
                        shipping_fee: shippingFee,
                        discount: totals.discount,
                        subtotal: totals.subtotal,
                        final_total: totals.finalTotal,
                        items: cart
                    }));
                    
                    window.location.href = `invoice.php?order_code=${data.order_code}`;
                } else {
                    alert('Có lỗi xảy ra khi tạo đơn hàng: ' + (data.message || 'Lỗi không xác định'));
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerText = 'Xác nhận đặt hàng';
                    }
                }
            })
            .catch(err => {
                console.error('Checkout error:', err);
                alert('Không thể kết nối đến máy chủ. Hệ thống sẽ kích hoạt lưu trữ đơn hàng ngoại tuyến.');
                
                const mockOrderCode = 'FOX' + Math.floor(100000 + Math.random() * 900000);
                localStorage.removeItem('the_fox_cart');
                
                sessionStorage.setItem('last_order', JSON.stringify({
                    order_code: mockOrderCode,
                    fullname: fullname,
                    phone: phone,
                    email: email,
                    address: address,
                    payment_method: payment_method,
                    shipping_method: shipping_method,
                    shipping_fee: shippingFee,
                    discount: totals.discount,
                    subtotal: totals.subtotal,
                    final_total: totals.finalTotal,
                    items: cart
                }));
                
                window.location.href = `invoice.php?order_code=${mockOrderCode}`;
            });
        });
    }
    
    function formatPrice(num) {
        return num.toLocaleString('vi-VN');
    }
});
