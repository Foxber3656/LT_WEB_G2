document.addEventListener('DOMContentLoaded', () => {
    const listBody = document.getElementById('order-list-tbody');
    const searchInput = document.getElementById('order-search');
    const detailModal = document.getElementById('order-detail-modal');
    const closeModalBtn = document.getElementById('close-modal-btn');
    const closeModalFooterBtn = document.getElementById('close-modal-footer-btn');
    const modalOrderCode = document.getElementById('modal-order-code');
    const modalBodyContent = document.getElementById('modal-body-content');
    const simStatusSelect = document.getElementById('sim-status-select');
    
    let ordersList = [];
    let selectedOrder = null;
    
    // Tải danh sách đơn hàng từ backend API
    function fetchOrders() {
        fetch('../routes/order.php?action=get_orders')
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    ordersList = data.orders;
                    renderOrders(ordersList);
                } else {
                    loadLocalMockOrders();
                }
            })
            .catch(err => {
                console.warn('API error, falling back to session storage / mock data.');
                loadLocalMockOrders();
            });
    }
    
    // Tải dữ liệu giả lập dự phòng cục bộ
    function loadLocalMockOrders() {
        let localMock = [];
        
        // 1. Lấy đơn hàng mới nhất vừa đặt trong phiên làm việc hiện tại
        const lastOrder = JSON.parse(sessionStorage.getItem('last_order'));
        if (lastOrder) {
            localMock.push({
                id: 1,
                order_code: lastOrder.order_code,
                created_at: new Date().toLocaleDateString('vi-VN') + ' ' + new Date().toLocaleTimeString('vi-VN', {hour: '2-digit', minute:'2-digit'}),
                fullname: lastOrder.fullname,
                phone: lastOrder.phone,
                email: lastOrder.email,
                address: lastOrder.address,
                subtotal: lastOrder.subtotal,
                shipping_fee: lastOrder.shipping_fee,
                discount: lastOrder.discount,
                final_total: lastOrder.final_total,
                payment_method: lastOrder.payment_method,
                payment_status: lastOrder.payment_method === 'COD' ? 'Chưa thanh toán' : 'Đã thanh toán',
                status: 'Chờ xác nhận',
                note: lastOrder.note || '',
                items: lastOrder.items
            });
        }
        
        // 2. Thêm dữ liệu mẫu mặc định để hiển thị trực quan phong phú
        localMock.push({
            id: 2,
            order_code: 'FOX682941',
            created_at: '24/05/2026 14:32',
            fullname: 'Nguyễn Văn Hùng',
            phone: '0901234567',
            email: 'hungnv@gmail.com',
            address: '123 Đường Lê Lợi, Quận 1, TP. Hồ Chí Minh',
            subtotal: 1210000,
            shipping_fee: 30000,
            discount: 100000,
            final_total: 1140000,
            payment_method: 'COD',
            payment_status: 'Đã thanh toán',
            status: 'Đã hoàn thành',
            note: 'Giao giờ hành chính',
            items: [
                { product_name: 'Áo sơ mi Oxford Nam', price: 550000, quantity: 1, color: 'Xanh nhạt', size: 'L' },
                { product_name: 'Quần Short Kaki Casual', price: 420000, quantity: 1, color: 'Bege', size: 'XL' }
            ]
        });
        
        localMock.push({
            id: 3,
            order_code: 'FOX209351',
            created_at: '25/05/2026 09:15',
            fullname: 'Lê Thị Thu',
            phone: '0978999888',
            email: 'thult@yahoo.com',
            address: '45 Ngõ 192 Kim Mã, Ba Đình, Hà Nội',
            subtotal: 790000,
            shipping_fee: 50000,
            discount: 0,
            final_total: 840000,
            payment_method: 'Chuyển khoản',
            payment_status: 'Đã thanh toán',
            status: 'Đang giao hàng',
            note: 'Để ở bảo vệ nếu không gọi điện được',
            items: [
                { product_name: 'Áo kiểu Fox Summer', price: 790000, quantity: 1, color: 'Hồng Pastel', size: 'M' }
            ]
        });

        // Sử dụng sessionStorage để ghi nhận các thay đổi giả lập trạng thái
        let storedMock = JSON.parse(sessionStorage.getItem('the_fox_mock_orders'));
        if (!storedMock) {
            sessionStorage.setItem('the_fox_mock_orders', JSON.stringify(localMock));
            ordersList = localMock;
        } else {
            ordersList = storedMock;
        }
        
        renderOrders(ordersList);
    }
    
    // Hiển thị danh sách đơn hàng ra bảng HTML
    function renderOrders(orders) {
        if (!listBody) return;
        listBody.innerHTML = '';
        
        if (orders.length === 0) {
            listBody.innerHTML = `
                <tr>
                    <td colspan="7" style="padding: 30px; text-align: center; color: #888;">Không tìm thấy đơn hàng nào.</td>
                </tr>
            `;
            return;
        }
        
        // Tối ưu hóa DOM: Sử dụng DocumentFragment để gom các thẻ TR rồi chèn một lần nhằm tránh reflow liên tục
        const fragment = document.createDocumentFragment();
        
        orders.forEach(order => {
            let statusColor = '#f57c00'; // Mặc định màu cam cho Chờ xác nhận
            if (order.status === 'Đang xử lý') statusColor = '#1976d2';
            if (order.status === 'Đang giao hàng') statusColor = '#7b1fa2';
            if (order.status === 'Đã hoàn thành') statusColor = '#388e3c';
            if (order.status === 'Đã hủy') statusColor = '#d32f2f';

            const tr = document.createElement('tr');
            tr.style.borderBottom = '1px solid #eee';
            tr.innerHTML = `
                <td style="padding: 15px 10px; font-weight: bold; color: var(--primary);">${order.order_code}</td>
                <td style="padding: 15px 10px; color: #666;">${order.created_at}</td>
                <td style="padding: 15px 10px;">
                    <div style="font-weight: 500;">${order.fullname}</div>
                    <div style="font-size: 12px; color: #777;">${order.phone}</div>
                </td>
                <td style="padding: 15px 10px; font-weight: bold;">${formatPrice(order.final_total)}đ</td>
                <td style="padding: 15px 10px;">
                    <span style="display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; background: ${order.payment_status === 'Đã thanh toán' ? '#e8f5e9' : '#ffeacc'}; color: ${order.payment_status === 'Đã thanh toán' ? '#2e7d32' : '#f57c00'};">
                        ${order.payment_status}
                    </span>
                </td>
                <td style="padding: 15px 10px;">
                    <span style="display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; background: ${statusColor}1A; color: ${statusColor}; border: 1px solid ${statusColor}40;">
                        ${order.status}
                    </span>
                </td>
                <td style="padding: 15px 10px; text-align: center;">
                    <button class="view-btn" data-id="${order.id}" style="padding: 6px 12px; background: #333; color: #fff; border: none; border-radius: 4px; font-size: 12px; cursor: pointer; font-weight: 500; transition: background 0.2s;">Chi tiết</button>
                </td>
            `;
            
            // Lắng nghe sự kiện click mở Popup Modal xem chi tiết
            tr.querySelector('.view-btn').addEventListener('click', () => {
                openOrderDetail(order);
            });
            
            fragment.appendChild(tr);
        });
        
        listBody.appendChild(fragment);
    }
    
    // Mở popup modal chi tiết đơn hàng
    function openOrderDetail(order) {
        selectedOrder = order;
        modalOrderCode.innerText = `(${order.order_code})`;
        simStatusSelect.value = order.status;
        
        let itemsHTML = '';
        order.items.forEach(item => {
            itemsHTML += `
                <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed #eee; padding: 10px 0;">
                    <div>
                        <div style="font-weight: bold; color: #333;">${item.product_name}</div>
                        <div style="font-size: 12px; color: #777;">Phân loại: ${item.color} | Size: ${item.size}</div>
                    </div>
                    <div style="text-align: right;">
                        <div>${item.quantity} x ${formatPrice(item.price)}đ</div>
                        <strong style="color: var(--primary);">${formatPrice(item.price * item.quantity)}đ</strong>
                    </div>
                </div>
            `;
        });
        
        modalBodyContent.innerHTML = `
            <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 15px;">
                <div style="flex: 1; min-width: 200px;">
                    <h4 style="margin: 0 0 10px 0; color: #555; text-transform: uppercase; font-size: 13px; border-left: 3px solid var(--primary); padding-left: 8px;">Thông tin khách hàng</h4>
                    <p style="margin: 0 0 6px 0;"><strong>Họ và tên:</strong> ${order.fullname}</p>
                    <p style="margin: 0 0 6px 0;"><strong>Điện thoại:</strong> ${order.phone}</p>
                    <p style="margin: 0 0 6px 0;"><strong>Email:</strong> ${order.email}</p>
                    <p style="margin: 0 0 6px 0;"><strong>Địa chỉ:</strong> ${order.address}</p>
                </div>
                <div style="flex: 1; min-width: 200px;">
                    <h4 style="margin: 0 0 10px 0; color: #555; text-transform: uppercase; font-size: 13px; border-left: 3px solid var(--primary); padding-left: 8px;">Giao nhận & Thanh toán</h4>
                    <p style="margin: 0 0 6px 0;"><strong>Vận chuyển:</strong> ${order.shipping_method}</p>
                    <p style="margin: 0 0 6px 0;"><strong>Thanh toán:</strong> ${order.payment_method}</p>
                    <p style="margin: 0 0 6px 0;"><strong>Trạng thái thanh toán:</strong> ${order.payment_status}</p>
                    <p style="margin: 0 0 6px 0;"><strong>Ghi chú:</strong> <span style="font-style: italic; color: #666;">${order.note || 'Không có'}</span></p>
                </div>
            </div>
            
            <h4 style="margin: 0 0 10px 0; color: #555; text-transform: uppercase; font-size: 13px; border-left: 3px solid var(--primary); padding-left: 8px;">Chi tiết sản phẩm</h4>
            <div style="margin-bottom: 20px;">
                ${itemsHTML}
            </div>
            
            <div style="display: flex; flex-direction: column; gap: 8px; text-align: right; border-top: 1px solid #eee; padding-top: 15px;">
                <div>Tạm tính: <strong>${formatPrice(order.subtotal)}đ</strong></div>
                <div>Phí vận chuyển: <strong>+${formatPrice(order.shipping_fee)}đ</strong></div>
                <div style="color: #d32f2f;">Giảm giá: <strong>-${formatPrice(order.discount)}đ</strong></div>
                <div style="font-size: 16px; font-weight: bold; color: var(--primary); margin-top: 5px;">Thành tiền: <span>${formatPrice(order.final_total)}đ</span></div>
            </div>
        `;
        
        detailModal.style.display = 'flex';
    }
    
    // Đóng popup modal
    function closeModal() {
        detailModal.style.display = 'none';
        selectedOrder = null;
    }
    
    closeModalBtn.addEventListener('click', closeModal);
    closeModalFooterBtn.addEventListener('click', closeModal);
    
    // Nhấp chuột ra vùng ngoài để đóng modal
    detailModal.addEventListener('click', (e) => {
        if (e.target === detailModal) {
            closeModal();
        }
    });
    
    // Giả lập cập nhật trạng thái đơn hàng khi chọn dropdown
    if (simStatusSelect) {
        simStatusSelect.addEventListener('change', (e) => {
            if (!selectedOrder) return;
            const newStatus = e.target.value;
            
            fetch('../routes/order.php?action=update_status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    order_id: selectedOrder.id,
                    order_code: selectedOrder.order_code,
                    status: newStatus
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    fetchOrders();
                    closeModal();
                } else {
                    simulateLocalStatusUpdate(newStatus);
                }
            })
            .catch(err => {
                simulateLocalStatusUpdate(newStatus);
            });
        });
    }
    
    // Cập nhật trạng thái ngoại tuyến ngoại lệ
    function simulateLocalStatusUpdate(newStatus) {
        ordersList = ordersList.map(o => {
            if (o.order_code === selectedOrder.order_code) {
                o.status = newStatus;
                if (newStatus === 'Đã hoàn thành') {
                    o.payment_status = 'Đã thanh toán';
                }
            }
            return o;
        });
        
        sessionStorage.setItem('the_fox_mock_orders', JSON.stringify(ordersList));
        
        const lastOrder = JSON.parse(sessionStorage.getItem('last_order'));
        if (lastOrder && lastOrder.order_code === selectedOrder.order_code) {
            lastOrder.payment_status = newStatus === 'Đã hoàn thành' ? 'Đã thanh toán' : lastOrder.payment_status;
            sessionStorage.setItem('last_order', JSON.stringify({
                ...lastOrder,
                status: newStatus
            }));
        }
        
        renderOrders(ordersList);
        closeModal();
    }
    
    // Tìm kiếm đơn hàng thời gian thực (Real-time Filter)
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.trim().toLowerCase();
            const filtered = ordersList.filter(o => o.order_code.toLowerCase().includes(query));
            renderOrders(filtered);
        });
    }
    
    // Định dạng tiền tệ
    function formatPrice(number) {
        return number.toLocaleString('vi-VN');
    }
    
    // Khởi động
    fetchOrders();
});
