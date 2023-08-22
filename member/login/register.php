<?php
#1.獲取請求提交前端的資料，傳入到伺服器
$uid = $_REQUEST['uid'];
$cname = $_REQUEST['cname'];
$upwd = $_REQUEST['upwd'];
$phone = $_REQUEST['phone'];
$gender = $_REQUEST['gender'];
$email = $_REQUEST['email'];

#2.連線到資料庫
require("db.php");


#3.拼sql語句並執行
// $sql="insert into userinfo(uid,gender,cname,pwd,phone,email)values('$uid','$gender','$cname','$pwd','$phone','$email')";
$sql = "call register(?,?,?,?,?,?)";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('ssssss', $uid, $cname, $upwd, $phone, $email, $gender);
$stmt->execute();
$stmt->bind_result($result);
$stmt->fetch();
$stmt->close();

//執行sql語句，成功就導回到首頁
#4.根據執行結果給出響應
if ($result == '註冊失敗，手機號碼已綁定') {
    echo '<script>alert("註冊失敗，手機號碼已綁定其他帳號");window.history.back();</script>';
    // echo '( 註冊失敗，手機號碼已綁定其他帳號 )';
} 
else if ($result == '註冊失敗，電子郵件已綁定') {
    echo '<script>alert("註冊失敗，電子郵件已綁定其他帳號");window.history.back();</script>';
}
else{
    echo '<script>alert("成功註冊");setTimeout(function() { window.location.href = "login.php"; }, 1000);</script>';
    exit();
}
?>
