const passwordInput =
document.querySelector(
'input[type="password"]'
);

passwordInput.addEventListener(
'focus',
()=>{
    passwordInput.style.borderColor =
    '#BF8A49';
});