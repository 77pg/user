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

// 動態指定欄位名稱
$selectFields = 'cname, uid, DATE_FORMAT(TIME, "%Y-%m-%d") AS date, state'; // 使用DATE_FORMAT函數格式化日期

// 使用字串插值構建 SQL 查詢
$sql = "SELECT $selectFields FROM userinfo";
$result = $conn->query($sql);

$members = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $members[] = array(
            'cname' => $row['cname'],
            'uid' => $row['uid'],
            'date' => $row['date'], // 格式化後的日期
            'state' => $row['state']
        );
    }
}

$conn->close();

// 回傳會員資料
echo json_encode($members);
?>
