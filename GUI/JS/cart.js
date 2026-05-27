const quantityBoxes=
document.querySelectorAll('.cart-item-quantity');
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
        input.value=parseInt(
        input.value
        )+1;

        updateCart();
    });

    minus.addEventListener(
    'click',
    ()=>{
        let value=parseInt(
        input.value
        );
        if(value>1){
            input.value=value-1;

            updateCart();
        }
    });
});

/*=====================DELETE PRODUCT======================*/
const deleteButtons=
document.querySelectorAll('.cart-item-delete'
);

deleteButtons.forEach(
(button)=>{
    button.addEventListener('click',
    ()=>{
        button
        .closest('.cart-item'
        )
        .remove();

        updateCart();
    });
});

/*=====================UPDATE CART======================*/
function updateCart(){
    const cartItems=
    document.querySelectorAll('.cart-item');

    let totalQuantity=0;
    let totalPrice=0;

    cartItems.forEach(
    (item)=>{
        const quantity=parseInt(
        item.querySelector('.cart-item-quantity input').value
        );

        const priceText=
        item.querySelector('.cart-item-price p'
        ).innerText;

        const price=parseInt(
        priceText
        .replace(/\D/g,'')
        );

        const itemTotal=quantity*price;

        item.querySelector('.cart-item-total p'
        ).innerText=
        formatPrice(
        itemTotal
        )+'đ';

        totalQuantity+=quantity;

        totalPrice+=itemTotal;
    });


    /*=====================SUMMARY======================*/
    const totalProduct=
    document.querySelector('.cart-summary-item span');

    totalProduct.innerText=totalQuantity;

    const summaryItems=
    document.querySelectorAll('.cart-summary-item span');

    summaryItems[1].innerText=formatPrice(
    totalPrice
    )+'đ';

    summaryItems[3].innerText=formatPrice(
    totalPrice-100000
    )+'đ';

    /*=====================TITLE======================*/
    const titleCount=
    document.querySelector('.cart-title span');

    titleCount.innerText=totalQuantity+' Sản phẩm';
}

/*====================FORMAT PRICE======================*/
function formatPrice(number){
    return number.toLocaleString(
    'vi-VN'
    );

}

updateCart();