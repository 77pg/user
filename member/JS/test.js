var forA = document.querySelector('#forA')
var forB = document.querySelector('#forB')
var forC = document.querySelector('#forC')
var forD = document.querySelector('#forD')
var maskA = document.querySelector('#showScreenA')
var maskB = document.querySelector('#showScreenB')
var maskC = document.querySelector('#showScreenC')
var maskD = document.querySelector('#showScreenD')

forA.addEventListener('click', function (event){
    maskA.classList.toggle('showScreen');
    maskA.addEventListener('click', function(event){
        maskA.classList.remove('showScreen')
    });
})

forB.addEventListener('click',function (event){
    maskB.classList.toggle('showScreen');
    maskB.addEventListener('click',function(event){
        maskB.classList.remove('showScreen')
    });
})

forC.addEventListener('click',function (event){
    maskC.classList.toggle('showScreen');
    maskC.addEventListener('click',function(event){
        maskC.classList.remove('showScreen')
    });
})

forD.addEventListener('click',function (event){
    maskD.classList.toggle('showScreen');
    maskD.addEventListener('click',function(event){
        maskD.classList.remove('showScreen')
    });
})