// addToCart.js
var totalCartAmount = parseFloat(localStorage.getItem('totalCartAmount')) || 0;

// 處理「加入購物車」按鈕點擊的函數
function addToCart() {
    // 獲取產品詳細資訊
    var productName = document.getElementById("pi_name").innerText;
    var productPrice = parseFloat(document.getElementById("pi_price").innerText);
    var productQuantity = parseInt(document.getElementById("numberDisplay").innerText);

    // 計算產品總價格
    var totalProductPrice = productPrice * productQuantity;

    // 更新
    totalCartAmount += totalProductPrice;

    // 將產品詳細資訊添加到 <p> 標籤中
    var cartInfoElement = document.getElementById("cartInfo");
    var existingCartInfo = cartInfoElement.innerHTML;
    var newCartInfo = productName + " - " + productPrice + " x " + productQuantity + " = " + totalProductPrice;
    cartInfoElement.innerHTML = existingCartInfo + "<br>" + newCartInfo;

    updateTotalCartAmountDisplay();

    alert("加入成功");

}

// 更新购物车总金额显示
function updateTotalCartAmountDisplay() {
    var totalCartAmountElement = document.getElementById("totalCartAmount");
    if (totalCartAmountElement) {
        totalCartAmountElement.innerHTML = "總金額： " + totalCartAmount;
    } else {
        console.error("Total cart amount element not found.");
    }
}

var slideIndex = 1;
showSlides(slideIndex);

function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  let i;
  let slides = document.getElementsByClassName("mySlides");
  let dots = document.getElementsByClassName("demo");

  if (n > slides.length) {slideIndex = 1}
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";
  dots[slideIndex-1].className += " active";

}