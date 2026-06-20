document.addEventListener(
'DOMContentLoaded',
()=>{

    const categoryItems=
    document.querySelectorAll('.cartegory-left-li'
    );

    categoryItems.forEach(item=>{

        const title=
        item.querySelector('.cartegory-title'
        );

        if(title){
            title.addEventListener('click',
            (e)=>{

                e.preventDefault();
                item.classList.toggle('active'
                );
            });
        }
    });
});