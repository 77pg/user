<?php
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'charites';

// 建立與資料庫的連線
$conn = new mysqli($hostname, $username, $password, $database);

// 檢查連線是否成功
if ($conn->connect_error) {
    die("連線失敗：" . $conn->connect_error);
}
?>
