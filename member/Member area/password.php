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

$storedPwd = $row['pwd']; // 從資料庫中取得的明文密碼

$showStep1 = true;
$showStep2 = false;
$showSuccess = false;
$errorMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['step']) && $_POST['step'] === '1') {
        $userInput = $_POST['oldPassword'];

        // 比對使用者輸入的舊密碼是否與儲存的明文密碼相符
        if ($userInput === $storedPwd) {
            $showStep1 = false;
            $showStep2 = true;
            echo '<script>alert("舊密碼驗證成功！");</script>';
        } else {
            $errorMsg = '舊密碼驗證失敗！';
        }

    } elseif (isset($_POST['step']) && $_POST['step'] === '2') {
        $newPassword = $_POST['newPassword'];
        $confirmPassword = $_POST['confirmPassword'];

        if ($newPassword === $confirmPassword) {
            // 更新資料庫中的密碼
            $updateSql = 'UPDATE userinfo SET pwd = ? WHERE token = ?';
            $updateStmt = $mysqli->prepare($updateSql);
            $updateStmt->bind_param('ss', $newPassword, $token);
            $updateStmt->execute();

            $showStep2 = false;
            $showSuccess = true;

        }
    }
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
    <link rel="stylesheet" href="../CSS/password.css">
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
            <form method="post" action="">
                <?php if ($showStep1) { ?>
                    <p>變更密碼 - 步驟 1</p>
                    <h1>輸入舊密碼</h1>
                    <input type="password" id="oldPassword" name="oldPassword">
                    <img id="toggleCheckOldPassword" alt="toggleCheckPassword" src="../icon_public/eye-closed.png"
                        onclick="togglePasswordVisibility('oldPassword', 'toggleCheckOldPassword')"
                        style="cursor: pointer; width: 20px; height: 20px; vertical-align: middle; margin-left: 10px;">
                    <input type="hidden" name="step" value="1">
                    <input type="submit" value="下一步">
                    <?php if ($errorMsg) {
                        echo '<p style="color: red;">' . $errorMsg . '</p>';
                    } ?>
                <?php } elseif ($showStep2) { ?>
                    <p>變更密碼 - 步驟 2</p>
                    <h1>輸入新密碼</h1>
                    <input type="password" id="newPassword" name="newPassword" placeholder="請輸入8~20位數，需包含一個英文及一個數字，英文不分大小寫" pattern="[a-zA-Z0-9]{8,}">
                    <img id="toggleCheckNewPassword" alt="toggleCheckNewPassword" src="../icon_public/eye-closed.png"
                        onclick="togglePasswordVisibility('newPassword', 'toggleCheckNewPassword')"
                        style="cursor: pointer; width: 20px; height: 20px; vertical-align: middle; margin-left: 10px;">
                    <h1>確認新密碼</h1>
                    <input type="password" id="confirmPassword" name="confirmPassword" placeholder="請輸入8~20位數，需包含一個英文及一個數字，英文不分大小寫" pattern="[a-zA-Z0-9]{8,}">
                    <img id="toggleCheckConfirmPassword" alt="toggleCheckConfirmPassword"
                        src="../icon_public/eye-closed.png"
                        onclick="togglePasswordVisibility('confirmPassword', 'toggleCheckConfirmPassword')"
                        style="cursor: pointer; width: 20px; height: 20px; vertical-align: middle; margin-left: 10px;">
                    <br>
                    <input type="hidden" name="step" value="2">
                    <button id="updateButton">確定修改</button>
                <?php } ?>
            </form>
        </div>
    </div>
    <script>
        function togglePasswordVisibility(inputId, iconId) {
        var passwordInput = document.getElementById(inputId);
        var toggleIcon = document.getElementById(iconId);

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            toggleIcon.src = "../icon_public/eye-open.png";
        } else {
            passwordInput.type = "password";
            toggleIcon.src = "../icon_public/eye-closed.png";
        }
    }
    document.getElementById("updateButton").addEventListener("click", function(event) {
        var newPassword = document.getElementById("newPassword").value;
        var confirmPassword = document.getElementById("confirmPassword").value;

        // 檢查是否有錯誤訊息，如果有則阻止表單提交
        if (newPassword !== confirmPassword) {
            alert('密碼不一致！');
            event.preventDefault(); // 阻止表單提交
            return;
        }

        var passwordPattern = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;
        if (!passwordPattern.test(newPassword)) {
            alert('請輸入8~20位數，需包含一個英文及一個數字，英文不分大小寫');
            event.preventDefault(); // 阻止表單提交
            return;
        }

        // 若一致且符合格式，則提交表單並彈出更新成功的提示
        if (confirm('確定要修改密碼嗎？')) {
            alert('更新密碼成功！');
        } else {
            event.preventDefault(); // 阻止表單提交
        }
    });
    </script>

    <!-- <script>
    function togglePasswordVisibility(inputId, iconId) {
        var passwordInput = document.getElementById(inputId);
        var toggleIcon = document.getElementById(iconId);

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            toggleIcon.src = "../icon_public/eye-open.png";
        } else {
            passwordInput.type = "password";
            toggleIcon.src = "../icon_public/eye-closed.png";
        }
    }

    document.getElementById("updateButton").addEventListener("click", function(event) {
        var newPassword = document.getElementById("newPassword").value;
        var confirmPassword = document.getElementById("confirmPassword").value;

        // 檢查是否有錯誤訊息，如果有則阻止表單提交
        if (newPassword === confirmPassword) {
            alert('更新密碼成功！');
        } else {
            alert('密碼不一致！');
            event.preventDefault(); // 阻止表單提交
        }
    });
</script> -->
</body>

</html>