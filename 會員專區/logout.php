<?php session_start();?>
<?php
session_destroy(); 
// 比現在時間小，會消失
setcookie('token','',-1);
setcookie('welcome','',-1);

require('db.php');
$token = $_COOKIE['token'];
$sql = 'call logout(?)';
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('s', $token);
$stmt->execute();


session_destroy(); 
header('location: login.php');
// header('location: login.html');
?>