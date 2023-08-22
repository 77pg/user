<?php
require('db.php');
session_start();
$token = $_COOKIE['token'];

// 取得目前登入帳號的uid
$stmt = $mysqli->prepare("SELECT uid from userinfo where token = ?");
$stmt->bind_param('s', $token);
$stmt->execute();
$result = $stmt->get_result();
$uid_row = $result->fetch_assoc();
$uid = $uid_row['uid'];

// collect table
$favorites_stmt = $mysqli->prepare("SELECT pid FROM collect WHERE uid = ?");
$favorites_stmt->bind_param('s', $uid);
$favorites_stmt->execute();
$fav_result = $favorites_stmt->get_result();
$favorites = [];
while ($favRow = $fav_result->fetch_assoc()) {
    $favorites[] = $favRow['pid'];
}

// collect table 關聯 product table
$stmt2 = $mysqli->prepare("SELECT p.* FROM collect c RIGHT JOIN product p ON p.pid = c.pid WHERE uid = ?");
$stmt2->bind_param('s', $uid);
$stmt2->execute();
$result2 = $stmt2->get_result();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>會員專區-收藏</title>
    <link rel="stylesheet" href="../CSS/reset.css">
    <link rel="stylesheet" href="../CSS/navbar.css">
    <link rel="stylesheet" href="../CSS/collect.css">
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
    <div class="frist">

        <div class="sidebar">
            <ul>
                <li><a href="update.php">更新個人資訊</a></li>
                <li><a href="password.php">變更密碼</a></li>
                <li><a href="search.php">訂單查詢</a></li>
                <li><a href="collect.php">收藏清單</a></li>
            </ul>
        </div>
        <!-- 內容 -->
        <div class="all">
            <p>收藏清單</p>
            <div class="cards">
                <div class="container">
                    <?php while ($row = $result2->fetch_assoc()) {
                        $isFavorited = in_array($row['pid'], $favorites);
                        
                    ?>
                        <div class="card">
                            <div class="box">
                                <div class="card-img">
                                    <a href="../Product outside/insidePage1.php?pid=<?php echo $row['pid'] ?>"><img src="../Product outside/<?php echo $row['pimage'] ?>" class="card-img"></a>
                                </div>
                                <!-- 如果被收藏，添加"liked" class  -->
                                <div data-pid="<?php echo $row['pid'] ?>" class="heart<?php echo $isFavorited ? ' liked' : ''; ?>"></div>
                            </div>
                            <div class="state">
                                <h3><?php echo $row['pname'] ?></h3>
                                <p>NT$<?php echo round($row['price']*$row['P_discount'])?> 元</p>
                            </div>
                        </div>
                    <?php } ?>
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

                    fetch("toggle_favorite.php", {
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
    </script>
</body>

</html>