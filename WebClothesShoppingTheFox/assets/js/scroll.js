/* SCROLL */
window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
        header.classList.add('active');
    } else {
        header.classList.remove('active');
    }
});
/* HOVER */
header.addEventListener('mouseenter', () => {
    header.classList.add('active');
});

header.addEventListener('mouseleave', () => {
    if (window.scrollY <= 50) {
        header.classList.remove('active');
    }
});

/*===================== ACTIVE NAVBAR LINK HIGHLIGHTER & ROUTING ======================*/
document.addEventListener('DOMContentLoaded', () => {
    const currentUrl = window.location.href;
    const pageName = currentUrl.split('/').pop().split('?')[0].split('#')[0] || 'home.php';

    // 1. Cập nhật link Logo về home.php
    const logoLinks = document.querySelectorAll('.header-logo a');
    logoLinks.forEach((link) => {
        link.setAttribute('href', 'home.php');
    });

    // 2. Cập nhật các menu link chính về cartegory.php với tham số phân loại
    const menuLinks = document.querySelectorAll('.menu > li > a');
    const catMap = {
        NỮ: 'nu',
        NAM: 'nam',
        'TRẺ EM': 'tre-em',
        'PHỤ KIỆN': 'phu-kien',
        'BỘ SƯU TẬP': 'bo-suu-tap',
        SALE: 'sale',
        'THƯƠNG HIỆU': 'thuong-hieu',
    };
    menuLinks.forEach((link) => {
        const text = link.textContent.trim().toUpperCase();
        if (catMap[text]) {
            link.setAttribute('href', `cartegory.php?cat=${catMap[text]}`);
        }
    });

    // 3. Cập nhật user link sang trang quản lý đơn hàng order.php
    const userLinks = document.querySelectorAll('.header-action a.fa-user');
    userLinks.forEach((link) => {
        link.setAttribute('href', 'order.php');
    });

    // 4. Highlight mục đang được chọn dựa trên pageName và query param ?cat=
    const urlParams = new URLSearchParams(window.location.search);
    let activeCat = urlParams.get('cat');
    if (pageName === 'cartegory.php' && !activeCat) {
        activeCat = 'nu'; // mặc định là Nữ khi vào trang cartegory.php trực tiếp
    }

    const allNavLinks = document.querySelectorAll('.menu > li > a');
    allNavLinks.forEach((link) => {
        const href = link.getAttribute('href');
        if (href && href !== '#' && href !== 'javascript:void(0)') {
            // Phân tách href
            const linkPage = href.split('?')[0].split('/').pop();
            const linkQuery = href.split('?')[1] || '';
            const linkParams = new URLSearchParams(linkQuery);
            const linkCat = linkParams.get('cat');

            if (pageName === linkPage) {
                if (linkCat === activeCat) {
                    link.classList.add('active-nav-link');
                }
            }
        }
    });
});
