// Module Giao Diện Trang Chi Tiết Sản Phẩm (Product Details JS)

const urlSearchParameters = new URLSearchParams(window.location.search);
const targetProductNameFromUrl = urlSearchParameters.get('name');
const targetProductPriceFromUrl = urlSearchParameters.get('price');
const targetProductImageFromUrl = urlSearchParameters.get('image');

// Đọc dữ liệu từ URL Query Parameters để cập nhật thông tin sản phẩm linh hoạt
if (targetProductNameFromUrl) {
    const productNameHeadingElement = document.querySelector('.product-content-right-name h1');
    if (productNameHeadingElement) productNameHeadingElement.innerText = targetProductNameFromUrl;
    const breadcrumbCurrentPageElement = document.querySelector('.product-top p:last-child');
    if (breadcrumbCurrentPageElement) breadcrumbCurrentPageElement.innerText = targetProductNameFromUrl;
}
if (targetProductPriceFromUrl) {
    const productPriceDisplayElement = document.querySelector('.product-content-right-price p');
    if (productPriceDisplayElement) productPriceDisplayElement.innerText = targetProductPriceFromUrl;
}
if (targetProductImageFromUrl) {
    const productBigImageElement = document.querySelector('.product-content-left-big-img img');
    if (productBigImageElement) productBigImageElement.src = targetProductImageFromUrl;
    const firstThumbnailImageElement = document.querySelector('.product-content-left-small-img img:first-child');
    if (firstThumbnailImageElement) firstThumbnailImageElement.src = targetProductImageFromUrl;
}

const productBottomSectionContainer = document.querySelector('.product-content-right-bottom');
const productBottomToggleTriggerButton = document.querySelector('.product-content-right-bottom-toggle');
const productBottomContentSection = document.querySelector('.product-content-right-bottom-content');

if (productBottomToggleTriggerButton) {
    productBottomToggleTriggerButton.addEventListener('click', () => {
        productBottomSectionContainer.classList.toggle('active');
    });
}

//CHUYỂN ĐỔI TAB NỘI DUNG MÔ TẢ & BẢO QUẢN SẢN PHẨM

const productTabNavigationItems = document.querySelectorAll('.product-content-right-bottom-top-item');
const productTabContentPanels = document.querySelectorAll('.product-tab-content');
productTabNavigationItems.forEach((tabItem, tabIndex) => {
    tabItem.addEventListener('click', () => {
        productTabNavigationItems.forEach((item) => {
            item.classList.remove('active');
        });
        productTabContentPanels.forEach((panel) => {
            panel.classList.remove('active');
        });
        tabItem.classList.add('active');
        productTabContentPanels[tabIndex].classList.add('active');
    });
});

//TĂNG GIẢM SỐ LƯỢNG MUA SẢN PHẨM

const decreaseQuantityButton = document.querySelector('.quantity-box button:first-child');
const increaseQuantityButton = document.querySelector('.quantity-box button:last-child');
const productQuantityInputField = document.querySelector('.quantity-box input');

if (decreaseQuantityButton && increaseQuantityButton && productQuantityInputField) {
    increaseQuantityButton.addEventListener('click', () => {
        let currentQuantityValue = parseInt(productQuantityInputField.value);
        currentQuantityValue++;
        productQuantityInputField.value = currentQuantityValue;
    });

    decreaseQuantityButton.addEventListener('click', () => {
        let currentQuantityValue = parseInt(productQuantityInputField.value);
        if (currentQuantityValue > 1) {
            currentQuantityValue--;
        }
        productQuantityInputField.value = currentQuantityValue;
    });
}

/* ======================================================
   QUẢN LÝ SLIDER HÌNH ẢNH SẢN PHẨM
====================================================== */
const productMainBigImage = document.querySelector('.product-content-left-big-img img');
const productThumbnailImagesList = document.querySelectorAll('.product-content-left-small-img img');
const previousImageSlideButton = document.querySelector('.prev');
const nextImageSlideButton = document.querySelector('.next');

