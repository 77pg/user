<?php
session_start();

// 引用 db.php 檔案
require('db.php');
// $id = $_GET["pid"];

// 取得目前登入帳號的uid
if(isset($_COOKIE['token'])){
    
    $token = $_COOKIE['token'];
    $stmt = $mysqli->prepare("SELECT uid from userinfo where token = ?");
    $stmt->bind_param('s',$token);
    $stmt->execute();
    $result = $stmt->get_result();
    $uid_row = $result->fetch_assoc();
    $uid = $uid_row['uid'];
}
// var_dump($token);

// collect table
$favorites_stmt = $mysqli->prepare("SELECT pid FROM collect WHERE uid = ?");
$favorites_stmt->bind_param('s', $uid);
$favorites_stmt->execute();
$fav_result = $favorites_stmt->get_result();
$favorites = [];
while ($favRow = $fav_result->fetch_assoc()) {
    $favorites[] = $favRow['pid'];
}




// 查詢語句
$query = "SELECT * FROM product";
$result = mysqli_query($mysqli, $query);

// 檢查查詢結果
if (!$result) {
    die("查詢失敗：" . mysqli_error($mysqli));
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品頁</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/product.css">
    <link rel="stylesheet" href="../css/navbar.css">

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
                        <li><a href="../login/login.php">會員登入</a></li>
                        <li><a href="../login/register.html">註冊新會員</a></li>
                        <li><a href="">購物車</a></li>
                        <li><a href="../Product outside/product.php">全部商品</a></li>
                        <li><a href="../Product outside/product.php">手鍊</a></li>
                        <li><a href="../Product outside/product.php">耳飾</a></li>
                        <li><a href="../Product outside/product.php">項鍊</a></li>
                        <li><a href="../Product outside/product.php">吊飾</a></li>
                    </div>
                </div>
            </div>

            <a href="../home/home.php">
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
                            echo '<li><a href="../logout.php">登出</a></li>';
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

    <!-- 首圖 -->
    <div class="caption">
        <div class="b-pic">
            <img src="./img/003.jpg" />
        </div>
        <div class="b-text">
            <p>「選擇，展現你的個性。參與心理測驗，探索你的飾品風格。」</p>
            <p>測試你的喜好和品味，尋找最適合的手作飾品。</p>
            <div class="test">
                <a href="../test/test.php">點我測驗</a>
            </div>
        </div>
    </div>

    <div class="all">
        <h1 id="pageTitle">全部商品</h1>
    </div>
    <div class="content">
        <!-- 側邊 -->
        <div class="sidebar">
            <ul>
                <li><a class="active" href="../home/home.php">首頁</a></li>
                <li><a href="../Product outside/product.php">全部商品</a></li>
                <li onclick="showCards(1)" id="category1">手鍊</li>
                <li onclick="showCards(2)" id="category2">耳飾</li>
                <li onclick="showCards(3)" id="category3">項鍊</li>
                <li onclick="showCards(4)" id="category4">吊飾</li>
            </ul>
        </div>
        <!-- 右邊內容 -->
        <div class="main">
        <div class="cards">
        <div class="container">
        <?php while ($row = mysqli_fetch_assoc($result)) {
        $isFavorited = in_array($row['pid'], $favorites);
        $pid = $row['pid'];
        $pname = $row['pname'];
        $price = $row['price'];
        $price_final = round($row['price']*$row['P_discount']);
        $pimage = $row['pimage']; ?>
        <div class="card">
            <div class="box">
                <div class="card-img">
                    <a href="insidePage1.php?pid=<?php echo $pid; ?>">
                        <img src="./<?php echo $pimage; ?>" class="card-img">
                    </a>
                </div>
                <div data-pid="<?php echo $row['pid'] ?>" class="heart<?php echo $isFavorited ? ' liked' : ''; ?>"></div>
            </div>
            <div class="state">
                <h3><?php echo $pname; ?></h3>
                <h3>NT$<?php echo $price_final; ?>元</h3>
            </div>
        </div>
        <?php } ?>
        </div>
        </div>
        </div>

    </div>
<?php

?>
    </div>

    <div class="fix">
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
    <script>
       let uid = "<?php echo $uid ?>";
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll(".heart").forEach(heartDiv => {
                heartDiv.addEventListener("click", function() {
                    const pid = heartDiv.getAttribute("data-pid");

                    fetch("../Member area/toggle_favorite.php", {
                            method: "POST",
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: `uid=${uid}&pid=${pid}` 
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.isFavorite) {
                                heartDiv.classList.add("liked");
                            } else {
                                heartDiv.classList.remove("liked");
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                });
            });
        });
    </script>
    <script src="../js/productoutside.js"></script>

</body>



</html>