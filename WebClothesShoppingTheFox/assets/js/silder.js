const bannerImages = document.querySelectorAll('.banner-item img');
const bannerDots = document.querySelectorAll('.banner-dot');
let currentBanner = 0;
/* INIT */
bannerImages.forEach((img, index) => {
    img.style.opacity = index === 0 ? '1' : '0';

    img.style.transition = 'opacity .5s ease';
});
/* UPDATE */
function updateBanner() {
    bannerImages.forEach((img, index) => {
        img.style.opacity = index === currentBanner ? '1' : '0';
    });
    bannerDots.forEach((dot) => {
        dot.classList.remove('active');
    });
    bannerDots[currentBanner].classList.add('active');
}
/* NEXT */
document.querySelector('.banner-next').onclick = () => {
    currentBanner++;
    if (currentBanner >= bannerImages.length) {
        currentBanner = 0;
    }
    updateBanner();
};

/* PREV */
document.querySelector('.banner-prev').onclick = () => {
    currentBanner--;
    if (currentBanner < 0) {
        currentBanner = bannerImages.length - 1;
    }
    updateBanner();
};
bannerDots.forEach((dot, index) => {
    dot.onclick = () => {
        bannerImages = index;
        updateBanner();
    };
});
setInterval(() => {
    currentBanner++;
    if (currentBanner >= bannerImages.length) {
        currentBanner = 0;
    }
    updateBanner();
}, 5000);
const header = document.querySelector('#header');
