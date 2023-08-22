<?php
session_start();
require_once("db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $captcha = $_POST['captcha'];

    // 從資料庫中獲取 reset_token 的值
    $stmt = $mysqli->prepare("SELECT reset_token FROM userinfo WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($resetToken);
    $stmt->fetch();
    $stmt->close();

    // 驗證驗證碼是否匹配
    if ($captcha !== $resetToken) {
        echo '驗證碼不正確，請重新輸入。';
    } else {
        echo '驗證成功!';// 驗證成功
    }
}
?>
