const heartIcon = document.querySelectorAll('.heart');

heartIcon.forEach(item => {
    item.addEventListener('click', function () {
        item.classList.toggle("liked");
    });
});

//側邊欄切換 
const cardCount = 6; // 每組顯示的卡片數量

function showCards(group) {
    const cards = document.querySelectorAll('.card');

    for (let i = 0; i < cards.length; i++) {
        if (i >= (group - 1) * cardCount && i < group * cardCount) {
            cards[i].style.display = 'block';
        } else {
            cards[i].style.display = 'none';
        }
    }
}

// 標題更換
const pageTitle = document.getElementById('pageTitle');
const category1 = document.getElementById('category1');
const category2 = document.getElementById('category2');
const category3 = document.getElementById('category3');
const category4 = document.getElementById('category4');

category1.addEventListener('click', function() {
    pageTitle.textContent = "手鍊";
});

category2.addEventListener('click', function() {
    pageTitle.textContent = "耳飾";
});

category3.addEventListener('click', function() {
    pageTitle.textContent = "項鍊";
});

category4.addEventListener('click', function() {
    pageTitle.textContent = "吊飾";
});











