<?php session_start();?>
<?php
// 在logincheck.php設time()+120，兩分鐘後會登出
// if (isset($_SESSION['uid'])) {
//     header('location:'.$_SESSION['welcome']);
// }
if (isset($_COOKIE['token'])) {
    header('location:'.$_COOKIE['welcome']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>首頁</title>
    <link rel="stylesheet" href="./home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link rel="stylesheet" href="./login.css">
    <link rel="stylesheet" href="./welcome.css">
    <script src="script.js" defer></script>

</head>

<body>
    <!-- http://localhost/login.php -->
    <div class="header">
        <div class="menu">
            <h1>低調廚房</h1>
            <div class="dropdown">
                <span><img src="./img/search.png" alt="搜尋種類"></span>
                <div class="dropdown-content">
                <li><a href="">全部商品</a></li>
                    <li><a href="">手鍊</a></li>
                    <li><a href="">耳飾</a></li>
                    <li><a href="">項鍊</a></li>
                    <li><a href="">吊飾</a></li>
                </div>
            </div>
            <div class="dropdown">
                <span><img src="./img/shopping-cart.png" alt="購物車"></span>
                <div class="dropdown-content">
                    <p>目前的購物車是空的!</p>
                    <button onclick="location.href='login.php'">立即登入</button>
                </div>
            </div>
            <div class="dropdown">
                <span><img src="./img/user.png" alt="使用者"></span>
                
                <div class="dropdown-content">
                <button onclick="location.href='login.php'">會員登入</button>
                    <button onclick="location.href='register.php'">註冊新會員</button>
                </div>
            </div>
            <!-- <ul>
                <li><a href="https://google.com"><img src="./img/search.png" alt="搜尋種類"></a></li>
                <li><img src="./img/shopping-cart.png" alt="購物車"></li>
                <li><a href="./login.html"><img src="./img/user.png" alt="使用者"></a></li>
            </ul> -->
        </div>
        <div class="navbar">
            <a href="">全部商品</a>
            <a href="">手鍊</a>
            <a href="">耳飾</a>
            <a href="">項鍊</a>
            <a href="">吊飾</a>
        </div>
        <div>            
            <ul class="breadcrumb p-3">
                <li class="breadcrumb-item"><a href="./home.html"><img src="./img/home.png" class="icon"></a></li>
                <li class="breadcrumb-item" style="font-weight: bold;">會員登入</li>
                <!-- <li class="breadcrumb-item"><a href="./login.html">會員登入</a></li> -->
        
            </ul>
        </div>
        <div class="login">
            <h1>會員登入</h1>
            <form method="post" action="logincheck.php">
                <div>
                    <label for="uid" class="login-label">帳號</label><br>
                    <input id="uid" name="uid" type="text" class="login-input" placeholder="請輸入帳號" required="required"><br><br>
                    <label for="pwd" class="login-label">密碼</label><br>
                    <input id="pwd" name="pwd" type="password" class="login-input" placeholder="請輸入密碼" required="required"><br><br>
                    <!-- <input type="submit" id="login-button">會員登入</input><br><br> -->
                    <button id="login-button">會員登入</button><br><br>
    
                    <input type="checkbox" name="keeplog" id="keeplog">
                    <label for="keeplog" style="font-size: 20px;">保持登入</label>
                </div>
                <div class="under-login">
                    <div class="u1">
                        <img src="./img/unlock.png" class="icon">
                        忘記密碼
                    </div>
                    <div class="u2">
                        <img src="./img/add-user.png" class="icon">
                        註冊會員
                    </div>
                    
                </div>
            </form>
        </div>
        
    </div>

</body>

</html>