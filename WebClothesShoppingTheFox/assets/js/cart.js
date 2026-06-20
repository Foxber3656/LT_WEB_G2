document.addEventListener('DOMContentLoaded', () => {
    const cartLeftContainer = document.querySelector('.cart-left');
    const titleCountEl = document.querySelector('.cart-title span');
    const summaryItems = document.querySelectorAll('.cart-summary-item span');
    const cartOrderBtn = document.querySelector('.cart-order');
    const continueShoppingBtn = document.querySelector('.continue-shopping');

    // 1. Hàm tải dữ liệu và render trang giỏ hàng chính
    function renderMainCart() {
        let cart = JSON.parse(localStorage.getItem('the_fox_cart')) || [];

        // Hỗ trợ đồng bộ dữ liệu mẫu ban đầu nếu giỏ hàng trống hoàn toàn để dễ test
        if (cart.length === 0) {
            cart = [
                {
                    product_id: 1,
                    name: 'Áo kiểu Fox Summer',
                    price: 790000,
                    quantity: 1,
                    color: 'Hồng Pastel',
                    size: 'M',
                    image: '../assets/images/sp1.jpg',
                },
                {
                    product_id: 2,
                    name: 'Váy Fox Summer',
                    price: 690000,
                    quantity: 2,
                    color: 'Đỏ',
                    size: 'S',
                    image: '../assets/images/sp2.jpg',
                },
            ];
            localStorage.setItem('the_fox_cart', JSON.stringify(cart));
        }

        if (!cartLeftContainer) return;
        cartLeftContainer.innerHTML = '';

        let totalQuantity = 0;
        let totalPrice = 0;

        cart.forEach((item, index) => {
            const itemTotal = item.price * item.quantity;
            totalQuantity += item.quantity;
            totalPrice += itemTotal;

            const itemHTML = `
                <div class="cart-item" data-index="${index}">
                    <div class="cart-item-image">
                        <img src="${item.image}" alt="${item.name}">
                    </div>
                    <div class="cart-item-info">
                        <h3>${item.name}</h3>
                        <p>Màu: ${item.color}</p>
                        <p>Size: ${item.size}</p>
                    </div>
                    <div class="cart-item-price">
                        <p>${formatPrice(item.price)}đ</p>
                    </div>
                    <div class="cart-item-quantity" style="display: flex; align-items: center; gap: 5px;">
                        <button class="btn-qty-minus" style="width: 28px; height: 28px; border: 1px solid #ccc; background: #fff; cursor: pointer; font-size: 14px;">-</button>
                        <input type="text" value="${item.quantity}" readonly style="width: 35px; text-align: center; border: 1px solid #ccc; height: 28px; font-size: 14px;">
                        <button class="btn-qty-plus" style="width: 28px; height: 28px; border: 1px solid #ccc; background: #fff; cursor: pointer; font-size: 14px;">+</button>
                    </div>
                    <div class="cart-item-total">
                        <p>${formatPrice(itemTotal)}đ</p>
                    </div>
                    <div class="cart-item-delete" style="cursor: pointer; padding: 5px; color: #888; transition: .3s;">
                        <i class="fas fa-trash"></i>
                    </div>
                </div>
            `;
            cartLeftContainer.insertAdjacentHTML('beforeend', itemHTML);
        });

        // Cập nhật số lượng ở Title
        if (titleCountEl) {
            titleCountEl.innerText = `${totalQuantity} Sản phẩm`;
        }

        // Cập nhật Summary
        if (summaryItems && summaryItems.length >= 4) {
            // Tổng sản phẩm
            summaryItems[0].innerText = totalQuantity;
            // Tổng tiền hàng
            summaryItems[1].innerText = formatPrice(totalPrice) + 'đ';

            // Giảm giá (mặc định cho demo là 100.000đ nếu tổng > 500k, còn lại là 0)
            const discount = totalPrice > 500000 ? 100000 : 0;
            summaryItems[2].innerText = `-${formatPrice(discount)}đ`;

            // Thành tiền
            const finalTotal = Math.max(0, totalPrice - discount);
            summaryItems[3].innerText = formatPrice(finalTotal) + 'đ';
        }

        attachEventListeners(cart);
    }

    // 2. Gắn các sự kiện click cho nút Tăng / Giảm / Xóa
    function attachEventListeners(cart) {
        const items = cartLeftContainer.querySelectorAll('.cart-item');
        items.forEach((itemEl) => {
            const index = parseInt(itemEl.dataset.index);
            const minusBtn = itemEl.querySelector('.btn-qty-minus');
            const plusBtn = itemEl.querySelector('.btn-qty-plus');
            const deleteBtn = itemEl.querySelector('.cart-item-delete');

            minusBtn.addEventListener('click', () => {
                if (cart[index].quantity > 1) {
                    cart[index].quantity--;
                    updateLocalStorage(cart);
                }
            });

            plusBtn.addEventListener('click', () => {
                cart[index].quantity++;
                updateLocalStorage(cart);
            });

            deleteBtn.addEventListener('click', () => {
                cart.splice(index, 1);
                updateLocalStorage(cart);
            });
        });
    }

    function updateLocalStorage(cart) {
        localStorage.setItem('the_fox_cart', JSON.stringify(cart));
        renderMainCart();
        // Đồng bộ ngược lại với giỏ hàng trượt nếu có
        if (typeof window.loadAndRenderSidebarCart === 'function') {
            window.loadAndRenderSidebarCart();
        }
    }

    function formatPrice(number) {
        return number.toLocaleString('vi-VN');
    }

    // 3. Sự kiện chuyển trang thanh toán và tiếp tục mua hàng
    if (cartOrderBtn) {
        cartOrderBtn.addEventListener('click', () => {
            window.location.href = 'checkout.php';
        });
    }

    if (continueShoppingBtn) {
        continueShoppingBtn.addEventListener('click', () => {
            window.location.href = 'cartegory.php';
        });
    }

    // Chạy render lần đầu
    renderMainCart();
});
