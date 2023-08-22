<?php
session_start();
require('db.php');

$token = $_COOKIE['token'];

$sql = 'SELECT * FROM userinfo WHERE token = ?';
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('s', $token);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$cname = $row['cname'];
$phone = $row['phone'];
$email = $row['email'];

// 更新資料
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newCname = $_REQUEST['cname'];
    $newPhone = $_REQUEST['phone'];
    $newEmail = $_REQUEST['email'];

    // 驗證手機號碼格式
    if (!preg_match('/^09\d{2}\d{6}$/', $newPhone)) {
        echo '<script>alert("手機號碼格式錯誤");</script>';
    } elseif (!preg_match('/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/', $newEmail)) {
        echo '<script>alert("電子郵件格式錯誤");</script>';
    } else {
        $updateSql = 'call memberupdate(?,?,?,?)';
        $updateStmt = $mysqli->prepare($updateSql);
        $updateStmt->bind_param('ssss', $token, $newCname, $newPhone, $newEmail);
        $updateStmt->execute();
        $updateStmt->bind_result($result);
        $updateStmt->fetch();
        $updateStmt->close();
        if ($result === '更改失敗，電子信箱已綁定') {
            echo '<script>alert("電子信箱已綁定其他帳號");</script>';
        } else if ($result === '更改失敗，手機號碼已綁定') {
            echo '<script>alert("手機號碼已綁定其他帳號");</script>';
        } 
        else if ($result === '會員更改成功') {
            echo '<script>';
            echo 'alert("會員更改成功");';
            echo 'setTimeout(function() {';
            echo '    window.location.href = "update.php";'; // 修改成相應的頁面路徑
            echo '}, 1000);'; // 1秒後進行重定向
            echo '</script>';
            exit();
        }
    }
}

$stmt->close();
$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>會員專區-更新</title>
    <link rel="stylesheet" href="../CSS/reset.css">
    <link rel="stylesheet" href="../CSS/update.css">
    <link rel="stylesheet" href="../CSS/navbar.css">
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
            <form action="" method="post" id="updateForm">
                <p>更新個人資訊</p>
                <h1>姓名</h1>
                <input type="text" id="cname" name="cname" value="<?php echo $cname; ?>">
                <h1>手機</h1>
                <input type="text" id="phone" name="phone" value="<?php echo $phone; ?>" placeholder="請輸入手機號碼，ex:0911222333" pattern="09\d{2}\d{6}">
                <h1>電子郵件</h1>
                <input type="text" id="email" name="email" value="<?php echo $email; ?>" placeholder="請輸入電子信箱" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$">
                <br>
                <button id="updateButton" type="button">確定修改</button>
            </form>
        </div>
        <script>
            document.getElementById("updateButton").addEventListener("click", function () {
                var newName = document.getElementById("cname").value;
                var newPhone = document.getElementById("phone").value;
                var newEmail = document.getElementById("email").value;

                if (newName === "<?php echo $cname; ?>" &&
                    newPhone === "<?php echo $phone; ?>" &&
                    newEmail === "<?php echo $email; ?>") {
                    alert("未做任何修改");
                    return;
                }

                document.getElementById("updateForm").submit();
            });

        </script>


    </div>
</body>

</html>