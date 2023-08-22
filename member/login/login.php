<?php session_start(); ?>
<?php
// 用cookie紀錄使用者登入
if (isset($_COOKIE['token'])) {
    header('location:' . $_COOKIE['welcome']);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>會員登入</title>
    <link rel="stylesheet" href="/member/CSS/navbar.css">
    <link rel="stylesheet" href="/member/CSS/reset.css">
    <link rel="stylesheet" href="/member/CSS/login.css">
    <script src="script.js" defer></script>

</head>

<body>
    <!-- http://localhost/login.php -->
    <div class="header">
        <div class="menu">
            <div class="menu-left">
                <div class="dropdown ">
                    <button class="dropbtn-0">
                        <img src="/member/icon_public/menu.png" alt="選單">
                    </button>
                    <div class="dropdown-content-0">
                        <li><a href="login.php">會員登入</a></li>
                        <li><a href="register.html">註冊新會員</a></li>
                        <li><a href="">購物車</a></li>
                        <li><a href="/member/Product outside/product.php">全部商品</a></li>
                        <li><a href="/member/Product outside/product.php">手鍊</a></li>
                        <li><a href="/member/Product outside/product.php">耳飾</a></li>
                        <li><a href="/member/Product outside/product.php">項鍊</a></li>
                        <li><a href="/member/Product outside/product.php">吊飾</a></li>
                    </div>
                </div>
            </div>

            <a href="/member/home/home.php">
                <h1>愛不飾手</h1>
            </a>

            <div class="menu-right">
                <div class="dropdown ">
                    <button class="dropbtn-1">
                        <img src="/member/icon_public/search.png" alt="搜尋">
                    </button>
                    <div class="dropdown-content">
                        <li><a href="/member/Product outside/product.php">全部商品</a></li>
                        <li><a href="/member/Product outside/product.php">手鍊</a></li>
                        <li><a href="/member/Product outside/product.php">耳飾</a></li>
                        <li><a href="/member/Product outside/product.php">項鍊</a></li>
                        <li><a href="/member/Product outside/product.php">吊飾</a></li>
                    </div>
                </div>
                <div class="dropdown">
                    <button class="dropbtn-2">
                        <img src="/member/icon_public/shopping-cart.png" alt="購物車">
                    </button>
                    <div class="dropdown-content">
                    <?php
                        // 檢查 Session 中的登入狀態
                        if (isset($_COOKIE['token'])) {
                            
                            echo '<li>您的商品:</li>';
                            echo '<li><a href="/member/Product outside/product.php">立即選購</a></li>';
                        } else {
                            echo '<li>購物車目前是空的!</li>';
                            echo '<li><a href="login.php">立即登入</a></li>';
                        }
                    ?>
                    </div>
                </div>
                <!-- 在 Navbar 中的登入選項 -->
                <div class="dropdown">
                    <button class="dropbtn-3">
                        <img src="/member/icon_public/user.png" alt="搜尋">
                    </button>
                    <div class="dropdown-content" id="user-dropdown">
                        <?php
                        // 檢查 Session 中的登入狀態
                        if (isset($_COOKIE['token'])) {
                            // 使用者已登入，顯示登出選項
                            echo '<li style="color:black;font-size: 12px;">---登入成功---</li>';
                            echo '<li><a href="../logout.php">登出</a></li>';
                            // 這裡可以根據需要顯示其他登入後的選項，例如"我的帳戶"、"訂單查詢"等
                        } else {
                            // 使用者未登入，顯示會員登入和註冊新會員選項
                            echo '<li><a href="login.php">會員登入</a></li>';
                            echo '<li><a href="register.html">註冊新會員</a></li>';
                        }
                        ?>
                        
                    </div>
                </div>

            </div>
        </div>
    </div>

    <hr>
    <div class="navbar">
        <a href="/member/Product outside/product.php">全部商品</a>
        <a href="/member/Product outside/product.php">手鍊</a>
        <a href="/member/Product outside/product.php">耳飾</a>
        <a href="/member/Product outside/product.php">項鍊</a>
        <a href="/member/Product outside/product.php">吊飾</a>
    </div>

<div>
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="/member/home/home.php"><img src="/member/icon_public/home.png"></a></li>
        <li class="breadcrumb-item">/會員登入</li>
    </ul>
</div>

<div class="login-parent">
    <h1>會員登入</h1>
    <div class="login">
        <form method="post" action="logincheck.php">
            <div>
                <label for="uid" class="login-label">帳號</label><br>
                <input id="uid" name="uid" type="text" class="login-input" placeholder="請輸入帳號" required="required"><br><br>
                <label for="pwd" class="login-label">密碼</label><br>
                <input id="pwd" name="pwd" type="password" class="login-input" placeholder="請輸入密碼" required="required"><br><br>
                <button id="login-button">會員登入</button><br><br>
                <div class="box">
                    <input type="checkbox" name="keeplog" id="keeplog">
                    <label for="keeplog">保持登入</label>
                </div>
            </div>

            <div class="under-login">
                <div class="u1">
                    <a href="./reset_password.html"><img src="/member/icon_public/unlock.png" class="icon">
                        忘記密碼</a>

                </div>
                <div class="u2">
                    <img src="/member/icon_public/add-user.png" class="icon">
                    <a href="register.html">註冊會員</a>
                </div>

            </div>
        </form>
    </div>
</div>

<div class="bottom">
        <div class="footer">
            <div class="footertxt">
                <p>版權聲明/使用條款/聯繫信息/首頁</p>
                <p>xxx12345@gmail.com</p>
            </div>
            <div class="media">
                <div class="icon">
                    <img src="icon_public/facebook.png">
                    <a>facebook</a>
                </div>
                <div class="icon">
                    <img src="icon_public/twitter.png">
                    <a>twitter</a>
                </div>
                <div class="icon">
                    <img src="icon_public/instagram.png">
                    <a>instagram</a>
                </div>
            </div>
        </div>
    </div>

</body>

</html>