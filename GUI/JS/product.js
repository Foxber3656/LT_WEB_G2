
const productBottom =
document.querySelector('.product-content-right-bottom');
const toggleBtn =
document.querySelector('.product-content-right-bottom-toggle');
const productContent =
document.querySelector('.product-content-right-bottom-content');

if(toggleBtn){
    toggleBtn.addEventListener(
    'click',
    ()=>{
        productBottom.classList.toggle('active');
    });

}

/*=====================PRODUCT TAB======================*/
const tabs=
document.querySelectorAll('.product-content-right-bottom-top-item');
const contents=
document.querySelectorAll('.product-tab-content');
tabs.forEach((tab,index)=>{

    tab.addEventListener('click',
        ()=>{

        tabs.forEach(item=>{
            item.classList.remove('active');
        });

        contents.forEach(item=>{
            item.classList.remove('active');
        });

        tab.classList.add('active');

        contents[index]
        .classList.add('active');
    });

});

/*=====================QUANTITY======================*/
const minusBtn=
document.querySelector('.quantity-box button:first-child');
const plusBtn=
document.querySelector('.quantity-box button:last-child');
const quantityInput=
document.querySelector('.quantity-box input');

if(
minusBtn &&
plusBtn &&
quantityInput
){
    plusBtn.addEventListener(
    'click',
    ()=>{
        let value=
        parseInt(quantityInput.value);
        value++;
        quantityInput.value=
        value;
    });

    minusBtn.addEventListener(
    'click',
    ()=>{
        let value=
        parseInt(quantityInput.value);
        if(value>1){
            value--;
        }
        quantityInput.value=
        value;
    });
}

/*=====================PRODUCT IMAGE======================*/
const bigImage=
document.querySelector('.product-content-left-big-img img');
const thumbnails=
document.querySelectorAll('.product-content-left-small-img img');
const prevBtn=
document.querySelector('.prev');
const nextBtn=
document.querySelector('.next');

let currentIndex=0;

/* CLICK THUMBNAIL */
thumbnails.forEach(
(img,index)=>{
    img.addEventListener(
    'click',
    ()=>{
        currentIndex=index;
        updateImage();
    });
});

/* UPDATE */
function updateImage(){
    bigImage.src=
    thumbnails[currentIndex].src;
    thumbnails.forEach(
    item=>{
        item.classList.remove('active');
    });
    thumbnails[currentIndex]
    .classList.add('active');
}

/* NEXT */
nextBtn.addEventListener('click',
()=>{
    currentIndex++;
    if(
    currentIndex>= thumbnails.length
    ){
        currentIndex=0;
    }
    updateImage();
});

/* PREV */
prevBtn.addEventListener('click',
()=>{
    currentIndex--;
    if(
    currentIndex<0
    ){
        currentIndex=
        thumbnails.length-1;
    }
    updateImage();
});
updateImage();

/*=====================THUMB SCROLL======================*/
const thumbList=
document.querySelector('.product-content-left-small-img');
const thumbContainer=
document.querySelector('.thumb-container');
const upBtn=
document.querySelector('.thumb-arrow.up');
const downBtn=
document.querySelector('.thumb-arrow.down');
let scrollPosition=0;


const firstThumb=
document.querySelector('.product-content-left-small-img img');
const gap=12;
const scrollStep=
firstThumb.offsetHeight+gap;

/* DOWN */
downBtn.addEventListener(
'click',
()=>{
    const maxScroll=
    thumbList.scrollHeight-
    thumbContainer.clientHeight;
    scrollPosition+=scrollStep;

    if(
    scrollPosition>
    maxScroll
    ){

        scrollPosition=
        maxScroll;
    }

    thumbList.style.transform=
    `translateY(-${scrollPosition}px)`;

});

/* UP */
upBtn.addEventListener(
'click',
()=>{
    scrollPosition-=
    scrollStep;
    if(
    scrollPosition<0
    ){
        scrollPosition=0;
    }
    thumbList.style.transform=
    `translateY(-${scrollPosition}px)`;
});

/*=====================PRODUCT SIZE======================*/
const sizes=
document.querySelectorAll(
'.size span'
);

sizes.forEach(size=>{

    size.addEventListener(
    'click',
    ()=>{

        sizes.forEach(item=>{

            item.classList.remove(
            'active'
            );

        });

        size.classList.add(
        'active'
        );

    });

});