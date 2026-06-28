/* ==========================================================================
   THE FOX - Module Giỏ Hàng Trượt Nhanh (Sidebar Quick Cart JS)
   Áp dụng chuẩn thiết kế phần mềm Clean Code & Senior Developer
   Tên biến/hàm: Tiếng Anh chuẩn | Chú thích (Comments): Tiếng Việt chuyên nghiệp
   ========================================================================== */

document.addEventListener('DOMContentLoaded', () => {
    const cartSidebarElement = document.querySelector('.cart-sidebar');
    const cartOverlayElement = document.querySelector('.cart-overlay');
    const closeCartButton = document.querySelector('.close-cart');
    const cartItemsContainer = document.querySelector('.cart-items');
    const cartBadgeCountElement = document.querySelector('.cart-count');
    const totalPriceElement = document.querySelector('.cart-total-price');
    const cartIconButton = document.querySelector('.cart-icon-btn') || document.querySelector('.fa-shopping-bag');

    if (cartIconButton) {
        // Vô hiệu hóa điều hướng mặc định của thẻ link để tránh việc chuyển trang khi người dùng tương tác với biểu tượng giỏ hàng
        cartIconButton.setAttribute('href', 'javascript:void(0)');
        cartIconButton.classList.add('cart-icon-btn');

        cartIconButton.addEventListener('click', (clickEvent) => {
            clickEvent.preventDefault();
            loadAndRenderSidebarCart();
            openCartSidebar();
        });

        // Áp dụng cơ chế Debounce/Delay 300ms đối với sự kiện di chuột (hover) nhằm ngăn chặn việc giỏ hàng trượt mở vô tình khi người dùng lướt chuột qua thanh Header
        let hoverTimerId = null;
        cartIconButton.addEventListener('mouseenter', () => {
            hoverTimerId = setTimeout(() => {
                loadAndRenderSidebarCart();
                openCartSidebar();
            }, 300);
        });
        cartIconButton.addEventListener('mouseleave', () => {
            clearTimeout(hoverTimerId);
        });
    }

    function openCartSidebar() {
        if (cartSidebarElement && cartOverlayElement) {
            cartSidebarElement.classList.add('active');
            cartOverlayElement.classList.add('active');
        }
    }

    function closeCartSidebar() {
        if (cartSidebarElement && cartOverlayElement) {
            cartSidebarElement.classList.remove('active');
            cartOverlayElement.classList.remove('active');
        }
    }

    if (closeCartButton) closeCartButton.addEventListener('click', closeCartSidebar);
    if (cartOverlayElement) cartOverlayElement.addEventListener('click', closeCartSidebar);

    function loadAndRenderSidebarCart() {
        let cartItems = JSON.parse(localStorage.getItem('the_fox_cart')) || [];

        // Khởi tạo dữ liệu thử nghiệm ban đầu khi giỏ hàng trống giúp lập trình viên kiểm thử nhanh tính năng giao diện
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
                    quantity: 1,
                    color: 'Đỏ',
                    size: 'S',
                    image: '../assets/images/sp2.jpg',
                },
            ];
            localStorage.setItem('the_fox_cart', JSON.stringify(cartItems));
        }

        renderSidebarCartItems(cartItems);
    }

    // Đăng ký hàm lên window để các module JS khác có thể gọi đồng bộ dữ liệu giỏ hàng trượt từ xa
    window.loadAndRenderSidebarCart = loadAndRenderSidebarCart;

    function renderSidebarCartItems(cartItems) {
        if (!cartItemsContainer) return;
        cartItemsContainer.innerHTML = '';

        let totalItemCount = 0;
        let totalCartPrice = 0;

        cartItems.forEach((item, itemIndex) => {
            totalItemCount += item.quantity;
            totalCartPrice += item.price * item.quantity;

            const itemHtmlMarkup = `
                <div class="cart-item" data-index="${itemIndex}">
                    <div class="cart-item-img">
                        <img src="${item.image}" alt="${item.name}">
                    </div>
                    <div class="cart-item-info">
                        <h3>${item.name}</h3>
                        <p>Màu: ${item.color}</p>
                        <p>Size: ${item.size}</p>
                        <div class="cart-item-quantity">
                            <button class="btn-qty-minus">-</button>
                            <input type="text" value="${item.quantity}" readonly>
                            <button class="btn-qty-plus">+</button>
                        </div>
                    </div>
                    <div class="cart-item-right">
                        <p class="cart-item-price" data-price="${item.price}">
                            ${formatCurrencyPrice(item.price * item.quantity)}đ
                        </p>
                        <button class="cart-delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            cartItemsContainer.insertAdjacentHTML('beforeend', itemHtmlMarkup);
        });

        if (cartBadgeCountElement) cartBadgeCountElement.innerText = totalItemCount;
        if (totalPriceElement) totalPriceElement.innerText = formatCurrencyPrice(totalCartPrice) + 'đ';

        attachSidebarItemEvents(cartItems);
    }

    function attachSidebarItemEvents(cartItems) {
        const itemElements = cartItemsContainer.querySelectorAll('.cart-item');
        itemElements.forEach((itemElement) => {
            const itemIndex = parseInt(itemElement.dataset.index);
            const decreaseButton = itemElement.querySelector('.btn-qty-minus');
            const increaseButton = itemElement.querySelector('.btn-qty-plus');
            const deleteButton = itemElement.querySelector('.cart-delete');

            decreaseButton.addEventListener('click', () => {
                if (cartItems[itemIndex].quantity > 1) {
                    cartItems[itemIndex].quantity--;
                    persistAndRenderSidebarCart(cartItems);
                }
            });

            increaseButton.addEventListener('click', () => {
                cartItems[itemIndex].quantity++;
                persistAndRenderSidebarCart(cartItems);
            });

            deleteButton.addEventListener('click', () => {
                cartItems.splice(itemIndex, 1);
                persistAndRenderSidebarCart(cartItems);
            });
        });
    }

    function persistAndRenderSidebarCart(cartItems) {
        localStorage.setItem('the_fox_cart', JSON.stringify(cartItems));
        renderSidebarCartItems(cartItems);
    }

    function formatCurrencyPrice(amountNumber) {
        return amountNumber.toLocaleString('vi-VN');
    }

    loadAndRenderSidebarCart();
});
