<?php session_start(); ?>
<?php
// if (!$_SESSION['uid']) {
//     header('location: login.php');
//     die();
// }
if (!$_COOKIE['token']) {
    header('location: login.php');
    die();
}
require('db.php');
// $uid = $_SESSION['uid'];
$token = $_COOKIE['token'];
$sql = 'select * from userinfo where token = ?';
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('s', $token);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$cname = $row['cname'];
$image = $row['image'];
if ($image == null) {
    $image = file_get_contents(("https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ1zbW2LsUxp_IT0_O9-khcIY-6_BnL_pp_Wg&usqp=CAU"));
}
$mime_type = (new finfo(FILEINFO_MIME_TYPE))->buffer($image);
$image_base64 = base64_encode($image);
$src = "data:{$mime_type};base64,{$image_base64}"
    ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="stylesheet" href="./home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link rel="stylesheet" href="./welcome.css">
    <link rel="stylesheet" href="./login.css">
    <script src="script.js" defer></script>

</head>

<body>
    <div class="header">
        <div class="menu">
            <h1>低調廚房</h1>
            <div class="dropdown">
                <span><img src="./img/search.png" alt="搜尋種類"></span>
                <div class="dropdown-content">
                    <ul>
                        <li><a href="">全部商品</a></li>
                        <li><a href="">手鍊</a></li>
                        <li><a href="">耳飾</a></li>
                        <li><a href="">項鍊</a></li>
                        <li><a href="">吊飾</a></li>
                    </ul>
                </div>
            </div>
            <div class="dropdown">
                <span><img src="./img/shopping-cart.png" alt="購物車"></span>
                <div class="dropdown-content">
                    <p>目前的購物車是空的!</p>
                </div>
            </div>
            <div class="dropdown">
                <span><img src="./img/user.png" alt="使用者"></span>
                
                <div class="dropdown-content">
                    <?= $cname ?>
                    <p>您好!</p>
                    <hr>
                    <ul>
                        <li><a href="revise.php">我的帳戶</a></li>
                        <li><a href="orders.php" >訂單查詢</a></li>
                        <li><a href="favorite.php">收藏清單</a></li>
                        <li><a href="logout.php" >會員登出</a></li>
                    </ul>
                </div>
            </div>

            <!-- <ul class="drop-down-menu">
                    <li><a href="https://google.com"><img src="./img/search.png" alt="搜尋種類"></a></li>
                    <li><img src="./img/shopping-cart.png" alt="購物車"></li>
                    <li><img src="./img/user.png" alt="使用者">
                    <li><button onclick="location.href='logout.php'">登出</button></li>
                    <li><button onclick="location.href='revise.php'">修改</button></li>

                    </li>
                </ul> -->
            <!-- <ul>
                <li><a href="https://google.com"><img src="./img/search.png" alt="搜尋種類"></a></li>
                <li><img src="./img/shopping-cart.png" alt="購物車"></li>
                <li class="flip"><img src="./img/user.png" alt="使用者">
                    <div class="panel"><button onclick="location.href='logout.php'">登出</button></div>
                    <div class="panel"><button onclick="location.href='revise.php'">修改</button></div>

                </li>
            </ul> -->
        </div>
        <a href="javascript:" onclick="hide1.style.display=hide1.style.display=='none'?'':'none'">點我展開／隱藏(方法三)</a>

        <span id="hide1" style="display:none">
            <div class="panel"><button onclick="location.href='logout.php'">登出</button></div>
            <div class="panel"><button onclick="location.href='revise.php'">修改</button></div>
        </span>
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
                <li class="breadcrumb-item" style="font-weight: bold;">會員專區</li>
                <!-- <li class="breadcrumb-item"><a href="./login.html">會員登入</a></li> -->

            </ul>
        </div>

        <h1>
            <?= $cname ?>成功登入
        </h1>
        <img src="<?= $src ?>" width="200">
</body>

</html>