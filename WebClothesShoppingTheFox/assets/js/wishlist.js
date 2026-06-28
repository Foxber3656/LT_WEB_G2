/* ==========================================================================
   THE FOX - Module Giao Diện Danh Sách Yêu Thích (Wishlist JS)
   Áp dụng chuẩn thiết kế phần mềm Clean Code & Senior Developer Architecture
   Tên biến/hàm: Tiếng Anh chuẩn | Chú thích (Comments): Tiếng Việt chuyên nghiệp
   ========================================================================== */

/* ======================================================
   BIẾN TRẠNG THÁI MODULE (MODULE STATE)
   Khai báo ngoài DOMContentLoaded để các hàm onclick
   trong HTML động luôn truy cập được dữ liệu này
====================================================== */
let _wishlistLoadedItems = [];

/* ======================================================
   XÓA SẢN PHẨM KHỎI DANH SÁCH YÊU THÍCH
   Được khai báo trên window TRƯỚC khi DOM render
   để tránh lỗi "function is not defined" khi onclick
   gọi hàm trước khi DOMContentLoaded hoàn tất
====================================================== */
window.removeWishlistItemFromPage = async function (productId) {
    if (!confirm("Bạn muốn bỏ sản phẩm này khỏi danh sách yêu thích?")) return;
    try {
        const removalResponse = await fetch("../routes/wishlist.php?action=remove_from_wishlist", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ product_id: parseInt(productId) })
        });

        // Kiểm tra HTTP status trước khi parse JSON để tránh lỗi 401/500 gây crash
        if (!removalResponse.ok) {
            const errorText = await removalResponse.text();
            console.error("[Wishlist] Lỗi HTTP " + removalResponse.status + ":", errorText);
            alert("Lỗi khi xóa sản phẩm. Vui lòng đăng nhập lại.");
            return;
        }

        const removalResult = await removalResponse.json();
        if (removalResult.success) {
            // Xóa trực tiếp khỏi DOM mà không cần reload toàn bộ danh sách — nhanh hơn
            const itemElement = document.querySelector(`.wish-item[data-id="${productId}"]`);
            if (itemElement) {
                itemElement.style.transition = "all 0.3s ease";
                itemElement.style.opacity = "0";
                itemElement.style.transform = "scale(0.9)";
                setTimeout(() => {
                    itemElement.remove();
                    // Nếu lưới trống thì hiển thị thông báo
                    const wishlistGridElement = document.getElementById("wishlistGrid");
                    if (wishlistGridElement && wishlistGridElement.querySelectorAll(".wish-item").length === 0) {
                        wishlistGridElement.innerHTML = `
                            <div style="grid-column: 1/-1; text-align: center; padding: 40px; color: var(--color-text-sub, #666);">
                                <i class="fa-regular fa-heart" style="font-size: 50px; color: #ccc; margin-bottom: 15px; display: block;"></i>
                                <p>Danh sách sản phẩm yêu thích của bạn đang trống.</p>
                                <a href="cartegory.php" style="color: var(--color-primary, #BF8A49); font-weight: bold; margin-top: 15px; display: inline-block;">Khám phá sản phẩm ngay</a>
                            </div>`;
                    }
                }, 300);
            }
            // Cập nhật danh sách nội bộ
            _wishlistLoadedItems = _wishlistLoadedItems.filter(item => parseInt(item.product_id) !== parseInt(productId));
        } else {
            alert(removalResult.message || "Không thể xóa sản phẩm.");
        }
    } catch (error) {
        console.error("[Wishlist] Lỗi kết nối:", error);
        alert("Lỗi kết nối khi xóa sản phẩm yêu thích.");
    }
};

/* ======================================================
   THÊM SẢN PHẨM YÊU THÍCH VÀO GIỎ HÀNG
   Khai báo trên window ngay lập tức tương tự như trên
====================================================== */
window.addWishlistItemToCart = function (productId, productName, productPrice, productImage) {
    const matchedItem = _wishlistLoadedItems.find(item => parseInt(item.product_id) === parseInt(productId));

    const name = productName || (matchedItem ? matchedItem.product_name : 'Sản phẩm yêu thích');
    const price = productPrice || (matchedItem ? parseFloat(matchedItem.price) : 790000);
    const image = productImage || (matchedItem
        ? (matchedItem.image.startsWith('..') ? matchedItem.image : '../assets/images/' + matchedItem.image)
        : '../assets/images/sp1.jpg');

    const cartPayload = {
        product_id: parseInt(productId),
        name: name,
        price: parseFloat(price),
        quantity: 1,
        color: 'Hồng Pastel',
        size: 'M',
        image: image
    };

    let localCartArray = JSON.parse(localStorage.getItem('the_fox_cart')) || [];
    const existingIndex = localCartArray.findIndex(item =>
        item.name === cartPayload.name &&
        item.color === cartPayload.color &&
        item.size === cartPayload.size
    );

    if (existingIndex > -1) {
        localCartArray[existingIndex].quantity += 1;
    } else {
        localCartArray.push(cartPayload);
    }

    localStorage.setItem('the_fox_cart', JSON.stringify(localCartArray));

    if (typeof loadAndRenderSidebarCart === 'function') {
        loadAndRenderSidebarCart();
    }

    const sidebarCartPanel = document.querySelector('.cart-sidebar');
    const sidebarCartOverlay = document.querySelector('.cart-overlay');
    if (sidebarCartPanel && sidebarCartOverlay) {
        sidebarCartPanel.classList.add('active');
        sidebarCartOverlay.classList.add('active');
    }
};

