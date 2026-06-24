/* SCROLL */
window.addEventListener('scroll',()=>{
    if(window.scrollY > 50){
        header.classList.add('active');
    }
    else{
        header.classList.remove('active');
    }

});
/* HOVER */
header.addEventListener('mouseenter',()=>{
    header.classList.add('active');
});

header.addEventListener('mouseleave',()=>{
    if(window.scrollY <= 50){

        header.classList.remove('active');
    }
});
