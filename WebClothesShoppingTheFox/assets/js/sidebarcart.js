document.addEventListener('DOMContentLoaded', () => {
    const cartSidebar = document.querySelector('.cart-sidebar');
    const cartOverlay = document.querySelector('.cart-overlay');
    const closeCart = document.querySelector('.close-cart');
    const cartItemsContainer = document.querySelector('.cart-items');
    const cartCountEl = document.querySelector('.cart-count');
    const totalPriceEl = document.querySelector('.cart-total-price');
    const cartIconBtn = document.querySelector('.cart-icon-btn') || document.querySelector('.fa-shopping-bag');

    // 1. Lắng nghe sự kiện mở giỏ hàng từ Icon Header
    if (cartIconBtn) {
        // Đổi href thành javascript:void(0) để không bị nhảy trang
        cartIconBtn.setAttribute('href', 'javascript:void(0)');
        cartIconBtn.classList.add('cart-icon-btn');

        // Sự kiện Click
        cartIconBtn.addEventListener('click', (e) => {
            e.preventDefault();
            loadAndRenderSidebarCart();
            openSidebar();
        });

        // Sự kiện Hover (mouseenter)
        cartIconBtn.addEventListener('mouseenter', () => {
            loadAndRenderSidebarCart();
            openSidebar();
        });
    }

    // Phím tắt phụ 'c' để mở giỏ hàng trượt (giữ lại từ code gốc của nhóm)
    document.addEventListener('keydown', (e) => {
        if (e.key === 'c' || e.key === 'C') {
            loadAndRenderSidebarCart();
            openSidebar();
        }
    });

    function openSidebar() {
        if (cartSidebar && cartOverlay) {
            cartSidebar.classList.add('active');
            cartOverlay.classList.add('active');
        }
    }

    function closeSidebar() {
        if (cartSidebar && cartOverlay) {
            cartSidebar.classList.remove('active');
            cartOverlay.classList.remove('active');
        }
    }

    if (closeCart) closeCart.addEventListener('click', closeSidebar);
    if (cartOverlay) cartOverlay.addEventListener('click', closeSidebar);

    // 2. Hàm tải dữ liệu giỏ hàng từ localStorage và render động
    function loadAndRenderSidebarCart() {
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
                    quantity: 1,
                    color: 'Đỏ',
                    size: 'S',
                    image: '../assets/images/sp2.jpg',
                },
            ];
            localStorage.setItem('the_fox_cart', JSON.stringify(cart));
        }

        renderCartItems(cart);
    }

    // Đăng ký toàn cục để các trang khác gọi được
    window.loadAndRenderSidebarCart = loadAndRenderSidebarCart;

    function renderCartItems(cart) {
        if (!cartItemsContainer) return;
        cartItemsContainer.innerHTML = '';

        let totalCount = 0;
        let totalPrice = 0;

        cart.forEach((item, index) => {
            totalCount += item.quantity;
            totalPrice += item.price * item.quantity;

            const itemHTML = `
                <div class="cart-item" data-index="${index}">
                    <div class="cart-item-img">
                        <img src="${item.image}" alt="${item.name}">
                    </div>
                    <div class="cart-item-info">
                        <h3>${item.name}</h3>
                        <p>Màu: ${item.color}</p>
                        <p>Size: ${item.size}</p>
                        <div class="cart-item-quantity" style="display: flex; align-items: center; gap: 5px; margin-top: 5px;">
                            <button class="btn-qty-minus" style="width: 24px; height: 24px; border: 1px solid #ccc; background: #fff; cursor: pointer;">-</button>
                            <input type="text" value="${item.quantity}" readonly style="width: 30px; text-align: center; border: 1px solid #ccc; height: 24px; font-size: 12px;">
                            <button class="btn-qty-plus" style="width: 24px; height: 24px; border: 1px solid #ccc; background: #fff; cursor: pointer;">+</button>
                        </div>
                    </div>
                    <div class="cart-item-right" style="display: flex; flex-direction: column; align-items: flex-end; justify-content: space-between;">
                        <p class="cart-item-price" data-price="${item.price}">
                            ${formatPrice(item.price * item.quantity)}đ
                        </p>
                        <button class="cart-delete" style="background: none; border: none; color: #888; cursor: pointer; padding: 5px;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            cartItemsContainer.insertAdjacentHTML('beforeend', itemHTML);
        });

        // Cập nhật số lượng và tổng tiền hiển thị
        if (cartCountEl) cartCountEl.innerText = totalCount;
        if (totalPriceEl) totalPriceEl.innerText = formatPrice(totalPrice) + 'đ';

        // Gắn sự kiện cho các nút tăng giảm số lượng & xóa sản phẩm
        attachItemEventListeners(cart);
    }

    function attachItemEventListeners(cart) {
        const items = cartItemsContainer.querySelectorAll('.cart-item');
        items.forEach((itemEl) => {
            const index = parseInt(itemEl.dataset.index);
            const minusBtn = itemEl.querySelector('.btn-qty-minus');
            const plusBtn = itemEl.querySelector('.btn-qty-plus');
            const deleteBtn = itemEl.querySelector('.cart-delete');

            minusBtn.addEventListener('click', () => {
                if (cart[index].quantity > 1) {
                    cart[index].quantity--;
                    updateLocalStorageAndRender(cart);
                }
            });

            plusBtn.addEventListener('click', () => {
                cart[index].quantity++;
                updateLocalStorageAndRender(cart);
            });

            deleteBtn.addEventListener('click', () => {
                cart.splice(index, 1);
                updateLocalStorageAndRender(cart);
            });
        });
    }

    function updateLocalStorageAndRender(cart) {
        localStorage.setItem('the_fox_cart', JSON.stringify(cart));
        renderCartItems(cart);
    }

    function formatPrice(number) {
        return number.toLocaleString('vi-VN');
    }

    // Khởi chạy lấy dữ liệu & cập nhật hiển thị ban đầu
    loadAndRenderSidebarCart();
});