let activeThumbnailIndex = 0;

productThumbnailImagesList.forEach((thumbnailImg, thumbnailIndex) => {
    thumbnailImg.addEventListener('click', () => {
        activeThumbnailIndex = thumbnailIndex;
        updateActiveProductImage();
    });
});

function updateActiveProductImage() {
    if (!productMainBigImage || productThumbnailImagesList.length === 0) return;
    productMainBigImage.src = productThumbnailImagesList[activeThumbnailIndex].src;
    productThumbnailImagesList.forEach((item) => {
        item.classList.remove('active');
    });
    if (productThumbnailImagesList[activeThumbnailIndex]) {
        productThumbnailImagesList[activeThumbnailIndex].classList.add('active');
    }
}

if (nextImageSlideButton) {
    nextImageSlideButton.addEventListener('click', () => {
        activeThumbnailIndex++;
        if (activeThumbnailIndex >= productThumbnailImagesList.length) {
            activeThumbnailIndex = 0;
        }
        updateActiveProductImage();
    });
}

if (previousImageSlideButton) {
    previousImageSlideButton.addEventListener('click', () => {
        activeThumbnailIndex--;
        if (activeThumbnailIndex < 0) {
            activeThumbnailIndex = productThumbnailImagesList.length - 1;
        }
        updateActiveProductImage();
    });
}
updateActiveProductImage();

/* ======================================================
   CUỘN DANH SÁCH ẢNH THUMBNAIL
====================================================== */
const thumbnailListElement = document.querySelector('.product-content-left-small-img');
const thumbnailViewportContainer = document.querySelector('.thumb-container');
const scrollUpArrowButton = document.querySelector('.thumb-arrow.up');
const scrollDownArrowButton = document.querySelector('.thumb-arrow.down');
let verticalScrollPosition = 0;

const firstThumbnailElement = document.querySelector('.product-content-left-small-img img');
const thumbnailGapPixels = 12;
const scrollStepPixels = firstThumbnailElement ? firstThumbnailElement.offsetHeight + thumbnailGapPixels : 100;

if (scrollDownArrowButton && thumbnailListElement && thumbnailViewportContainer) {
    scrollDownArrowButton.addEventListener('click', () => {
        const maximumScrollLimit = thumbnailListElement.scrollHeight - thumbnailViewportContainer.clientHeight;
        verticalScrollPosition += scrollStepPixels;
        if (verticalScrollPosition > maximumScrollLimit) {
            verticalScrollPosition = maximumScrollLimit;
        }
        thumbnailListElement.style.transform = `translateY(-${verticalScrollPosition}px)`;
    });
}

if (scrollUpArrowButton && thumbnailListElement) {
    scrollUpArrowButton.addEventListener('click', () => {
        verticalScrollPosition -= scrollStepPixels;
        if (verticalScrollPosition < 0) {
            verticalScrollPosition = 0;
        }
        thumbnailListElement.style.transform = `translateY(-${verticalScrollPosition}px)`;
    });
}

/* ======================================================
   LỰA CHỌN KÍCH CỠ (SIZE) SẢN PHẨM
====================================================== */
const availableProductSizesList = document.querySelectorAll('.size span');
availableProductSizesList.forEach((sizeElement) => {
    sizeElement.addEventListener('click', () => {
        availableProductSizesList.forEach((item) => {
            item.classList.remove('active');
        });
        sizeElement.classList.add('active');
    });
});

