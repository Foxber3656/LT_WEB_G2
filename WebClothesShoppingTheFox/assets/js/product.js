// Parse URL query parameters to dynamically load product info
const urlParams = new URLSearchParams(window.location.search);
const pName = urlParams.get('name');
const pPrice = urlParams.get('price');
const pImage = urlParams.get('image');

if (pName) {
    const titleEl = document.querySelector('.product-content-right-name h1');
    if (titleEl) titleEl.innerText = pName;
    const breadcrumbEl = document.querySelector('.product-top p:last-child');
    if (breadcrumbEl) breadcrumbEl.innerText = pName;
}
if (pPrice) {
    const priceEl = document.querySelector('.product-content-right-price p');
    if (priceEl) priceEl.innerText = pPrice;
}
if (pImage) {
    const bigImgEl = document.querySelector('.product-content-left-big-img img');
    if (bigImgEl) bigImgEl.src = pImage;
    const firstThumb = document.querySelector('.product-content-left-small-img img:first-child');
    if (firstThumb) firstThumb.src = pImage;
}

const productBottom = document.querySelector('.product-content-right-bottom');
const toggleBtn = document.querySelector('.product-content-right-bottom-toggle');
const productContent = document.querySelector('.product-content-right-bottom-content');

if (toggleBtn) {
    toggleBtn.addEventListener('click', () => {
        productBottom.classList.toggle('active');
    });
}

/*=====================PRODUCT TAB======================*/
const tabs = document.querySelectorAll('.product-content-right-bottom-top-item');
const contents = document.querySelectorAll('.product-tab-content');
tabs.forEach((tab, index) => {
    tab.addEventListener('click', () => {
        tabs.forEach((item) => {
            item.classList.remove('active');
        });

        contents.forEach((item) => {
            item.classList.remove('active');
        });

        tab.classList.add('active');

        contents[index].classList.add('active');
    });
});

/*=====================QUANTITY======================*/
const minusBtn = document.querySelector('.quantity-box button:first-child');
const plusBtn = document.querySelector('.quantity-box button:last-child');
const quantityInput = document.querySelector('.quantity-box input');

if (minusBtn && plusBtn && quantityInput) {
    plusBtn.addEventListener('click', () => {
        let value = parseInt(quantityInput.value);
        value++;
        quantityInput.value = value;
    });

    minusBtn.addEventListener('click', () => {
        let value = parseInt(quantityInput.value);
        if (value > 1) {
            value--;
        }
        quantityInput.value = value;
    });
}

/*=====================PRODUCT IMAGE======================*/
const bigImage = document.querySelector('.product-content-left-big-img img');
const thumbnails = document.querySelectorAll('.product-content-left-small-img img');
const prevBtn = document.querySelector('.prev');
const nextBtn = document.querySelector('.next');

let currentIndex = 0;

/* CLICK THUMBNAIL */
thumbnails.forEach((img, index) => {
    img.addEventListener('click', () => {
        currentIndex = index;
        updateImage();
    });
});

/* UPDATE */
function updateImage() {
    bigImage.src = thumbnails[currentIndex].src;
    thumbnails.forEach((item) => {
        item.classList.remove('active');
    });
    thumbnails[currentIndex].classList.add('active');
}

/* NEXT */
nextBtn.addEventListener('click', () => {
    currentIndex++;
    if (currentIndex >= thumbnails.length) {
        currentIndex = 0;
    }
    updateImage();
});

/* PREV */
prevBtn.addEventListener('click', () => {
    currentIndex--;
    if (currentIndex < 0) {
        currentIndex = thumbnails.length - 1;
    }
    updateImage();
});
updateImage();

/*=====================THUMB SCROLL======================*/
const thumbList = document.querySelector('.product-content-left-small-img');
const thumbContainer = document.querySelector('.thumb-container');
const upBtn = document.querySelector('.thumb-arrow.up');
const downBtn = document.querySelector('.thumb-arrow.down');
let scrollPosition = 0;

