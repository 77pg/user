<?php session_start(); ?>
<?php 
require('db.php');

// $uid = $_REQUEST['uid'];
$uid = $_REQUEST['uid'];
$pwd = $_REQUEST['pwd'];

$sql = "call login(?, ?)";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('ss', $uid, $pwd);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$nextPage=$row['result'];
$token=$row['token'];
if($nextPage ==='welcome.php'){
    setcookie('token',$token,time()+1200);
    setcookie('welcome',$nextPage,time()+1200);
    
}
header("location:{$nextPage}");