/* ======================================================
   HÀM TIỆN ÍCH: THU THẬP THÔNG TIN SẢN PHẨM HIỆN TẠI
====================================================== */
function collectCurrentProductPayload() {
    const productNameText = document.querySelector('.product-content-right-name h1')?.innerText || 'Áo Kiểu Fox Summer';
    const rawPriceString = document.querySelector('.product-content-right-price p')?.innerText || '790.000đ';
    const parsedNumericPrice = parseInt(rawPriceString.replace(/[^\d]/g, '')) || 790000;
    const activeSizeSpan = document.querySelector('.size span.active');
    const selectedSizeText = activeSizeSpan ? activeSizeSpan.innerText : 'M';
    const productColorContainer = document.querySelector('.product-content-right-color p');
    let selectedColorText = 'Hồng Pastel';
    if (productColorContainer) {
        selectedColorText = productColorContainer.innerText.replace('Màu:', '').trim();
    }
    const selectedQuantityNumber = parseInt(document.querySelector('.quantity-box input')?.value) || 1;
    const currentProductImageUrl =
        document.querySelector('.product-content-left-big-img img')?.src || '../assets/images/sp1.jpg';
    return {
        product_id: 1,
        name: productNameText,
        price: parsedNumericPrice,
        quantity: selectedQuantityNumber,
        color: selectedColorText,
        size: selectedSizeText,
        image: currentProductImageUrl,
    };
}

/* ======================================================
   THÊM VÀO GIỎ HÀNG
====================================================== */
const addToCartActionButton = document.querySelector('.product-content-right-button button:first-child');
const buyNowActionButton = document.querySelector('.product-content-right-button button:nth-child(2)');

if (addToCartActionButton) {
    addToCartActionButton.addEventListener('click', () => {
        const selectedProductPayload = collectCurrentProductPayload();
        let localCartArray = JSON.parse(localStorage.getItem('the_fox_cart')) || [];
        const existingItemIndex = localCartArray.findIndex(
            (item) =>
                item.name === selectedProductPayload.name &&
                item.color === selectedProductPayload.color &&
                item.size === selectedProductPayload.size
        );
        if (existingItemIndex > -1) {
            localCartArray[existingItemIndex].quantity += selectedProductPayload.quantity;
        } else {
            localCartArray.push(selectedProductPayload);
        }
        localStorage.setItem('the_fox_cart', JSON.stringify(localCartArray));
        if (typeof loadAndRenderSidebarCart === 'function') loadAndRenderSidebarCart();
        const sidebarCartPanel = document.querySelector('.cart-sidebar');
        const sidebarCartOverlay = document.querySelector('.cart-overlay');
        if (sidebarCartPanel && sidebarCartOverlay) {
            sidebarCartPanel.classList.add('active');
            sidebarCartOverlay.classList.add('active');
        }
    });
}

/* ======================================================
   MUA NGAY: Thêm vào giỏ ngầm, chuyển thẳng sang thanh toán
   Không bật Sidebar Cart để tránh bước thừa
====================================================== */
if (buyNowActionButton) {
    buyNowActionButton.addEventListener('click', () => {
        const selectedProductPayload = collectCurrentProductPayload();
        let localCartArray = JSON.parse(localStorage.getItem('the_fox_cart')) || [];
        const existingItemIndex = localCartArray.findIndex(
            (item) =>
                item.name === selectedProductPayload.name &&
                item.color === selectedProductPayload.color &&
                item.size === selectedProductPayload.size
        );
        if (existingItemIndex > -1) {
            localCartArray[existingItemIndex].quantity += selectedProductPayload.quantity;
        } else {
            localCartArray.push(selectedProductPayload);
        }
        localStorage.setItem('the_fox_cart', JSON.stringify(localCartArray));
        // Chuyển thẳng đến trang thanh toán mà không mở sidebar giỏ hàng
        window.location.href = 'checkout.php';
    });
}