const firstThumb = document.querySelector('.product-content-left-small-img img');
const gap = 12;
const scrollStep = firstThumb.offsetHeight + gap;

/* DOWN */
downBtn.addEventListener('click', () => {
    const maxScroll = thumbList.scrollHeight - thumbContainer.clientHeight;
    scrollPosition += scrollStep;

    if (scrollPosition > maxScroll) {
        scrollPosition = maxScroll;
    }

    thumbList.style.transform = `translateY(-${scrollPosition}px)`;
});

/* UP */
upBtn.addEventListener('click', () => {
    scrollPosition -= scrollStep;
    if (scrollPosition < 0) {
        scrollPosition = 0;
    }
    thumbList.style.transform = `translateY(-${scrollPosition}px)`;
});

/*=====================PRODUCT SIZE======================*/
const sizes = document.querySelectorAll('.size span');

sizes.forEach((size) => {
    size.addEventListener('click', () => {
        sizes.forEach((item) => {
            item.classList.remove('active');
        });

        size.classList.add('active');
    });
});

/*===================== ADD TO CART & BUY NOW ======================*/
const addToCartBtn = document.querySelector('.product-content-right-button button:first-child');
const buyNowBtn = document.querySelector('.product-content-right-button button:nth-child(2)');

if (addToCartBtn) {
    addToCartBtn.addEventListener('click', () => {
        const productName = document.querySelector('.product-content-right-name h1').innerText;
        const rawPrice = document.querySelector('.product-content-right-price p').innerText;
        const price = parseInt(rawPrice.replace(/[^\d]/g, ''));
        const size = document.querySelector('.size span.active').innerText;

        // Lấy tên màu từ text: "Màu: Hồng Pastel" -> "Hồng Pastel"
        const colorContainer = document.querySelector('.product-content-right-color p');
        let color = 'Hồng Pastel';
        if (colorContainer) {
            color = colorContainer.innerText.replace('Màu:', '').trim();
        }

        const quantity = parseInt(document.querySelector('.quantity-box input').value) || 1;
        const image = document.querySelector('.product-content-left-big-img img').src;

        // Tạo đối tượng sản phẩm
        const productItem = {
            product_id: 1, // Mặc định ID sản phẩm cho trang demo
            name: productName,
            price: price,
            quantity: quantity,
            color: color,
            size: size,
            image: image,
        };

        // Thêm vào localStorage
        let cart = JSON.parse(localStorage.getItem('the_fox_cart')) || [];

        // Kiểm tra xem sản phẩm có cùng phân loại đã tồn tại chưa
        const existingIndex = cart.findIndex(
            (item) =>
                item.name === productItem.name && item.color === productItem.color && item.size === productItem.size
        );
        if (existingIndex > -1) {
            cart[existingIndex].quantity += quantity;
        } else {
            cart.push(productItem);
        }

        localStorage.setItem('the_fox_cart', JSON.stringify(cart));

        // Kích hoạt cập nhật hiển thị giỏ hàng trượt và mở nó ra
        if (typeof loadAndRenderSidebarCart === 'function') {
            loadAndRenderSidebarCart();
        } else {
            // Phát sự kiện hoặc kích hoạt trực tiếp nếu file sidebarcart.js đã định nghĩa
            const event = new CustomEvent('cartUpdated');
            window.dispatchEvent(event);
        }

        // Mở sidebar cart
        const cartSidebar = document.querySelector('.cart-sidebar');
        const cartOverlay = document.querySelector('.cart-overlay');
        if (cartSidebar && cartOverlay) {
            cartSidebar.classList.add('active');
            cartOverlay.classList.add('active');
        }
    });
}

if (buyNowBtn) {
    buyNowBtn.addEventListener('click', () => {
        // Kích hoạt click thêm vào giỏ hàng trước, sau đó chuyển hướng thẳng sang trang thanh toán
        if (addToCartBtn) {
            addToCartBtn.click();
            setTimeout(() => {
                window.location.href = 'checkout.php';
            }, 300);
        }
    });
}