/* ======================================================
   KHỞI TẠO: TẢI VÀ HIỂN THỊ DANH SÁCH YÊU THÍCH
====================================================== */
document.addEventListener("DOMContentLoaded", async () => {
    const wishlistGridElement = document.getElementById("wishlistGrid");
    if (!wishlistGridElement) return;

    try {
        const wishlistResponse = await fetch("../routes/wishlist.php?action=get_wishlist");

        if (!wishlistResponse.ok) {
            console.error("[Wishlist] Lỗi HTTP:", wishlistResponse.status);
            if (wishlistResponse.status === 401) {
                window.location.href = "login.php";
                return;
            }
            wishlistGridElement.innerHTML = `<p style="color: var(--color-danger, #de3b3b); grid-column: 1/-1;">Lỗi tải dữ liệu. Vui lòng thử lại.</p>`;
            return;
        }

        const wishlistResult = await wishlistResponse.json();

        if (wishlistResult.success) {
            _wishlistLoadedItems = wishlistResult.data;

            if (_wishlistLoadedItems.length === 0) {
                wishlistGridElement.innerHTML = `
                    <div style="grid-column: 1/-1; text-align: center; padding: 40px; color: var(--color-text-sub, #666);">
                        <i class="fa-regular fa-heart" style="font-size: 50px; color: #ccc; margin-bottom: 15px; display: block;"></i>
                        <p>Danh sách sản phẩm yêu thích của bạn đang trống.</p>
                        <a href="cartegory.php" style="color: var(--color-primary, #BF8A49); font-weight: bold; margin-top: 15px; display: inline-block;">Khám phá sản phẩm ngay</a>
                    </div>`;
                return;
            }

            wishlistGridElement.innerHTML = _wishlistLoadedItems.map(wishlistItem => {
                const imageUrl = wishlistItem.image.startsWith('..') ? wishlistItem.image : '../assets/images/' + wishlistItem.image;
                // Escape dấu nháy đơn trong tên để tránh vỡ chuỗi onclick
                const safeName = wishlistItem.product_name.replace(/\\/g, '\\\\').replace(/'/g, "\\'");
                const safeImage = imageUrl.replace(/'/g, "\\'");
                return `
                    <div class="wish-item" data-id="${wishlistItem.product_id}">
                        <div class="wish-image">
                            <img src="${imageUrl}" alt="${wishlistItem.product_name}" loading="lazy">
                            <a href="javascript:void(0)"
                               onclick="removeWishlistItemFromPage(${wishlistItem.product_id})"
                               class="heart-btn"
                               title="Bỏ yêu thích">
                                <i class="fa-solid fa-heart"></i>
                            </a>
                        </div>
                        <h3>${wishlistItem.product_name}</h3>
                        <p class="product-price">${parseFloat(wishlistItem.price).toLocaleString('vi-VN')}đ</p>
                        <a href="javascript:void(0)"
                           onclick="addWishlistItemToCart(${wishlistItem.product_id}, '${safeName}', ${wishlistItem.price}, '${safeImage}')"
                           class="addCart-btn">
                            Thêm vào giỏ hàng
                        </a>
                        <a href="javascript:void(0)"
                           onclick="removeWishlistItemFromPage(${wishlistItem.product_id})"
                           class="delete-btn">
                            Xóa khỏi yêu thích
                        </a>
                    </div>
                `;
            }).join('');

        } else {
            wishlistGridElement.innerHTML = `<p style="color: var(--color-danger, #de3b3b); grid-column: 1/-1;">Lỗi tải dữ liệu: ${wishlistResult.message}</p>`;
        }
    } catch (error) {
        console.error("[Wishlist] Lỗi khi tải wishlist:", error);
        wishlistGridElement.innerHTML = `<p style="color: var(--color-danger, #de3b3b); grid-column: 1/-1;">Lỗi kết nối máy chủ.</p>`;
    }
});
