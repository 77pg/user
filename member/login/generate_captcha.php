<?php
session_start();

// 生成验证码
$captcha = generateCaptcha();
$_SESSION['captcha'] = $captcha;

// 创建图像
$image = imagecreate(100, 40);
$bgColor = imagecolorallocate($image, 255, 255, 255);
$textColor = imagecolorallocate($image, 0, 0, 0);
imagestring($image, 5, 30, 12, $captcha, $textColor);

// 输出图像
header('Content-Type: image/png');
imagepng($image);
imagedestroy($image);

// 生成随机验证码函数
function generateCaptcha() {
    $length = 6;
    $characters = '0123456789';
    $captcha = '';
    
    for ($i = 0; $i < $length; $i++) {
        $captcha .= $characters[rand(0, strlen($characters) - 1)];
    }
    
    return $captcha;
}
?>
