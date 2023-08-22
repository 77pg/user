<?php
header('Content-Type: application/json');

$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'charites';

// 建立與資料庫的連接
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連接是否成功
if ($conn->connect_error) {
    die('連接失敗：' . $conn->connect_error);
}

$memberId = isset($_GET['memberId']) ? $_GET['memberId'] : '';

// 使用字串插值構建 SQL 查詢
$sql = "SELECT cname, uid,pwd, email, phone, gender, state FROM userinfo WHERE uid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $memberId);
$stmt->execute();
$result = $stmt->get_result();
$member = $result->fetch_assoc();

$stmt->close();

// 回傳單個會員資料
echo json_encode($member);
?>
