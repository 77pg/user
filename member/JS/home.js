const wrapper = document.querySelector(".wrapper");
const carousel = document.querySelector(".carousel");
const firstCardWidth = carousel.querySelector(".card").offsetWidth;
const arrowBtns = document.querySelectorAll(".wrapper i");
const carouselChildrens = [...carousel.children];

let isDragging = false, isAutoPlay = true, startX, startScrollLeft, timeoutId;

// Get the number of cards that can fit in the carousel at once
let cardPerView = Math.round(carousel.offsetWidth / firstCardWidth);

// Insert copies of the last few cards to beginning of carousel for infinite scrolling
carouselChildrens.slice(-cardPerView).reverse().forEach(card => {
    carousel.insertAdjacentHTML("afterbegin", card.outerHTML);
});

// Insert copies of the first few cards to end of carousel for infinite scrolling
carouselChildrens.slice(0, cardPerView).forEach(card => {
    carousel.insertAdjacentHTML("beforeend", card.outerHTML);
});

// Scroll the carousel at appropriate postition to hide first few duplicate cards on Firefox
carousel.classList.add("no-transition");
carousel.scrollLeft = carousel.offsetWidth;
carousel.classList.remove("no-transition");

// Add event listeners for the arrow buttons to scroll the carousel left and right
arrowBtns.forEach(btn => {
    btn.addEventListener("click", () => {
        carousel.scrollLeft += btn.id == "left" ? -firstCardWidth : firstCardWidth;
    });
});

const dragStart = (e) => {
    isDragging = true;
    carousel.classList.add("dragging");
    // Records the initial cursor and scroll position of the carousel
    startX = e.pageX;
    startScrollLeft = carousel.scrollLeft;
}

const dragging = (e) => {
    if (!isDragging) return; // if isDragging is false return from here
    // Updates the scroll position of the carousel based on the cursor movement
    carousel.scrollLeft = startScrollLeft - (e.pageX - startX);
}

const dragStop = () => {
    isDragging = false;
    carousel.classList.remove("dragging");
}

const infiniteScroll = () => {
    // If the carousel is at the beginning, scroll to the end
    if (carousel.scrollLeft === 0) {
        carousel.classList.add("no-transition");
        carousel.scrollLeft = carousel.scrollWidth - (2 * carousel.offsetWidth);
        carousel.classList.remove("no-transition");
    }
    // If the carousel is at the end, scroll to the beginning
    else if (Math.ceil(carousel.scrollLeft) === carousel.scrollWidth - carousel.offsetWidth) {
        carousel.classList.add("no-transition");
        carousel.scrollLeft = carousel.offsetWidth;
        carousel.classList.remove("no-transition");
    }

    // Clear existing timeout & start autoplay if mouse is not hovering over carousel
    clearTimeout(timeoutId);
    if (!wrapper.matches(":hover")) autoPlay();
}

const autoPlay = () => {
    if (window.innerWidth < 800 || !isAutoPlay) return; // Return if window is smaller than 800 or isAutoPlay is false
    // Autoplay the carousel after every 2500 ms
    timeoutId = setTimeout(() => carousel.scrollLeft += firstCardWidth, 2500);
}
autoPlay();


carousel.addEventListener("mousedown", dragStart);
carousel.addEventListener("mousemove", dragging);
document.addEventListener("mouseup", dragStop);
carousel.addEventListener("scroll", infiniteScroll);
wrapper.addEventListener("mouseenter", () => clearTimeout(timeoutId));
wrapper.addEventListener("mouseleave", autoPlay);

// 輪播圖
let slideIndex = 1;
showSlides(slideIndex);

// Next/previous controls
function plusSlides(n) {
    showSlides(slideIndex += n);
}

// Thumbnail image controls
function currentSlide(n) {
    showSlides(slideIndex = n);
}

function showSlides(n) {
    let i;
    let slides = document.getElementsByClassName("mySlides");
    let dots = document.getElementsByClassName("dot");
    if (n > slides.length) { slideIndex = 1 }
    if (n < 1) { slideIndex = slides.length }
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }
    slides[slideIndex - 1].style.display = "block";
    dots[slideIndex - 1].className += " active";
}

// 敘述輪播
    // Function to show the next itemtxt and corresponding image
    function showNextItem() {
        var currentItems = document.querySelectorAll('.itemtxt');
        var currentItem = document.querySelector('.itemtxt:not([style*="display: none"])');
        var currentIndex = Array.from(currentItems).indexOf(currentItem);
        var itemImgs = document.querySelectorAll('.itemimg img');

        if (currentIndex !== -1) {
            currentItem.style.display = 'none';
            itemImgs[currentIndex].style.display = 'none';

            var nextIndex = (currentIndex + 1) % currentItems.length;
            currentItems[nextIndex].style.display = 'block';
            itemImgs[nextIndex].style.display = 'block';
        }
    }

    // Function to show the previous itemtxt and corresponding image
    function showPreviousItem() {
        var currentItems = document.querySelectorAll('.itemtxt');
        var currentItem = document.querySelector('.itemtxt:not([style*="display: none"])');
        var currentIndex = Array.from(currentItems).indexOf(currentItem);
        var itemImgs = document.querySelectorAll('.itemimg img');

        if (currentIndex !== -1) {
            currentItem.style.display = 'none';
            itemImgs[currentIndex].style.display = 'none';

            var previousIndex = (currentIndex - 1 + currentItems.length) % currentItems.length;
            currentItems[previousIndex].style.display = 'block';
            itemImgs[previousIndex].style.display = 'block';
        }
    }

    // Hide all itemtxt and itemimg images except the first one initially
    var itemTexts = document.querySelectorAll('.itemtxt');
    var itemImages = document.querySelectorAll('.itemimg img');
    for (var i = 1; i < itemTexts.length; i++) {
        itemTexts[i].style.display = 'none';
        itemImages[i].style.display = 'none';
    }

    // Add event listeners to the right and left arrows
    var rightArrow = document.querySelector('.itemcontent .myitem > img[src="../icon_public/right-arrow (1).png"]');
    var leftArrow = document.querySelector('.itemcontent .myitem > img[src="../icon_public/left-arrow.png"]');

    rightArrow.addEventListener('click', showNextItem);
    leftArrow.addEventListener('click', showPreviousItem);






