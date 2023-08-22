<?php
header('Content-Type: application/json');

// 獲取 POST 請求中的數據
$data = json_decode(file_get_contents('php://input'), true);

// 建立與資料庫的連接
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'charites';

$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連接是否成功
if ($conn->connect_error) {
    die('連接失敗：' . $conn->connect_error);
}

// 確認接收到的數據
$uid = isset($data['uid']) ? $data['uid'] : null;
$cname = isset($data['cname']) ? $data['cname'] : null;
$pwd = isset($data['upwd']) ? $data['upwd'] : null;
$phone = isset($data['cphone']) ? $data['cphone'] : null;
$email = isset($data['uemail']) ? $data['uemail'] : null;
$gender = isset($data['gender']) ? $data['gender'] : null;
$state = isset($data['state']) ? $data['state'] : null;

// 執行存儲過程
$stmt = $conn->prepare("CALL UpdateMember(?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $uid, $cname, $pwd, $phone, $email, $gender, $state);
$stmt->execute();
$stmt->bind_result($result);
$stmt->fetch();
$stmt->close();

// 回傳處理結果
echo $result;
//這樣，當用戶在前端提交表單時，會先顯示 alert 提示框，顯示表單的更改內容，然後用戶確認後才會將數據送到後端 PHP 檔案進行處理，最後前端會顯示處理結果的 alert。






// // 執行存儲過程
// $stmt = $conn->prepare("CALL UpdateMember(?, ?, ?, ?, ?, ?, ?)");
// $stmt->bind_param("sssssss", $uid, $cname, $pwd, $phone, $email, $gender, $state);
// $stmt->execute();
// $stmt->bind_result($result);
// $stmt->fetch();
// $stmt->close();
// if ($result == '會員更改成功') {
//     echo '會員更改成功';
// } 
// else if ($result == '更改失敗，電子信箱已綁定') {
//     echo 'EMAIL已綁定其他會員';
//     exit();
// }
// else if ($result == '更改失敗，手機號碼已綁定') {
//     echo '手機號碼已綁定其他會員';
//     exit();
// }else{
//     echo '更改失敗';
//     exit();
// }

?>
