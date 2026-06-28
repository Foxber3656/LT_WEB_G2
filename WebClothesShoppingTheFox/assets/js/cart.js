/* ==========================================================================
   THE FOX - Module Quản Lý Giỏ Hàng Chính (Main Shopping Cart JS)
   Áp dụng chuẩn thiết kế phần mềm Clean Code & Senior Developer
   Tên biến/hàm: Tiếng Anh chuẩn | Chú thích (Comments): Tiếng Việt chuyên nghiệp
   ========================================================================== */

document.addEventListener('DOMContentLoaded', () => {
    const cartLeftContainer = document.querySelector('.cart-left');
    const titleCountElement = document.querySelector('.cart-title span');
    const summaryItems = document.querySelectorAll('.cart-summary-item span');
    const proceedToCheckoutButton = document.querySelector('.cart-order');
    const continueShoppingButton = document.querySelector('.continue-shopping');

    // Tải dữ liệu giỏ hàng và render toàn bộ danh sách sản phẩm cùng tổng chi phí
    function renderMainCart() {
        let cartItems = JSON.parse(localStorage.getItem('the_fox_cart')) || [];

        // Tạo dữ liệu mẫu khởi tạo ban đầu khi giỏ hàng trống giúp thử nghiệm giao diện thuận tiện hơn
        if (cartItems.length === 0) {
            cartItems = [
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
            localStorage.setItem('the_fox_cart', JSON.stringify(cartItems));
        }

        if (!cartLeftContainer) return;
        cartLeftContainer.innerHTML = '';

        let totalQuantity = 0;
        let totalPrice = 0;

        cartItems.forEach((item, itemIndex) => {
            const itemTotal = item.price * item.quantity;
            totalQuantity += item.quantity;
            totalPrice += itemTotal;

            const itemHtmlMarkup = `
                <div class="cart-item" data-index="${itemIndex}">
                    <div class="cart-item-image">
                        <img src="${item.image}" alt="${item.name}">
                    </div>
                    <div class="cart-item-info">
                        <h3>${item.name}</h3>
                        <p>Màu: ${item.color}</p>
                        <p>Size: ${item.size}</p>
                    </div>
                    <div class="cart-item-price">
                        <p>${formatCurrencyPrice(item.price)}đ</p>
                    </div>
                    <div class="cart-item-quantity" style="display: flex; align-items: center; gap: 5px;">
                        <button class="btn-qty-minus" style="width: 28px; height: 28px; border: 1px solid #ccc; background: #fff; cursor: pointer; font-size: 14px;">-</button>
                        <input type="text" value="${item.quantity}" readonly style="width: 35px; text-align: center; border: 1px solid #ccc; height: 28px; font-size: 14px;">
                        <button class="btn-qty-plus" style="width: 28px; height: 28px; border: 1px solid #ccc; background: #fff; cursor: pointer; font-size: 14px;">+</button>
                    </div>
                    <div class="cart-item-total">
                        <p>${formatCurrencyPrice(itemTotal)}đ</p>
                    </div>
                    <div class="cart-item-delete" style="cursor: pointer; padding: 5px; color: #888; transition: .3s;">
                        <i class="fas fa-trash"></i>
                    </div>
                </div>
            `;
            cartLeftContainer.insertAdjacentHTML('beforeend', itemHtmlMarkup);
        });

        if (titleCountElement) {
            titleCountElement.innerText = `${totalQuantity} Sản phẩm`;
        }

        if (summaryItems && summaryItems.length >= 4) {
            summaryItems[0].innerText = totalQuantity;
            summaryItems[1].innerText = formatCurrencyPrice(totalPrice) + 'đ';

            // Khuyến mãi tự động: Giảm 100.000đ khi tổng giá trị đơn hàng vượt mốc 500.000đ để kích thích mua sắm
            const discountAmount = totalPrice > 500000 ? 100000 : 0;
            summaryItems[2].innerText = `-${formatCurrencyPrice(discountAmount)}đ`;

            const finalTotalPayment = Math.max(0, totalPrice - discountAmount);
            summaryItems[3].innerText = formatCurrencyPrice(finalTotalPayment) + 'đ';
        }

        attachCartEventListeners(cartItems);
    }

    // Đăng ký sự kiện tương tác tăng/giảm số lượng và xóa sản phẩm
    function attachCartEventListeners(cartItems) {
        const cartItemElements = cartLeftContainer.querySelectorAll('.cart-item');
        cartItemElements.forEach((itemElement) => {
            const itemIndex = parseInt(itemElement.dataset.index);
            const decreaseQuantityButton = itemElement.querySelector('.btn-qty-minus');
            const increaseQuantityButton = itemElement.querySelector('.btn-qty-plus');
            const deleteItemButton = itemElement.querySelector('.cart-item-delete');

            decreaseQuantityButton.addEventListener('click', () => {
                if (cartItems[itemIndex].quantity > 1) {
                    cartItems[itemIndex].quantity--;
                    persistCartData(cartItems);
                }
            });

            increaseQuantityButton.addEventListener('click', () => {
                cartItems[itemIndex].quantity++;
                persistCartData(cartItems);
            });

            deleteItemButton.addEventListener('click', () => {
                cartItems.splice(itemIndex, 1);
                persistCartData(cartItems);
            });
        });
    }

    // Cập nhật LocalStorage và kích hoạt đồng bộ dữ liệu giao diện đa thành phần
    function persistCartData(cartItems) {
        localStorage.setItem('the_fox_cart', JSON.stringify(cartItems));
        renderMainCart();

        // Đồng bộ dữ liệu ngược lại với Sidebar Cart giúp giao diện luôn nhất quán trên toàn bộ hệ thống
        if (typeof window.loadAndRenderSidebarCart === 'function') {
            window.loadAndRenderSidebarCart();
        }
    }

    function formatCurrencyPrice(amountNumber) {
        return amountNumber.toLocaleString('vi-VN');
    }

    if (proceedToCheckoutButton) {
        proceedToCheckoutButton.addEventListener('click', () => {
            window.location.href = 'checkout.php';
        });
    }

    if (continueShoppingButton) {
        continueShoppingButton.addEventListener('click', () => {
            window.location.href = 'cartegory.php';
        });
    }

    renderMainCart();
});
