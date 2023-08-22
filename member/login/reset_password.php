<?php
session_start();
require_once("db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $newPassword = $_POST['new_password'];

    // 更新密碼
    $stmt = $mysqli->prepare("UPDATE userinfo SET pwd = ? WHERE email = ?");
    $stmt->bind_param("ss", $newPassword, $email);
    $stmt->execute();
    $stmt->close();

    // 清除 reset_token 和將 reset_token_valid 設置為 0
    $stmt = $mysqli->prepare("UPDATE userinfo SET reset_token = '', reset_token_valid = 0 WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->close();

    
    echo '重設密碼成功。';
}
?>


