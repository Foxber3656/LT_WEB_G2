/*=====================DELIVERY COST======================*/
const deliveryOptions =
document.querySelectorAll('input[name="delivery"]');
const shippingElement =
document.querySelector('.shipping-price');
const totalElement =
document.querySelector('.total');
const untotalElement =
document.querySelector('.untotal');

/*=====================UPDATE TOTAL======================*/
function updateDelivery(){
    const untotal = 1480000;
    let shipping = 0;


    if(deliveryOptions[1].checked){
        shipping = 15000;
    }
    shippingElement.innerText =
    formatPrice(shipping) + 'đ';

    totalElement.innerText =
    formatPrice(untotal + shipping) + 'đ';

}


/*=====================FORMAT PRICE======================*/
function formatPrice(number){
    return number
    .toLocaleString('vi-VN');
}

/*=====================EVENT======================*/
deliveryOptions.forEach(
(option)=>{

    option.addEventListener('change',
    updateDelivery
    );
});

/*=====================CHECKOUT BUTTON======================*/
const deliveryBtn =document.querySelector('.delivery-btn');
if(deliveryBtn){
    deliveryBtn.addEventListener(
    'click',
    ()=>{
        window.location.href =
        'payment.html';
    });
}

updateDelivery();