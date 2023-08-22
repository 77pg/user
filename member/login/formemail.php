<?php
require('db.php');

$email = $_REQUEST['email'];

$sql = "call emailregister(?)";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->bind_result($result);
$stmt->fetch();
$stmt->close();

if ($result === 'email已存在') {
    echo '( email存在，請重新註冊 )';
} else if ($result === '成功註冊') {
    echo '';
}
?>