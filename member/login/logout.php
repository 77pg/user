<?php
session_start();

require('db.php');
$token = $_COOKIE['token'];

// 呼叫 logout 存儲過程，執行其他登出相關操作
$sql_logout = 'CALL logout(?)';
$stmt_logout = $mysqli->prepare($sql_logout);
$stmt_logout->bind_param('s', $token);
$stmt_logout->execute();
$stmt_logout->close();

// 清除 Cookie，並將過期時間設定為過去的時間
setcookie('token', '', time() - 3600, '/');
setcookie('welcome', '', time() - 3600, '/');

// 清除會話
session_destroy();

// 導向登入頁面
header('location: login.php');
?>
