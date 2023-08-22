<?php
require('db.php');
require('status.php');  

// 獲取所有訂單
$sql_orders = "SELECT o.*, s.status AS order_status
FROM orders o
LEFT JOIN (
    SELECT order_id, status
    FROM order_status
    WHERE (order_id, status_timestamp) IN (
        SELECT order_id, MAX(status_timestamp)
        FROM order_status
        GROUP BY order_id
    )
) AS s ON o.order_id = s.order_id
WHERE o.uid = 'A01'
ORDER BY o.order_date DESC;";
$result_orders = $mysqli->query($sql_orders);

$all_orders = [];

while($order = $result_orders->fetch_assoc()) {
    // 為每個訂單獲取相應的商品
    $sql_order_items = "SELECT o.pid, o.price, o.quantity, p.pname, p.pimage
    FROM order_item o
    JOIN product p ON o.pid = p.pid
    WHERE o.order_id = ?";
    $stmt_items = $mysqli->prepare($sql_order_items);
    $stmt_items->bind_param("s", $order['order_id']);
    $stmt_items->execute();

    $result_items = $stmt_items->get_result();
    $items = [];
    while($item = $result_items->fetch_assoc()) {
        $items[] = $item;
    }

    // 為每個訂單獲取所有狀態的日期
    $sql_status_dates = "SELECT status_timestamp FROM order_status WHERE order_id = ? ORDER BY status_timestamp";
    $stmt_dates = $mysqli->prepare($sql_status_dates);
    $stmt_dates->bind_param("s", $order['order_id']);
    $stmt_dates->execute();

    $result_dates = $stmt_dates->get_result();
    $statusDates = [];
    while($date = $result_dates->fetch_assoc()) {
        $statusDates[] = $date['status_timestamp'];
    }

    $all_orders[] = [
        'order_id' => $order['order_id'],
        'order_date' => $order['order_date'],
        'order_status' => $order['order_status'],
        'status_dates' => $statusDates,
        'items' => $items
    ];
    $stmt_items->close();
    $stmt_dates->close();
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>會員專區-密碼</title>
    <link rel="stylesheet" href="../CSS/reset.css">
    <link rel="stylesheet" href="../CSS/navbar.css">
    <link rel="stylesheet" href="../CSS/search.css">
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

    <div class="container">

        <div class="sidebar">
            <ul>
                <li><a href="update.php">更新個人資訊</a></li>
                <li><a href="password.php">變更密碼</a></li>
                <li><a href="search.php">訂單查詢</a></li>
                <li><a href="collect.php">收藏清單</a></li>
            </ul>
        </div>
        <div class="context">
            <div class="main">
                <!--  -->
                <?php
             foreach($all_orders as $order_detail){
                ?>
                        <div class="order" style="margin-bottom:1rem;">
                            <div class="day">
                                <div class="daycontent">
                                    <p>訂單編號</p><span><?php echo $order_detail['order_id'] ?></span>
                                    <p>訂購日期</p><span><?php echo $order_detail['order_date'] ?></span>
                                    <p>付款方式</p><span>信用卡一次付清</span>
                                </div>
                                <div class="plusicon">
                                    <img src="./img/instagram-post.png" class="plus">
                                    <img src="./img/minus.png" class="display minus">
                                </div>
                            </div>
                            <div class="zoom display">
                                <!--  -->
                                <?= renderDot($order_detail['order_status'], $order_detail['status_dates'] ?? []) ?>
                                <?php foreach($order_detail['items'] as $item ) {?>
                                <div class="orderitem">
                                    <div class="ordername">
                                        <div class="orderimg"><img src="../Product outside/<?php echo $item['pimage'] ?>"></div>
                                        <p><?php echo $item['pname'] ?></p>
                                    </div>
                                    <div class="ordercount">
                                        <p>單&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;價：<?php echo $item['price'] ?></p>
                                        <p>數&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;量：<?php echo $item['quantity'] ?></p>
                                        <p>優惠折扣：NT$ 0</p>
                                        <p>小&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;計：NT$ <?php echo $item['price']*$item['quantity'] ?></p>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                <?php } ?>
            </div>
        </div>


    </div>


    <script src="../JS/search.js"></script>
</body>

</html>