<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// 連結資料庫跟sql.php
$host = "localhost";
// 不建議用root，有資安危險，可以用abuser
$user = "wyuec77";
// 密碼
$pwd = "z1234567";
$db = "charites";

$mysqli = new mysqli($host, $user, $pwd, $db);
?>