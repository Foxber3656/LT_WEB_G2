/* ==========================================================================
   THE FOX - Module Thanh Điều Hướng & Điều Hướng Trang (Scroll & Navigation JS)
   Áp dụng chuẩn thiết kế phần mềm Clean Code & Senior Developer
   Tên biến/hàm: Tiếng Anh chuẩn | Chú thích (Comments): Tiếng Việt chuyên nghiệp
   ========================================================================== */

const mainHeaderElement = document.querySelector('#header');

if (mainHeaderElement) {
    /* ======================================================
       THAY ĐỔI GIAO DIỆN HEADER KHU DÙNG CUỘN TRANG (STICKY)
    ====================================================== */
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            mainHeaderElement.classList.add('active');
        } else {
            mainHeaderElement.classList.remove('active');
        }
    });

    /* HIỆU ỨNG RÊ CHUỘT (HOVER) GIỮ NỀN HEADER SÁNG */
    mainHeaderElement.addEventListener('mouseenter', () => {
        mainHeaderElement.classList.add('active');
    });

    mainHeaderElement.addEventListener('mouseleave', () => {
        if (window.scrollY <= 50) {
            mainHeaderElement.classList.remove('active');
        }
    });
}

/* ======================================================
   TỰ ĐỘNG HIGHLIGHT VÀ ĐỊNH TỰ ĐỘNG ĐƯỜNG DẪN NAVIGATION
====================================================== */
document.addEventListener('DOMContentLoaded', () => {
    const currentFullUrl = window.location.href;
    const currentActivePageName = currentFullUrl.split('/').pop().split('?')[0].split('#')[0] || 'home.php';

    // 1. Cập nhật đường dẫn Logo mặc định dẫn về Trang Chủ (home.php)
    const headerLogoLinkElements = document.querySelectorAll('.header-logo a');
    headerLogoLinkElements.forEach((singleLink) => {
        singleLink.setAttribute('href', 'home.php');
    });

    // 2. Định ánh xạ danh mục sản phẩm từ Tiếng Việt sang tham số Query URL
    const navigationMenuLinksList = document.querySelectorAll('.menu > li > a');
    const categorySlugMappingTable = {
        NỮ: 'nu',
        NAM: 'nam',
        'TRẺ EM': 'tre-em',
        'PHỤ KIỆN': 'phu-kien',
        'BỘ SƯU TẬP': 'bo-suu-tap',
        SALE: 'sale',
        'THƯƠNG HIỆU': 'thuong-hieu',
    };
    navigationMenuLinksList.forEach((singleLink) => {
        const linkLabelText = singleLink.textContent.trim().toUpperCase();
        if (categorySlugMappingTable[linkLabelText]) {
            singleLink.setAttribute('href', `cartegory.php?cat=${categorySlugMappingTable[linkLabelText]}`);
        }
    });

    // 3. Kiểm tra trạng thái Session linh hoạt để gắn liên kết Hồ sơ (profile.php) hoặc Đăng nhập (login.php)
    const userHeaderIconLinkElements = document.querySelectorAll('.header-action a.fa-user');
    userHeaderIconLinkElements.forEach(async (singleLink) => {
        try {
            const checkSessionResponse = await fetch('../routes/auth.php?action=check');
            const checkSessionResultData = await checkSessionResponse.json();
            if (checkSessionResultData.success && checkSessionResultData.logged_in) {
                singleLink.setAttribute('href', 'profile.php');
            } else {
                singleLink.setAttribute('href', 'login.php');
            }
        } catch (error) {
            singleLink.setAttribute('href', 'login.php');
        }
    });

    // 4. Đánh dấu nổi bật (Highlight CSS) liên kết trang/danh mục hiện tại đang được truy cập
    const urlSearchParameters = new URLSearchParams(window.location.search);
    let activeCategorySlug = urlSearchParameters.get('cat');
    if (currentActivePageName === 'cartegory.php' && !activeCategorySlug) {
        activeCategorySlug = 'nu';
    }

    const allNavigationLinksList = document.querySelectorAll('.menu > li > a');
    allNavigationLinksList.forEach((singleLink) => {
        const hrefAttributeValue = singleLink.getAttribute('href');
        if (hrefAttributeValue && hrefAttributeValue !== '#' && hrefAttributeValue !== 'javascript:void(0)') {
            const extractedLinkPage = hrefAttributeValue.split('?')[0].split('/').pop();
            const extractedLinkQuery = hrefAttributeValue.split('?')[1] || '';
            const extractedLinkParams = new URLSearchParams(extractedLinkQuery);
            const extractedLinkCategory = extractedLinkParams.get('cat');

            if (currentActivePageName === extractedLinkPage) {
                if (extractedLinkCategory === activeCategorySlug) {
                    singleLink.classList.add('active-nav-link');
                }
            }
        }
    });
});
