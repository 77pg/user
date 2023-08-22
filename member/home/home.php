<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>首頁</title>
    <link rel="stylesheet" href="../CSS/reset.css">
    <link rel="stylesheet" href="../CSS/navbar.css">
    <link rel="stylesheet" href="../CSS/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
</head>

<body>
    <div class="header">
        <div class="menu">
            <div class="menu-left">
                <div class="dropdown ">
                    <button class="dropbtn-0">
                        <img src="../icon_public/menu.png" alt="選單">
                    </button>
                    <div class="dropdown-content-0">
                        <?php
                        // 檢查 Session 中的登入狀態
                        if (isset($_COOKIE['token'])) {
                            // 使用者已登入，顯示登出選項
                            echo '<li style="color:black;font-size: 12px;">---登入成功---</li>';
                            echo '<li><a href="../Member area/update.php">我的帳戶</a></li>';
                            echo '<li><a href="../Member area/password.php">變更密碼</a></li>';
                            echo '<li><a href="../Member area/search.php">訂單查詢</a></li>';
                            echo '<li><a href="../Member area/collect.php">收藏清單</a></li>';
                            echo '<li><a href="../login/logout.php">登出</a></li>';
                            // 這裡可以根據需要顯示其他登入後的選項，例如"我的帳戶"、"訂單查詢"等
                        } else {
                            // 使用者未登入，顯示會員登入和註冊新會員選項
                            echo '<li><a href="../login/login.php">會員登入</a></li>';
                            echo '<li><a href="../login/register.html">註冊新會員</a></li>';
                        }
                        ?>

                        <hr>

                        <li><a href="../Product outside/product.php">全部商品</a></li>
                        <li><a href="../Product outside/product.php">手鍊</a></li>
                        <li><a href="../Product outside/product.php">耳飾</a></li>
                        <li><a href="../Product outside/product.php">項鍊</a></li>
                        <li><a href="../Product outside/product.php">吊飾</a></li>
                    </div>
                </div>
            </div>

            <a href="home.php">
                <h1>愛不飾手</h1>
            </a>

            <div class="menu-right">
                <div class="dropdown ">
                    <button class="dropbtn-1">
                        <img src="../icon_public/search.png" alt="搜尋">
                    </button>
                    <div class="dropdown-content">
                        <li><a href="../Product outside/product.php">全部商品</a></li>
                        <li><a href="../Product outside/product.php">手鍊</a></li>
                        <li><a href="../Product outside/product.php">耳飾</a></li>
                        <li><a href="../Product outside/product.php">項鍊</a></li>
                        <li><a href="../Product outside/product.php">吊飾</a></li>
                    </div>
                </div>
                <div class="dropdown">
                    <button class="dropbtn-2">
                        <img src="../icon_public/shopping-cart.png" alt="購物車">
                    </button>
                    <div class="dropdown-content">
                        <?php
                        // 檢查 Session 中的登入狀態
                        if (isset($_COOKIE['token'])) {

                            echo '<li>您的商品:</li>';
                            echo '<li><a href="../Product outside/product.php">立即選購</a></li>';
                        } else {
                            echo '<li>購物車目前是空的!</li>';
                            echo '<li><a href="../login/login.php">立即登入</a></li>';
                        }
                        ?>
                    </div>
                </div>
                <!-- 在 Navbar 中的登入選項 -->
                <div class="dropdown">
                    <button class="dropbtn-3">
                        <img src="../icon_public/user.png" alt="搜尋">
                    </button>
                    <div class="dropdown-content" id="user-dropdown">
                        <?php
                        // 檢查 Session 中的登入狀態
                        if (isset($_COOKIE['token'])) {
                            // 使用者已登入，顯示登出選項
                            echo '<li style="color:black;font-size: 12px;">---登入成功---</li>';
                            echo '<li><a href="../Member area/update.php">我的帳戶</a></li>';
                            echo '<li><a href="../Member area/password.php">變更密碼</a></li>';
                            echo '<li><a href="../Member area/search.php">訂單查詢</a></li>';
                            echo '<li><a href="../Member area/collect.php">收藏清單</a></li>';
                            echo '<li><a href="../login/logout.php">登出</a></li>';
                            // 這裡可以根據需要顯示其他登入後的選項，例如"我的帳戶"、"訂單查詢"等
                        } else {
                            // 使用者未登入，顯示會員登入和註冊新會員選項
                            echo '<li><a href="../login/login.php">會員登入</a></li>';
                            echo '<li><a href="../login/register.html">註冊新會員</a></li>';
                        }
                        ?>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <hr>
    <div class="navbar">
        <a href="../Product outside/product.php">全部商品</a>
        <a href="../Product outside/product.php">手鍊</a>
        <a href="../Product outside/product.php">耳飾</a>
        <a href="../Product outside/product.php">項鍊</a>
        <a href="../Product outside/product.php">吊飾</a>
    </div>


    <div class="slideshow-container">
        <!-- Full-width images with number and caption text -->
        <div class="mySlides fade">
            <img src="./img/001.jpg" style="width:100%">
        </div>

        <div class="mySlides fade">
            <img src="./img/002.jpg" style="width:100%">
        </div>

        <div class="mySlides fade">
            <img src="./img/003.jpg" style="width:100%">
        </div>

        <!-- Next and previous buttons -->
        <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
        <a class="next" onclick="plusSlides(1)">&#10095;</a>

    </div>

    <!-- The dots/circles -->
    <div style="text-align:center">
        <span class="dot" onclick="currentSlide(1)"></span>
        <span class="dot" onclick="currentSlide(2)"></span>
        <span class="dot" onclick="currentSlide(3)"></span>
    </div>

    <!-- 敘述輪播 -->
    <div class="itemoutsid">
        <div class="item">
            <div class="itemimgoutsid">
                <div class="itemimg">
                    <img src="./img/001.jpg">
                    <img src="./img/002.jpg">
                    <img src="./img/003.jpg">
                </div>
            </div>
            <div class="itemcontent">
                <div class="myitem">
                    <img src="../icon_public/left-arrow.png">
                    <div class="itemtxt">
                        <h2>手鍊</h2>
                        <p>完美的細節，為你增添風采！讓我們的飾品成為你造型的亮點。</p>
                        <button class="more"><a href="../Product outside/product.php">進入商品</a></button>
                    </div>
                    <div class="itemtxt" style="display: none;">
                        <h2>項鍊</h2>
                        <p>無論是宴會、婚禮或日常穿搭，這款飾品將為你的風格注入奢華和閃耀。與我們的飾品一同追逐時尚，綻放你獨特的魅力！</p>
                        <button class="more"><a href="../Product outside/product.php">進入商品</a></button>
                    </div>
                    <div class="itemtxt" style="display: none;">
                        <h2>戒指</h2>
                        <p>完美的細節，為你增添風采！這款飾品將為你的風格注入奢華和閃耀。</p>
                        <button class="more"><a href="../Product outside/product.php">進入商品</a></button>
                    </div>
                    <img src="../icon_public/right-arrow (1).png">
                </div>
            </div>
        </div>
    </div>


    <!-- 圓球區 -->
    <div class="content">
        <div class="ball-1">
            <div class="ball-left">
                <p>一圈璀璨，繞在你的腕間。我們的手環不僅是一個時尚配飾，更是你個性的展示。每一個手環都蘊含著細膩的工藝和無限的創意，為你的手腕增添一絲光彩。無論是與其他飾品堆疊，或獨立佩戴，這款手環將為你的造型帶來獨特的風格。
                </p>
            </div>
            <div class="photo-1">
                <img src="./img/001.jpg">
            </div>
        </div>
        <div class="ball-2">
            <div class="photo-2">
                <img src="./img/004.jpg" class="p-1">
            </div>
            <div class="text-2">
                <p>一圈璀璨，繞在你的腕間。我們的手環不僅是一個時尚配飾，更是你個性的展示。每一個手環都蘊含著細膩的工藝和無限的創意，為你的手腕增添一絲光彩。無論是與其他飾品堆疊，或獨立佩戴，這款手環將為你的造型帶來獨特的風格。
                </p>
            </div>
        </div>
    </div>


    <!-- 方形輪播圖 -->
    <div class="wrapper">
        <div class="linear-gradient"></div>
        <ul class="carousel">
            <li class="card">
                <div class="img"><img src="../home/img/004.jpg" alt="img" draggable="false"></div>
            </li>
            <li class="card">
                <div class="img"><img src="../home/img/005.jpg" alt="img" draggable="false"></div>
            </li>
            <li class="card">
                <div class="img"><img src="../home/img/004.jpg" alt="img" draggable="false"></div>
            </li>
            <li class="card">
                <div class="img"><img src="../home/img/005.jpg" alt="img" draggable="false"></div>
            </li>
            <li class="card">
                <div class="img"><img src="../home/img/004.jpg" alt="img" draggable="false"></div>
            </li>
            <li class="card">
                <div class="img"><img src="../home/img/005.jpg" alt="img" draggable="false"></div>
            </li>
            <li class="card">
                <div class="img"><img src="../home/img/004.jpg" alt="img" draggable="false"></div>
            </li>
            <li class="card">
                <div class="img"><img src="../home/img/005.jpg" alt="img" draggable="false"></div>
            </li>
            <i id="right" class="fa-solid fa-angle-right"></i>
        </ul>
    </div>

    <div class="test">
        <div class="testbox">
            <img src="../home/img/test.png" alt="">
            <div class="testcover">
                <div class="testtxt">
                    <p>送禮還是自用?</p>
                    <p>選擇困難嗎?</p>
                    <br>
                    <p>選擇展現你的個性。</p>
                    <p>參與心理測驗，探索你的飾品風格。</p>
                </div>
                <a href="../test/test.php"><div class="testlink">點我測驗</div></a>
            </div>
        </div>
    </div>

    <!-- 聯絡預定 -->
    <!-- <div class="container">
        <img src="../home/img/003.jpg" alt="Avatar" class="image">
        <div class="overlay">
            <div class="containertext">
                <div class="text">
                    <p>尋找獨一無二的禮物嗎？我們提供手作商品訂製服務，讓您的禮物更加特別！</p>
                    <p>每一件商品都是由熟練的手工藝師用心製作，融入您的獨特要求和意念。無論是婚禮紀念品、生日驚喜，或是表達您對摯愛的愛意，我們講以精湛的手藝打造屬於您的獨一無二的珍品。</p>
                    <p>訂製手作商品，讓溫暖的情感化為永恆的回憶。</p>
                </div>
                <div class="connectbuttom">
                    <a href="">聯絡預定</a>
                </div>
            </div>
        </div>
    </div> -->

    <div class="fix">
        <!-- Messenger Chat Plugin Code -->
        <img src="../icon_public/chat.png">
        <a href="#top"><img src="../icon_public/top.png"></a>
    </div>


    <div class="bottom">
        <div class="footer">
            <div class="footertxt">
                <p>版權聲明/使用條款/聯繫信息/首頁</p>
                <p>xxx12345@gmail.com</p>
            </div>
            <div class="media">
                <div class="icon">
                    <img src="../icon_public/facebook.png">
                    <a>facebook</a>
                </div>
                <div class="icon">
                    <img src="../icon_public/twitter.png">
                    <a>twitter</a>
                </div>
                <div class="icon">
                    <img src="../icon_public/instagram.png">
                    <a>instagram</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Messenger 洽談外掛程式 Code -->
    <div id="fb-root"></div>

    <!-- Your 洽談外掛程式 code -->
    <div id="fb-customer-chat" class="fb-customerchat">
    </div>
</body>

</html>

<script src="../JS/home.js" defer></script>
<script>
    var chatbox = document.getElementById('fb-customer-chat');
    chatbox.setAttribute("page_id", "109853725531001");
    chatbox.setAttribute("attribution", "biz_inbox");
</script>

<!-- Your SDK code -->
<script>
    window.fbAsyncInit = function () {
        FB.init({
            xfbml: true,
            version: 'v17.0'
        });
    };

    (function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = 'https://connect.facebook.net/zh_TW/sdk/xfbml.customerchat.js';
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>