// var plus = document.querySelector('#plusicon');
// var minus = document.querySelector('#minusicon');
// var zoom = document.querySelector('.zoom');


// plus.addEventListener('click',()=>{
//     zoom.classList.remove('display')
//     minus.classList.remove('display')
//     plus.classList.add('display')
// })
// minus.addEventListener('click',()=>{
//     zoom.classList.add('display')
//     minus.classList.add('display')
//     plus.classList.remove('display')
// })


document.addEventListener('DOMContentLoaded', function() {
    document.body.addEventListener('click', function(event) {
        // 檢查點擊的是否是plus
        if (event.target.classList.contains('plus')) {
            let zoom = event.target.closest('.order').querySelector('.zoom');
            let minus = event.target.closest('.order').querySelector('.minus');
            let plus = event.target;

            zoom.classList.remove('display');
            minus.classList.remove('display');
            plus.classList.add('display');
        }

        // 檢查點擊的是否是minus
        if (event.target.classList.contains('minus')) {
            let zoom = event.target.closest('.order').querySelector('.zoom');
            let minus = event.target;
            let plus = event.target.closest('.order').querySelector('.plus');

            zoom.classList.add('display');
            minus.classList.add('display');
            plus.classList.remove('display');
        }
    });
});

