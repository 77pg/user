<?php
session_start();
require 'db.php';

// 取得目前登入帳號的uid
if(isset($_COOKIE['token'])){
    $token = $_COOKIE['token'];
    $stmt = $mysqli->prepare("SELECT uid from userinfo where token = ?");
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $uid_row = $result->fetch_assoc();
    $uid = $uid_row['uid'];
}

// collect table
$favorites_stmt = $mysqli->prepare("SELECT pid FROM collect WHERE uid = ?");
$favorites_stmt->bind_param('s', $uid);
$favorites_stmt->execute();
$fav_result = $favorites_stmt->get_result();
$favorites = [];
while ($favRow = $fav_result->fetch_assoc()) {
    // 將此會員的收藏清單的pid加入陣列
    $favorites[] = $favRow['pid'];
}

if (isset($_GET['pid'])) {
    $pid = $_GET['pid'];
    $sql = "SELECT * FROM product WHERE pid = $pid";
    $result = $mysqli->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $pname = $row["pname"];
        $price = $row["price"];
        $P_discount = $row["P_discount"];
        // $picnumber = $row["picnumber"];
        $PmainImage = $row["pimage"];
        $subgraph1 = $row["pimage_1"];
        $subgraph2 = $row["pimage_2"];
        $subgraph3 = $row["pimage_3"];
        $subgraph4 = $row["pimage_4"];
        // 四捨五入
        $PfinalPrice =round($price*$P_discount) ;
        $Pintroduction = $row["pcontent"];
        $Pstandard = $row["pcontent_spec"];
        $editor = $row["pcontent_main"];
        // 檢查是否有該商品pid
        $isFavorited = in_array($row['pid'], $favorites);
    } else {
        echo "Product not found.";
    }
} else {
    echo "Invalid product ID.";
}

// $conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品內頁</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link rel="stylesheet" href="../CSS/reset.css">
    <link rel="stylesheet" href="../CSS/navbar.css">
    <link rel="stylesheet" href="../CSS/insidePage.css">
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
                    <div class="dropdown-content" id="pcart">
                        <?php
                        // 檢查 Session 中的登入狀態
                        if (isset($_COOKIE['token'])) {
                            echo '<li>您的商品:</li>';
                            echo '<div id="cartInfo" style="color:black;padding:10px;"></div></br>';
                            echo '<p id="totalCartAmount" style="color:black;padding:10px;">總金額： 0</p>';
                            echo '<li><a href="../Member area/shoppingcart.php">結帳</a></li>';
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

    <!-- 內容 -->
    <div class="inside">
        <div class="info">
            <div class="pic">
                <div class="big">
                    <img src="<?= $PmainImage; ?>" alt="" class='mySlides'>
                    <img src="<?= $subgraph1; ?>" alt="" class='mySlides'>
                    <img src="<?= $subgraph2; ?>" alt="" class='mySlides'>
                    <img src="<?= $subgraph3; ?>" alt="" class='mySlides'>
                </div>
                <div class="small">
                    <img src="<?= $PmainImage; ?>" alt="" class='demo' onclick="currentSlide(1)">
                    <img src="<?= $subgraph1; ?>" alt="" class='demo' onclick="currentSlide(2)">
                    <img src="<?= $subgraph2; ?>" alt="" class='demo' onclick="currentSlide(3)">
                    <img src="<?= $subgraph3; ?>" alt="" class='demo' onclick="currentSlide(4)">
                </div>
            </div>

            <div class="text">
                <h1 id="pi_name">
                    <?php echo $pname; ?>
                </h1>
                <div class="caption">
                    <p>
                        <?php echo $row["pcontent"]; ?>
                    </p>
                    <p>分類：
                        <?= $Pstandard; ?>
                    </p>
                </div>
                <div class="box">
                    <div data-pid="<?php echo $row['pid'] ?>" class="heart<?php echo $isFavorited ? ' liked' : ''; ?>"></div>
                </div>
                <div class="price">
                    NT$<span style="text-decoration:line-through">
                        <?php echo $price?>
                    </span>
                    <br>
                    <span style="font-size:22px;color:red;">NT$</span>
                    <span id="pi_price" style="font-size:22px;color:red;">
                        <?php echo $PfinalPrice; ?>
                    </span>
                    <!-- <p style="color: red;">是否活動折扣</p> -->
                </div>
                <div class="btn">
                    <a href=""><button class="bigbutton">立即購買<img src="../icon_public/bag.png"></button></a>
                    <div class="count">
                        <div class="countbutton" id="decreaseButton">-</div>
                        <div class="num" id="numberDisplay">1</div>
                        <div class="countbutton" id="increaseButton">+</div>
                        <a href="javascript:void(0);" onclick="handleCartButtonClick()">
                            <div class="cartbutton">加入購物車<img src="../icon_public/shopping-cart.png"></div>
                        </a>

                        <script>
                            function handleCartButtonClick() {
                                <?php
                                // 檢查登入狀態
                                if (isset($_COOKIE['token'])) {
                                    echo 'addToCart();';

                                } else {
                                    echo 'window.location.href = "../login/login.php";';
                                }
                                ?>
                            }
                        </script>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- 商品大圖 -->
    <div class="title">
        <h2>商品詳細</h2>
    </div>

    <div class="picinfo">
        <div class="photo">

            <?= $editor; ?>

        </div>
    </div>
    <!-- 方形輪播 -->
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

</body>

<script>
    // 愛心按鈕處理
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

    // 商品圖切換js
    document.addEventListener("DOMContentLoaded", function() {
        // 獲取所有的縮略圖
        var thumbnails = document.querySelectorAll('.thumbnail');

        // 獲取主圖並保存其原始src
        var mainImage = document.getElementById('mainImage');
        var originalSrc = mainImage.getAttribute('src');

        // 為每個縮略圖綁定事件
        thumbnails.forEach(function(thumbnail) {
            thumbnail.addEventListener('click', function() {
                // 獲取被點擊縮略圖的src
                var src = this.getAttribute('src');

                // 將主圖的src設為被點擊縮略圖的src
                mainImage.setAttribute('src', src);
            });
        });
    });
</script>
<script src="../js/script.js"></script>
<script src="../js/insidePage.js"></script>

</html>