/* ======================================================
   NÚT TRÁI TIM: CHỈ THÊM VÀO YÊU THÍCH (KHÔNG XÓA)
   Xóa khỏi yêu thích chỉ được phép trong trang wishlist.php
====================================================== */
document.addEventListener('DOMContentLoaded', () => {
    const productNameHeadingElement = document.querySelector('.product-content-right-name h1');
    const activeProductName =
        targetProductNameFromUrl ||
        (productNameHeadingElement ? productNameHeadingElement.innerText.trim() : 'Áo kiểu Fox Summer');

    const heartButton = document.getElementById('wishlistBtn');
    if (!heartButton) return;

    // Cập nhật trạng thái icon trái tim (đỏ = đã yêu thích, rỗng = chưa)
    function setHeartState(isLiked) {
        const iconEl = heartButton.querySelector('i');
        if (!iconEl) return;
        if (isLiked) {
            iconEl.className = 'fas fa-heart';
            iconEl.style.color = '#de3b3b';
            heartButton.title = 'Đã thêm vào yêu thích';
        } else {
            iconEl.className = 'far fa-heart';
            iconEl.style.color = '';
            heartButton.title = 'Thêm vào yêu thích';
        }
    }

    // Hiển thị toast nhỏ không chặn UI
    function showToast(message, color) {
        const old = document.getElementById('wishlistToast');
        if (old) old.remove();
        const toast = document.createElement('div');
        toast.id = 'wishlistToast';
        toast.textContent = message;
        toast.style.cssText = `position:fixed;top:90px;right:20px;background:${color || '#BF8A49'};color:#fff;padding:12px 22px;border-radius:8px;z-index:99999;font-size:14px;font-weight:600;box-shadow:0 4px 16px rgba(0,0,0,0.15);transition:opacity 0.3s;`;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 2500);
    }

    // Kiểm tra trạng thái yêu thích khi tải trang
    (async () => {
        try {
            const sessRes = await fetch('../routes/auth.php?action=check');
            const sessData = await sessRes.json();
            if (sessData.logged_in) {
                const statusRes = await fetch(
                    `../routes/wishlist.php?action=check_status_by_name&product_name=${encodeURIComponent(activeProductName)}`
                );
                const statusData = await statusRes.json();
                if (statusData.success && statusData.is_in_wishlist) {
                    setHeartState(true);
                }
            }
        } catch (e) {
            /* im lặng khi trang chưa sẵn sàng */
        }
    })();

    // Click trái tim → CHỈ THÊM vào yêu thích, không bao giờ xóa
    heartButton.addEventListener('click', async () => {
        try {
            const sessRes = await fetch('../routes/auth.php?action=check');
            const sessData = await sessRes.json();

            if (!sessData.logged_in) {
                alert('Vui lòng đăng nhập để lưu sản phẩm vào danh sách yêu thích.');
                window.location.href = 'login.php';
                return;
            }

            // Kiểm tra nếu đã có trong wishlist rồi → thông báo, không làm gì thêm
            const statusRes = await fetch(
                `../routes/wishlist.php?action=check_status_by_name&product_name=${encodeURIComponent(activeProductName)}`
            );
            const statusData = await statusRes.json();
            if (statusData.success && statusData.is_in_wishlist) {
                showToast('💛 Sản phẩm đã có trong danh sách yêu thích!', '#888');
                setHeartState(true);
                return;
            }

            // Chưa có → gọi toggle_by_name để thêm mới (lúc này chắc chắn chưa có nên sẽ ADD)
            const addRes = await fetch('../routes/wishlist.php?action=toggle_by_name', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ product_name: activeProductName }),
            });
            const addData = await addRes.json();

            if (addData.success) {
                setHeartState(addData.is_in_wishlist);
                showToast(
                    addData.is_in_wishlist ? 'Đã thêm vào danh sách yêu thích!' : addData.message,
                    addData.is_in_wishlist ? '#BF8A49' : '#888'
                );
            } else {
                alert(addData.message || 'Có lỗi xảy ra.');
            }
        } catch (error) {
            console.error('[Wishlist] Lỗi:', error);
            alert('Đã xảy ra lỗi. Vui lòng thử lại.');
        }
    });
});
