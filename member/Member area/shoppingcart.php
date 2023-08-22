<?php
require('map.php');
require('db.php');

$total = 0;
$freight = 999;
$discount = 220;

$sql = "SELECT c.pid, c.cart_id, p.pname, p.price, c.count, c.delivery_fee, p.pimage, p.stock
FROM cart c
INNER JOIN product p ON c.pid = p.pid
WHERE c.uid = 'A01'
";
$result = $mysqli->query($sql);
$num_rows = 0;

if ($result) {
    $num_rows = $result->num_rows;
    while ($row = $result->fetch_assoc()) {
        $subtotal = $row["price"] * $row["count"];
        $total += $subtotal;
    }
    if ($total >= 1000) {
        $freight = 0;
    }
    $total += $freight;
    $total -= $discount;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>購物車</title>
    <link rel="stylesheet" href="../CSS/reset.css">
    <link rel="stylesheet" href="../CSS/navbar.css">
    <link rel="stylesheet" href="../CSS/shoppingcart.css">
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
        <hr>
        <div class="navbar">
            <a href="../Product outside/product.php">全部商品</a>
            <a href="../Product outside/product.php">手鍊</a>
            <a href="../Product outside/product.php">耳飾</a>
            <a href="../Product outside/product.php">項鍊</a>
            <a href="../Product outside/product.php">吊飾</a>
        </div>
    </div>
    <div class="box">
        <div class="content">
            <h2>購物車內容</h2>
            <div class="commoditydetail">
                <div class="thead">
                    <div class="leftwidth">
                        <p>商品明細</p>
                    </div>
                    <div class="rightwidth">
                        <p>單價</p>
                        <p>數量</p>
                        <p>小計</p>
                    </div>
                </div>
                <?php
                        if ($result && $result->num_rows > 0) {
                            // 重置指標位置
                            $result->data_seek(0);
                            while ($row = $result->fetch_assoc()) {
                    ?>
                <div class="tbody">
                    <div class="leftwidth">
                        <img src="../Product outside/<?php echo $row["pimage"] ?>">
                        <p class="pname" data-pname="<?php echo $row["pname"]; ?>">
                            <?php echo $row["pname"]; ?>
                        </p>
                    </div>
                    <div class="rightwidth">
                        <p class="price" data-price="<?php echo number_format($row["price"]); ?>">NT$
                            <?php echo number_format($row["price"]); ?>
                        </p>
                        <div class="quantity" data-item-id="<?php echo $row["pid"] ?>" data-stock="
                            <?php echo $row["stock"] ?>" data-price="
                            <?php echo $row["price"] ?>">
                            <button class='decreaseBtn'>-</button>
                            <div class="num">
                                <?php echo $row["count"]; ?>
                            </div>
                            <button class='increaseBtn'>+</button>
                        </div>
                        <p data-subtotal="<?php echo $row["pid"] ?>">NT$
                            <?php echo number_format($row["price"] * $row["count"]); ?>
                        </p>
                    </div>
                </div>
                <?php
                    } // end of while loop
                    ?>
                <div class="calculate">
                    <div class="freight">運費 NT$<div id="freightValue" class="nt">
                            <?php echo $freight ?>
                        </div>
                    </div>
                    <div class="discount">優惠折扣 NT$<div class="nt"><?php echo $discount?></div>
                    </div>
                </div>
                <div class="total">
                    <div class="leftwidth">
                        <p>購物車內共有
                        <div class="itemnum">
                            <?php echo $num_rows; ?>
                        </div>項商品</p>
                    </div>
                    <div class="right">
                        <p>總計 NT$
                        <div id="totalValue" class="count">
                            <?php echo number_format($total); ?>
                        </div>
                        </p>
                    </div>
                </div>

                <?php
                    } else {
                        echo "<p>購物車是空的。</p>";
                    }
                    ?>
            </div>
            <h2 style="margin-top:1rem;">配送地址</h2>
            <form id="checkoutForm" onsubmit="event.preventDefault(); checkout();">
                <div>
                    <div style="margin-bottom:1rem;">
                        <label for="name">收貨人姓名：</label>
                        <input type="text" id="name" placeholder="請輸入姓名">
                    </div>
                    <div style="margin-bottom:1rem;">
                        <label for="phone">聯絡電話：</label>
                        <input type="text" id="phone" placeholder="請輸入手機號碼">
                    </div>
                    <div style="margin-bottom:1rem;">
                        <label for="county">收件地址：</label>
                        <?php
                                echo '<select id="city" onchange="updateDistricts()">';
                                foreach ($locations as $city => $districts) {
                                    echo '<option  value="' . $city . '">' . $city . '</option>';
                                }
                                echo '</select>';
                            ?>
                        <select id="district"></select>
                        <input type="text" id="address" placeholder="請輸入住址">
                    </div>
                    <div>
                        <button type="submit">確認結帳</button>
                    </div>
                </div>
            </form>
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
        <!-- 用於顯示回傳的 ECPay 表單 -->
        <div id="ecpayFormContainer"></div>
        <!-- 一般信用卡測試卡號 : 4311-9522-2222-2222 安全碼 : 222 -->
    </div>
    <script>
        // 行政區功能區塊
        var locations = <?php echo json_encode($locations); ?>;

        window.onload = function () {
            updateDistricts();
        }

        function updateDistricts() {
            var city = document.getElementById('city').value;
            var districtSelect = document.getElementById('district');

            // console.log(locations);
            // 清空目前行政區的選項
            districtSelect.innerHTML = "";

            var districts = locations[city];
            for (var i = 0; i < districts.length; i++) {
                var option = document.createElement('option');
                option.value = districts[i];
                option.textContent = districts[i];
                districtSelect.appendChild(option);
            }
        }
    </script>
    <script src="../JS/checkout.js"></script>
    <script src="../JS/shoppingCart.js"></script>
</body>

</html>