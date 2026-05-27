const cartSidebar=document.querySelector('.cart-sidebar');
const cartOverlay=document.querySelector('.cart-overlay');
const closeCart=document.querySelector('.close-cart');
/*=====================OPEN======================*/

/*
tạm dùng test sau sẽ đổi thành icon header
*/

document.addEventListener(
'keydown',
(e)=>{
    if(e.key==="c"){
        cartSidebar.classList.add('active');
        cartOverlay.classList.add('active');
    }
});

/*=====================CLOSE=====================*/

function closeSidebar(){
    cartSidebar.classList.remove('active');
    cartOverlay.classList.remove('active');
}
closeCart.addEventListener('click',closeSidebar);

cartOverlay.addEventListener('click',closeSidebar);

/*=====================QUANTITY======================*/
const quantityBoxes=document.querySelectorAll('.cart-item-quantity');
quantityBoxes.forEach(
(box)=>{
    const minus=
    box.querySelector('button:first-child');
    const plus=
    box.querySelector('button:last-child');

    const input=
    box.querySelector('input');

    plus.addEventListener('click',
    ()=>{   
        let value=parseInt(input.value);
        value++;
        input.value=value;
    updateCartCount();
    updateSidebarTotal();
});

    minus.addEventListener('click',
    ()=>{
        let value=parseInt(input.value);
        if(value>1)
        {
        value--;
        }
    
        input.value=value;
    updateCartCount();
    updateSidebarTotal();
    });
});

/*=====================DELETE PRODUCT======================*/
const deleteButtons=document.querySelectorAll('.cart-delete');

deleteButtons.forEach((button)=>{
    button.addEventListener('click',
    ()=>{
        button
        .closest('.cart-item')
        .remove();
        updateCartCount();
        updateSidebarTotal();
    });
});

/*=====================CART COUNT======================*/
function updateCartCount(){
    const quantityInputs=
    document.querySelectorAll('.cart-item-quantity input');
    const cartCount=
    document.querySelector('.cart-count');

    let total=0;

    quantityInputs.forEach(
    input=>{
        total+=parseInt(input.value);
    });
    cartCount.innerText=total;

}

/*=====================UPDATE TOTAL PRICE======================*/
function updateSidebarTotal(){
    const cartItems=
    document.querySelectorAll('.cart-item');
    const totalPrice=
    document.querySelector('.cart-total-price');

    let total=0;

    cartItems.forEach(
    (item)=>{
        const quantity=parseInt(
        item.querySelector('.cart-item-quantity input'
        ).value
        );

        /* GIÁ GỐC */
        const price=parseInt(
        item.querySelector('.cart-item-price'
        ).dataset.price
        );

        /* THÀNH TIỀN */
        const itemTotal=quantity*price;

        item.querySelector('.cart-item-price'
        ).innerText=
        formatPrice(itemTotal)+'đ';

        total+=itemTotal;
    });

    totalPrice.innerText=
    formatPrice(total)+'đ';
}
function formatPrice(number){
    return number.toLocaleString('vi-VN');
}

/* chạy lần đầu */

updateCartCount();
updateSidebarTotal();