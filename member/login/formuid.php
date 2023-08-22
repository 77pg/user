<?php
require('db.php');

$uid = $_REQUEST['uid'];

$sql = "CALL uidregister(?)";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('s', $uid);
$stmt->execute();
$stmt->bind_result($result);
$stmt->fetch();
$stmt->close();

if ($result === 'UID存在') {
    echo '( UID存在，請重新註冊 )';
} else if ($result === '成功註冊') {
    echo '';
}
?>