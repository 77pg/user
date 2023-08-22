<?php
require 'DB.php';
$sql = "SELECT * FROM product ORDER BY RAND() LIMIT 4;";
$result = mysqli_query($conn, $sql);
$products = array();

if ($result && mysqli_num_rows($result) >= 4) {
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = array(
            'pname' => $row['pname'],
            'pid' => $row['pid']
        );
    }
}


mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>適性測驗</title>
    <link rel="stylesheet" href="../CSS/reset.css">
    <link rel="stylesheet" href="../CSS/navbar.css">
    <link rel="stylesheet" href="../CSS/test.css">
</head>
<style>
    .itemA{
    width: 500px;
    height: 500px;
    background-image: url(../test/image/newtile-all4-13-4.png);
    background-size: 100%;
    background-repeat: no-repeat;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    }
    .itemB{
    width: 500px;
    height: 500px;
    background-image: url(../test/image/newtile-all4-35-4.png);
    background-size: 100%;
    background-repeat: no-repeat;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    }
    .itemC{
        width: 500px;
        height: 500px;
        background-image: url(../test/image/newtile-all4-18-2-3.png);
        background-size: 100%;
        background-repeat: no-repeat;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .itemD{
        width: 500px;
        height: 500px;
        background-image: url(../test/image/newtile-all4-13-6.png);
        background-size: 100%;
        background-repeat: no-repeat;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
</style>
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
                            echo '<li><a href="../Member area/update.php">變更密碼</a></li>';
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

    <div class="txtbox">
        <div class="txt">
            <p>「選擇，展現你的個性。參與心理測驗，探索你的飾品風格。」</p>
            <p>測試你的喜好和品味，尋找最適合的手作飾品。</p>
        </div>
    </div>    

    <div class="choose">
        <div class="item">
            <img src="./image/newtile-all4-43-3.png" id="forA">
            <img src="./image/newtile-all4-35-3.png" id="forB">
        </div>
        <div class="item">
            <img src="./image/newtile-all4-18-2.png" id="forC">
            <img src="./image/newtile-all4-13-5.png" id="forD">
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

    <div class="noshow" id="showScreenA">
        <div class="itemA">
            <div class="itematitle">
                <p>適合你的是：</p>
                <a href="../Product outside/insidePage1.php?pid=<?php echo $products[0]['pid']; ?>"><?php echo $products[0]['pname']; ?></a>
            </div>
            <div class="itemaimg">
                <div class="img"><img src="./image/Alphabet A.png"></div>
                <div class="itemaimgtxt">
                    <p>細心、溫和、理性，注重秩序和紀律，但可能過於保守和固執</p>
                </div>
            </div>
            <div class="itemtxt">
                <p>你通常會考慮風險和機會，但有時也需要學會冒險和創新。你很可能是一個喜歡閱讀、研究、學習新知識的人。你的個性讓你在工作和學業上取得成功，但也需要時刻注意自己的情緒和與他人的互動。</p>
            </div>
        </div>
    </div>
    <div class="noshow" id="showScreenB">
        <div class="itemB">
            <div class="itematitle">
                <p>適合你的是：</p>
                <a href="../Product outside/insidePage1.php?pid=<?php echo $products[1]['pid']; ?>"><?php echo $products[1]['pname']; ?></a>
            </div>
            <div class="itemaimg">
                <div class="img"><img src="./image/Alphabet B.png"></div>
                <div class="itemaimgtxt">
                    <p>細心、溫和、理性，注重秩序和紀律，但可能過於保守和固執</p>
                </div>
            </div>
            <div class="itemtxt">
                <p>你通常會考慮風險和機會，但有時也需要學會冒險和創新。你很可能是一個喜歡閱讀、研究、學習新知識的人。你的個性讓你在工作和學業上取得成功，但也需要時刻注意自己的情緒和與他人的互動。</p>
            </div>
        </div>
    </div>
    <div class="noshow" id="showScreenC">
        <div class="itemC">
            <div class="itematitle">
                <p>適合你的是：</p>
                <a href="../Product outside/insidePage1.php?pid=<?php echo $products[2]['pid']; ?>"><?php echo $products[2]['pname']; ?></a>
            </div>
            <div class="itemaimg">
                <div class="img"><img src="./image/Alphabet C.png"></div>
                <div class="itemaimgtxt">
                    <p>細心、溫和、理性，注重秩序和紀律，但可能過於保守和固執</p>
                </div>
            </div>
            <div class="itemtxt">
                <p>你通常會考慮風險和機會，但有時也需要學會冒險和創新。你很可能是一個喜歡閱讀、研究、學習新知識的人。你的個性讓你在工作和學業上取得成功，但也需要時刻注意自己的情緒和與他人的互動。</p>
            </div>
        </div>
    </div>
    <div class="noshow" id="showScreenD">
        <div class="itemD">
            <div class="itematitle">
                <p>適合你的是：</p>
                <a href="../Product outside/insidePage1.php?pid=<?php echo $products[3]['pid']; ?>"><?php echo $products[3]['pname']; ?></a>
            </div>
            <div class="itemaimg">
                <div class="img"><img src="./image/Alphabet D.png"></div>
                <div class="itemaimgtxt">
                    <p>細心、溫和、理性，注重秩序和紀律，但可能過於保守和固執</p>
                </div>
            </div>
            <div class="itemtxt">
                <p>你通常會考慮風險和機會，但有時也需要學會冒險和創新。你很可能是一個喜歡閱讀、研究、學習新知識的人。你的個性讓你在工作和學業上取得成功，但也需要時刻注意自己的情緒和與他人的互動。</p>
            </div>
        </div>
    </div>

</body>
<script src="../JS/test.js"></script>
</html>