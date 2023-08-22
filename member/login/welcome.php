<?php session_start(); ?>
<?php
if (!$_COOKIE['token']) {
    header('location: login.php');
    die();
}
require('db.php');
$token = $_COOKIE['token'];
$sql = 'select * from userinfo where token = ?';
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('s', $token);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$cname = $row['cname'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>成功登入</title>
    <link rel="stylesheet" href="../CSS/reset.css">
    <link rel="stylesheet" href="../CSS/welcome.css">

</head>

<body>
    
    <?php
    // 印出成功登入的訊息
    echo '<p style="text-align: center;">成功登入，正在跳轉...</p>';
    ?>

    <script>
        setTimeout(function () {
            window.location.href = "../home/home.php";
        }, 2000); // 2秒後跳轉
    </script>
</body>

</html>