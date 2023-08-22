<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

require_once("db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    // 在發送驗證碼郵件之前，檢查資料庫中是否存在指定的電子郵件
    $stmt_check_email = $mysqli->prepare("SELECT email FROM userinfo WHERE email = ?");
    $stmt_check_email->bind_param("s", $email);
    $stmt_check_email->execute();
    $stmt_check_email->store_result();

    if ($stmt_check_email->num_rows > 0) {
        // 資料庫中存在該電子郵件，執行以下發送郵件的代碼
        // 生成驗證碼
        $captcha = generateCaptcha();

        // 將驗證碼存儲到資料庫
        $stmt = $mysqli->prepare("UPDATE userinfo SET reset_token = ? WHERE email = ?");
        $stmt->bind_param("ss", $captcha, $email);
        $stmt->execute();
        $stmt->close();

        // 發送驗證碼郵件
        $subject = "=?UTF-8?B?" . base64_encode('愛不飾手-會員驗證碼') . "?="; //信件標題，解決亂碼問題
        $message =
            '您好
    我們收到您要重設密碼與「愛不飾手」會員使用。您的一次性驗證碼為:' . $captcha . '。' .
            '
    
    若您並未要求此代碼，可能有人誤輸入了您的電子郵件地址，可以忽略此電子郵件。
    
謝謝您!';

        $mail = new PHPMailer(true);

        try {
            // 設定 SMTP 伺服器的相關設定
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'charites00@gmail.com'; // 替換為你的 Gmail 信箱
            $mail->Password = 'mzdsibubtxwsltea'; // 替換為你的 Gmail 安全性第三方應用程式的 密碼
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // 設定寄件人和收件人
            $mail->setFrom('charites00@gmail.com', 'charites'); // 替換為你的寄件人信箱和名稱
            $mail->addAddress($email, '收件人');

            // 設定郵件內容
            $mail->CharSet = 'UTF-8';
            $mail->isHTML(false);
            $mail->Subject = $subject;
            $mail->Body = $message;

            // 發送郵件
            $mail->send();
            $_SESSION['captcha'] = $captcha; // 將驗證碼存儲到 Session
            echo '驗證碼已發送至您的郵箱。';
        } catch (Exception $e) {
            echo '發送驗證碼失敗: ' . $mail->ErrorInfo;
        }
    } else {
        // 資料庫中不存在該電子郵件，顯示錯誤訊息或進行其他處理
        echo '電子郵件不存在於資料庫中！請重新註冊！';
    }

    $stmt_check_email->close();
}

// 生成隨機驗證碼函數
function generateCaptcha()
{
    $length = 6;
    $characters = '0123456789';
    $captcha = '';

    for ($i = 0; $i < $length; $i++) {
        $captcha .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $captcha;
}
?>
