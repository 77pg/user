<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// 連結資料庫跟sql.php
$host = "localhost";
$user = "root";
$pwd = "";
$db = "addressbook";

$mysqli = new mysqli($host, $user, $pwd, $db);
?>