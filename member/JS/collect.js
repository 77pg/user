const heartIcon = document.querySelectorAll('.heart');

heartIcon.forEach(item =>{
    item.addEventListener('click', function()
    {
        item.classList.toggle("liked");
    });
});
