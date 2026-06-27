/* ==========================================================================
   THE FOX - Module Quản Lý Danh Sách Đơn Hàng (Order Management JS)
   Áp dụng chuẩn thiết kế phần mềm Clean Code & Senior Developer
   Tên biến/hàm: Tiếng Anh chuẩn | Chú thích (Comments): Tiếng Việt chuyên nghiệp
   ========================================================================== */

document.addEventListener('DOMContentLoaded', () => {
    const orderListTableBody = document.getElementById('order-list-tbody');
    const orderSearchInputElement = document.getElementById('order-search');
    const orderDetailModalElement = document.getElementById('order-detail-modal');
    const closeModalIconButton = document.getElementById('close-modal-btn');
    const closeModalFooterButton = document.getElementById('close-modal-footer-btn');
    const modalOrderCodeElement = document.getElementById('modal-order-code');
    const modalBodyContentElement = document.getElementById('modal-body-content');
    const simulateStatusSelectElement = document.getElementById('sim-status-select');
    const logoutButton = document.getElementById('logoutBtn');

    let cachedOrdersList = [];
    let currentlySelectedOrder = null;

    if (logoutButton) {
        logoutButton.addEventListener('click', async () => {
            try {
                const logoutResponse = await fetch('../routes/auth.php?action=logout');
                const logoutResult = await logoutResponse.json();
                if (logoutResult.success) window.location.href = 'login.php';
            } catch (error) {
                window.location.href = 'login.php';
            }
        });
    }

    // Truy xuất danh sách đơn hàng từ Máy chủ API
    function fetchOrders() {
        fetch('../routes/order.php?action=get_orders')
            .then((apiResponse) => apiResponse.json())
            .then((apiResult) => {
                if (apiResult.success) {
                    cachedOrdersList = apiResult.orders;
                    applyFiltersAndRenderOrders();
                } else {
                    loadFallbackMockOrders();
                }
            })
            .catch(() => {
                loadFallbackMockOrders();
            });
    }

    // Tải dữ liệu giả lập dự phòng khi hệ thống mất kết nối máy chủ để tránh làm đứt quãng trải nghiệm xem giao diện
    function loadFallbackMockOrders() {
        let fallbackMockOrders = [];

        const lastPlacedOrder = JSON.parse(sessionStorage.getItem('last_order'));
        if (lastPlacedOrder) {
            fallbackMockOrders.push({
                id: 1,
                order_code: lastPlacedOrder.order_code,
                created_at:
                    new Date().toLocaleDateString('vi-VN') +
                    ' ' +
                    new Date().toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' }),
                fullname: lastPlacedOrder.fullname,
                phone: lastPlacedOrder.phone,
                email: lastPlacedOrder.email,
                address: lastPlacedOrder.address,
                subtotal: lastPlacedOrder.subtotal,
                shipping_fee: lastPlacedOrder.shipping_fee,
                discount: lastPlacedOrder.discount,
                final_total: lastPlacedOrder.final_total,
                payment_method: lastPlacedOrder.payment_method,
                payment_status: lastPlacedOrder.payment_method === 'COD' ? 'Chưa thanh toán' : 'Đã thanh toán',
                status: 'Chờ xác nhận',
                note: lastPlacedOrder.note || '',
                items: lastPlacedOrder.items,
            });
        }

        fallbackMockOrders.push({
            id: 2,
            order_code: 'FOX682941',
            created_at: '24/05/2026 14:32',
            fullname: 'Nguyễn Văn Hùng',
            phone: '0901234567',
            email: 'hungnv@gmail.com',
            address: '123 Đường Lê Lợi, Quận 1, TP. HCM',
            subtotal: 1210000,
            shipping_fee: 30000,
            discount: 100000,
            final_total: 1140000,
            payment_method: 'COD',
            payment_status: 'Đã thanh toán',
            status: 'Đã hoàn thành',
            note: 'Giao giờ hành chính',
            shipping_method: 'Tiêu chuẩn',
            items: [
                { product_name: 'Áo sơ mi Oxford Nam', price: 550000, quantity: 1, color: 'Xanh nhạt', size: 'L' },
                { product_name: 'Quần Short Kaki Casual', price: 420000, quantity: 1, color: 'Bege', size: 'XL' },
            ],
        });

        fallbackMockOrders.push({
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
            note: '',
            shipping_method: 'Nhanh',
            items: [
                { product_name: 'Áo kiểu Fox Summer', price: 790000, quantity: 1, color: 'Hồng Pastel', size: 'M' },
            ],
        });

        let storedMockOrders = JSON.parse(sessionStorage.getItem('the_fox_mock_orders'));
        if (!storedMockOrders) {
            sessionStorage.setItem('the_fox_mock_orders', JSON.stringify(fallbackMockOrders));
            cachedOrdersList = fallbackMockOrders;
        } else {
            cachedOrdersList = storedMockOrders;
        }

        applyFiltersAndRenderOrders();
    }

    // Vẽ danh sách đơn hàng ra bảng HTML giao diện
    function renderOrdersTable(ordersToRender) {
        if (!orderListTableBody) return;
        orderListTableBody.innerHTML = '';

        if (ordersToRender.length === 0) {
            orderListTableBody.innerHTML = `<tr><td colspan="7" style="padding:30px;text-align:center;color:#888;">Bạn chưa có đơn hàng nào.</td></tr>`;
            return;
        }

        const documentFragment = document.createDocumentFragment();

        ordersToRender.forEach((singleOrder) => {
            const statusColorMap = {
                'Chờ xác nhận': '#f57c00',
                'Đang xử lý': '#1976d2',
                'Đang giao hàng': '#7b1fa2',
                'Đã hoàn thành': '#388e3c',
                'Đã hủy': '#d32f2f',
            };
            const statusThemeColor = statusColorMap[singleOrder.status] || '#f57c00';

            const tableRow = document.createElement('tr');
            tableRow.style.borderBottom = '1px solid #eee';
            tableRow.innerHTML = `
                <td style="padding:15px 10px;font-weight:bold;color:var(--primary);">${singleOrder.order_code}</td>
                <td style="padding:15px 10px;color:#666;">${singleOrder.created_at}</td>
                <td style="padding:15px 10px;">
                    <div style="font-weight:500;">${singleOrder.fullname}</div>
                    <div style="font-size:12px;color:#777;">${singleOrder.phone}</div>
                </td>
                <td style="padding:15px 10px;font-weight:bold;">${formatCurrencyPrice(singleOrder.final_total)}đ</td>
                <td style="padding:15px 10px;">
                    <span style="display:inline-block;padding:4px 8px;border-radius:4px;font-size:11px;font-weight:bold;
                        background:${singleOrder.payment_status === 'Đã thanh toán' ? '#e8f5e9' : '#ffeacc'};
                        color:${singleOrder.payment_status === 'Đã thanh toán' ? '#2e7d32' : '#f57c00'};">
                        ${singleOrder.payment_status}
                    </span>
                </td>
                <td style="padding:15px 10px;">
                    <span style="display:inline-block;padding:4px 8px;border-radius:4px;font-size:11px;font-weight:bold;
                        background:${statusThemeColor}1A;color:${statusThemeColor};border:1px solid ${statusThemeColor}40;">
                        ${singleOrder.status}
                    </span>
                </td>
                <td style="padding:15px 10px;text-align:center;">
                    <button class="view-btn"
                        style="padding:6px 14px;background:#333;color:#fff;border:none;border-radius:5px;
                               font-size:12px;cursor:pointer;font-weight:500;transition:background 0.2s;"
                        onmouseover="this.style.background='#BF8A49'"
                        onmouseout="this.style.background='#333'">
                        Chi tiết
                    </button>
                </td>
            `;

            tableRow.querySelector('.view-btn').addEventListener('click', () => openOrderDetailModal(singleOrder));
            documentFragment.appendChild(tableRow);
        });

        orderListTableBody.appendChild(documentFragment);
    }

    // Hiển thị Popup Modal thông tin chi tiết toàn bộ đơn hàng
    function openOrderDetailModal(singleOrder) {
        currentlySelectedOrder = singleOrder;
        modalOrderCodeElement.innerText = `(${singleOrder.order_code})`;

        if (simulateStatusSelectElement) {
            simulateStatusSelectElement.value = singleOrder.status;
        }

        let orderItemsHtmlMarkup = '';
        (singleOrder.items || []).forEach((orderItem) => {
            orderItemsHtmlMarkup += `
                <div style="display:flex;justify-content:space-between;border-bottom:1px dashed #eee;padding:10px 0;">
                    <div>
                        <div style="font-weight:bold;color:#333;">${orderItem.product_name}</div>
                        <div style="font-size:12px;color:#777;">Phân loại: ${orderItem.color} | Size: ${orderItem.size}</div>
                    </div>
                    <div style="text-align:right;min-width:140px;">
                        <div>${orderItem.quantity} × ${formatCurrencyPrice(orderItem.price)}đ</div>
                        <strong style="color:var(--primary);">${formatCurrencyPrice(orderItem.price * orderItem.quantity)}đ</strong>
                    </div>
                </div>
            `;
        });

        modalBodyContentElement.innerHTML = `
            <div style="display:flex;gap:20px;flex-wrap:wrap;margin-bottom:20px;border-bottom:1px solid #eee;padding-bottom:15px;">
                <div style="flex:1;min-width:200px;">
                    <h4 style="margin:0 0 10px;color:#555;text-transform:uppercase;font-size:13px;border-left:3px solid var(--primary);padding-left:8px;">Thông tin khách hàng</h4>
                    <p style="margin:0 0 6px;"><strong>Họ và tên:</strong> ${singleOrder.fullname}</p>
                    <p style="margin:0 0 6px;"><strong>Điện thoại:</strong> ${singleOrder.phone}</p>
                    <p style="margin:0 0 6px;"><strong>Email:</strong> ${singleOrder.email || '—'}</p>
                    <p style="margin:0 0 6px;"><strong>Địa chỉ:</strong> ${singleOrder.address}</p>
                </div>
                <div style="flex:1;min-width:200px;">
                    <h4 style="margin:0 0 10px;color:#555;text-transform:uppercase;font-size:13px;border-left:3px solid var(--primary);padding-left:8px;">Giao nhận & Thanh toán</h4>
                    <p style="margin:0 0 6px;"><strong>Vận chuyển:</strong> ${singleOrder.shipping_method || 'Tiêu chuẩn'}</p>
                    <p style="margin:0 0 6px;"><strong>Thanh toán:</strong> ${singleOrder.payment_method}</p>
                    <p style="margin:0 0 6px;"><strong>Trạng thái thanh toán:</strong> ${singleOrder.payment_status}</p>
                    <p style="margin:0 0 6px;"><strong>Ghi chú:</strong> <em style="color:#666;">${singleOrder.note || 'Không có'}</em></p>
                </div>
            </div>

            <h4 style="margin:0 0 10px;color:#555;text-transform:uppercase;font-size:13px;border-left:3px solid var(--primary);padding-left:8px;">Chi tiết sản phẩm</h4>
            <div style="margin-bottom:20px;">${orderItemsHtmlMarkup}</div>

            <div style="display:flex;flex-direction:column;gap:6px;text-align:right;border-top:1px solid #eee;padding-top:14px;">
                <div>Tạm tính: <strong>${formatCurrencyPrice(singleOrder.subtotal)}đ</strong></div>
                <div>Phí vận chuyển: <strong>+${formatCurrencyPrice(singleOrder.shipping_fee)}đ</strong></div>
                <div style="color:#d32f2f;">Giảm giá: <strong>-${formatCurrencyPrice(singleOrder.discount || 0)}đ</strong></div>
                <div style="font-size:16px;font-weight:bold;color:var(--primary);margin-top:5px;">
                    Thành tiền: <span>${formatCurrencyPrice(singleOrder.final_total)}đ</span>
                </div>
            </div>
        `;

        orderDetailModalElement.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeOrderDetailModal() {
        orderDetailModalElement.classList.remove('active');
        document.body.style.overflow = '';
        currentlySelectedOrder = null;
    }

    if (closeModalIconButton) closeModalIconButton.addEventListener('click', closeOrderDetailModal);
    if (closeModalFooterButton) closeModalFooterButton.addEventListener('click', closeOrderDetailModal);

    orderDetailModalElement.addEventListener('click', (event) => {
        if (event.target === orderDetailModalElement) closeOrderDetailModal();
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && orderDetailModalElement.classList.contains('active')) closeOrderDetailModal();
    });

    if (simulateStatusSelectElement) {
        simulateStatusSelectElement.addEventListener('change', (event) => {
            if (!currentlySelectedOrder) return;
            const updatedStatus = event.target.value;

            fetch('../routes/order.php?action=update_status', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    order_id: currentlySelectedOrder.id,
                    order_code: currentlySelectedOrder.order_code,
                    status: updatedStatus,
                }),
            })
                .then((response) => response.json())
                .then((resultData) => {
                    if (resultData.success) {
                        fetchOrders();
                        closeOrderDetailModal();
                    } else {
                        updateLocalMockOrderStatus(updatedStatus);
                    }
                })
                .catch(() => updateLocalMockOrderStatus(updatedStatus));
        });
    }

    function updateLocalMockOrderStatus(updatedStatus) {
        cachedOrdersList = cachedOrdersList.map((orderItem) => {
            if (orderItem.order_code === currentlySelectedOrder.order_code) {
                orderItem.status = updatedStatus;
                if (updatedStatus === 'Đã hoàn thành') orderItem.payment_status = 'Đã thanh toán';
            }
            return orderItem;
        });
        sessionStorage.setItem('the_fox_mock_orders', JSON.stringify(cachedOrdersList));
        applyFiltersAndRenderOrders();
        closeOrderDetailModal();
    }

    let activeStatusFilter = 'all';
    let activeSearchQuery = '';

    const statusTabElements = document.querySelectorAll('.order-status-tab');
    statusTabElements.forEach((singleTab) => {
        singleTab.addEventListener('click', () => {
            statusTabElements.forEach((tabItem) => {
                tabItem.classList.remove('active');
                tabItem.style.color = '#555';
                tabItem.style.borderBottomColor = 'transparent';
                tabItem.style.fontWeight = '500';
            });
            singleTab.classList.add('active');
            singleTab.style.color = '#ee4d2d';
            singleTab.style.borderBottomColor = '#ee4d2d';
            singleTab.style.fontWeight = '600';

            activeStatusFilter = singleTab.getAttribute('data-status');
            applyFiltersAndRenderOrders();
        });
    });

    if (orderSearchInputElement) {
        orderSearchInputElement.addEventListener('input', (event) => {
            activeSearchQuery = event.target.value.trim().toLowerCase();
            applyFiltersAndRenderOrders();
        });
    }

    function applyFiltersAndRenderOrders() {
        let filteredOrders = cachedOrdersList;

        if (activeStatusFilter !== 'all') {
            filteredOrders = filteredOrders.filter((order) => {
                if (activeStatusFilter === 'Chờ thanh toán')
                    return (
                        order.payment_status === 'Chưa thanh toán' ||
                        order.status === 'Chờ thanh toán' ||
                        order.status === 'Chờ xác nhận'
                    );
                if (activeStatusFilter === 'Đang giao hàng')
                    return order.status === 'Đang giao hàng' || order.status === 'Vận chuyển';
                if (activeStatusFilter === 'Đang xử lý')
                    return order.status === 'Đang xử lý' || order.status === 'Chờ giao hàng';
                if (activeStatusFilter === 'Đã hoàn thành')
                    return order.status === 'Đã hoàn thành' || order.status === 'Hoàn thành';
                if (activeStatusFilter === 'Đã hủy') return order.status === 'Đã hủy';
                if (activeStatusFilter === 'Trả hàng')
                    return order.status === 'Trả hàng' || order.status === 'Trả hàng/Hoàn tiền';
                return order.status === activeStatusFilter;
            });
        }

        if (activeSearchQuery !== '') {
            filteredOrders = filteredOrders.filter((order) => {
                const isCodeMatching = (order.order_code || '').toLowerCase().includes(activeSearchQuery);
                const isNameMatching = (order.fullname || '').toLowerCase().includes(activeSearchQuery);
                const isProductMatching = (order.items || []).some((item) =>
                    (item.product_name || '').toLowerCase().includes(activeSearchQuery)
                );
                return isCodeMatching || isNameMatching || isProductMatching;
            });
        }

        renderOrdersTable(filteredOrders);
    }

    function formatCurrencyPrice(amountNumber) {
        return Number(amountNumber || 0).toLocaleString('vi-VN');
    }

    fetchOrders();
});
