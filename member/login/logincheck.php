<?php session_start(); ?>
<?php
require('db.php');
$uid = $_REQUEST['uid'];
$pwd = $_REQUEST['pwd'];

// 檢查帳號是否存在於資料庫
$sql_check_uid = "SELECT COUNT(*) AS count FROM userinfo WHERE uid = ?";
$stmt_check_uid = $mysqli->prepare($sql_check_uid);
$stmt_check_uid->bind_param('s', $uid);
$stmt_check_uid->execute();
$result_check_uid = $stmt_check_uid->get_result();
$row_check_uid = $result_check_uid->fetch_assoc();

if ($row_check_uid['count'] === 0) {
    header('refresh:1;url=login.php');
    echo '<script>alert("無此帳號，請重新輸入");</script>';
    exit;
}

$stmt_check_uid->close();

// 調用 login 存儲過程
$sql_login = "CALL login(?, ?)";
$stmt_login = $mysqli->prepare($sql_login);
$stmt_login->bind_param('ss', $uid, $pwd);
$stmt_login->execute();
$result_login = $stmt_login->get_result();
$row_login = $result_login->fetch_assoc();
$nextPage = $row_login['result'];
$token = $row_login['token'];
$stmt_login->close();

// 檢查帳號密碼是否符合資料庫的資料
if ($nextPage === 'error.html') {
    header("location:error.html"); // 帳號密碼錯誤，導向到 error.html 頁面
    exit;
} elseif ($nextPage === 'welcome.php') {
    // 3600秒=1小時，path加上 '/'，讓所有網頁都能抓取到
    setcookie('token', $token, time() + 3600, '/');
    setcookie('welcome', $nextPage, time() + 3600, '/');
    header("location:{$nextPage}");
    exit;
    // // 3600秒=1小時
    // setcookie('token', $token, time() + 3600, '/login');
    // setcookie('welcome', $nextPage, time() + 3600);
    // header("location:{$nextPage}");
    // exit;
}