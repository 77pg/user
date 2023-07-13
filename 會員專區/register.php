<?php session_start(); ?>
<?php
// 在registercheck.php設time()+120，兩分鐘後會登出
// if (isset($_SESSION['uid'])) {
//     header('location:'.$_SESSION['welcome']);
// }
if (isset($_COOKIE['token'])) {
    header('location:' . $_COOKIE['welcome']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>首頁</title>
    <link rel="stylesheet" href="./CSS/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link rel="stylesheet" href="./CSS/register.css">
    <script src="script.js" defer></script>
    <script src="./JS/register.js"></script>

</head>
<body>
    <!-- http://localhost/register.php -->
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
                    <button onclick="location.href='register.php'">立即登入</button>
                </div>
            </div>
            <div class="dropdown">
                <span><img src="./img/user.png" alt="使用者"></span>

                <div class="dropdown-content">
                    <button onclick="location.href='register.php'">會員登入</button>
                    <button onclick="location.href='register.php'">註冊會員</button>
                </div>
            </div>
            <!-- <ul>
                <li><a href="https://google.com"><img src="./img/search.png" alt="搜尋種類"></a></li>
                <li><img src="./img/shopping-cart.png" alt="購物車"></li>
                <li><a href="./register.html"><img src="./img/user.png" alt="使用者"></a></li>
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
                <li class="breadcrumb-item" style="font-weight: bold;">註冊會員</li>
                <!-- <li class="breadcrumb-item"><a href="./register.html">會員登入</a></li> -->

            </ul>
        </div>
        <div class="register-parent">
            <div class="register">
                <h1>註冊會員</h1>
                <!--  action=".php" -->
                <form method="post">
                    <div>
                        <label for="uid" class="register-label">帳號(3-10字)</label>
                        <span id="userinfo"></span>                        
                        <input id="uid" name="uid" type="text" class="register-input" placeholder="請輸入帳號" required="required" pattern="\w{3,10}"><br><br>
                        
                        <label for="pwd" class="register-label">密碼(8字)</label><br>
                        <input id="pwd" name="pwd" type="password" class="register-input" placeholder="請輸入密碼(至少輸入一個英文及一個數字)" required="required" 
                        pattern="^(?=.*[a-zA-Z])(?=.*[0-9]).{8,}$"oninput="setCustomValidity('');"><br><br>
                        
                        <label for="checkpwd" class="register-label">確認密碼</label><br>
                        <input id="checkpwd" name="checkpwd" type="password" class="register-input" placeholder="請再輸入一次密碼" required="required"
                        pattern="[a-zA-Z0-9]{8,}" oninput="setCustomValidity('');"
                        onchange="if(document.getElementById('pwd').value != document.getElementById('checkpwd').value){
                        setCustomValidity('密碼不吻合');}"><br><br>
                        
                        <label for="phone" class="register-label">手機號碼</label><br>
                        <input id="phone" name="phone" type="text" class="register-input" placeholder="請輸入手機號碼，ex:0911222333" required="required" 
                        maxlength="10" pattern="09\d{2}\d{6}" ><br><br>
                        
                        <label for="email" class="register-label">電子信箱</label><br>
                        <input id="email" name="email" type="email" class="register-input" placeholder="請輸入電子信箱" required="required" 
                        pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$"/><br><br>
                        
                        <input type="radio" name="sex" id="male" value="male" checked>
                        <label for="male">男</label>
                        <input type="radio" name="sex" id="female" value="female">
                        <label for="female">女</label><br><br>
                        
                        <button id="register-button">加入會員</button><br><br>
                    </div>

                    <div class="under-register">

                        <span>已有會員帳號?</span>
                        <span class="u1">
                            <a href="./login.php">返回登入頁面</a>
                        </span>
                    </div>
                </form>
            </div>

        </div>

</body>

</html>