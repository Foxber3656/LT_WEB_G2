const paymentMethods =
document.querySelectorAll('input[name="payment"]');
const paymentItems =
document.querySelectorAll('.payment-method-item');
const codInfo =
document.querySelector('.payment-cod');
const bankInfo =
document.querySelector('.payment-bank');

/*=====================PAYMENT CHANGE======================*/
paymentMethods.forEach(
(method,index)=>{
    method.addEventListener('change',
    ()=>{
        /* RESET */
        paymentItems.forEach(
        item=>{
            item.classList.remove('active');
        });

        codInfo.classList.remove('active');
        bankInfo.classList.remove('active');

        /* ACTIVE CARD */
        paymentItems[index].classList.add('active');

        /* SHOW INFO */
        if(method.value==="cod")
        {
            codInfo.classList.add('active');
        }
        else
        {
            bankInfo.classList.add('active');
        }
    });
});

paymentItems[0].classList.add('active');