/* ==========================================================================
   THE FOX - Module Quản Lý Banner Slider Trang Chủ (Homepage Slider JS)
   Áp dụng chuẩn thiết kế phần mềm Clean Code & Senior Developer
   Tên biến/hàm: Tiếng Anh chuẩn | Chú thích (Comments): Tiếng Việt chuyên nghiệp
   ========================================================================== */

const bannerImageElementsList = document.querySelectorAll('.banner-item img');
const bannerNavigationDotElementsList = document.querySelectorAll('.banner-dot');
const nextBannerSlideButton = document.querySelector('.banner-next');
const previousBannerSlideButton = document.querySelector('.banner-prev');

let activeBannerSlideIndex = 0;

// Khởi tạo trạng thái ban đầu cho danh sách ảnh banner (Hiển thị ảnh đầu tiên, ẩn các ảnh còn lại bằng mờ đục transition)
bannerImageElementsList.forEach((imageItem, imageIndex) => {
    imageItem.style.opacity = imageIndex === 0 ? '1' : '0';
    imageItem.style.transition = 'opacity .5s ease';
});

// Cập nhật hiển thị Banner và trạng thái Active của các chấm tròn điều hướng
function updateActiveBannerSlideDisplay() {
    bannerImageElementsList.forEach((imageItem, imageIndex) => {
        imageItem.style.opacity = imageIndex === activeBannerSlideIndex ? '1' : '0';
    });
    bannerNavigationDotElementsList.forEach((dotItem) => {
        dotItem.classList.remove('active');
    });
    if (bannerNavigationDotElementsList[activeBannerSlideIndex]) {
        bannerNavigationDotElementsList[activeBannerSlideIndex].classList.add('active');
    }
}

if (nextBannerSlideButton) {
    nextBannerSlideButton.onclick = () => {
        activeBannerSlideIndex++;
        if (activeBannerSlideIndex >= bannerImageElementsList.length) {
            activeBannerSlideIndex = 0;
        }
        updateActiveBannerSlideDisplay();
    };
}

if (previousBannerSlideButton) {
    previousBannerSlideButton.onclick = () => {
        activeBannerSlideIndex--;
        if (activeBannerSlideIndex < 0) {
            activeBannerSlideIndex = bannerImageElementsList.length - 1;
        }
        updateActiveBannerSlideDisplay();
    };
}

bannerNavigationDotElementsList.forEach((dotItem, dotIndex) => {
    dotItem.onclick = () => {
        activeBannerSlideIndex = dotIndex;
        updateActiveBannerSlideDisplay();
    };
});

// Thiết lập tự động chuyển Slide Banner sau mỗi chu kỳ 5 giây
setInterval(() => {
    if (bannerImageElementsList.length === 0) return;
    activeBannerSlideIndex++;
    if (activeBannerSlideIndex >= bannerImageElementsList.length) {
        activeBannerSlideIndex = 0;
    }
    updateActiveBannerSlideDisplay();
}, 5000);
