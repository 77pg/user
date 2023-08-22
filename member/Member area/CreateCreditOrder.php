<?php
session_start();
// 模擬A01會員登入
$uid = 'A01';
use Ecpay\Sdk\Factories\Factory;
use Ecpay\Sdk\Services\UrlService;

require ("../vendor/autoload.php");

// 從 HTTP Body 中取得 JSON 格式的資料
$json = file_get_contents('php://input');

// 將 JSON 資料轉成陣列
$data = json_decode($json, true);
if(isset($data['productPrices'])){
    $_SESSION['productPrices'] = $data['productPrices'];
}
if(isset($data['name'])){
    $_SESSION['name'] = $data['name'];
}
if(isset($data['phone'])){
    $_SESSION['phone'] = $data['phone'];
}
if(isset($data['fullAddress'])){
    $_SESSION['fullAddress'] = $data['fullAddress'];
}
$factory = new Factory([
    'hashKey' => '5294y06JbISpM5x9',
    'hashIv' => 'v77hoKGq4kWxNNIS',
]);
$autoSubmitFormService = $factory->create('AutoSubmitFormWithCmvService');

$input = [
    'MerchantID' => '2000132',
    'MerchantTradeNo' => time(),
    'MerchantTradeDate' => date('Y/m/d H:i:s'),
    'PaymentType' => 'aio',
    'TotalAmount' => $data['totalAmount'],
    'TradeDesc' => UrlService::ecpayUrlEncode('交易描述範例'),
    // 後續寫入資料庫時，需要解碼再存入
    'ItemName' => $data['itemNameString'],
    'ChoosePayment' => 'Credit',
    'EncryptType' => 1,
    'ClientBackURL' => 'http://localhost/member/Member%20area/search.php',
    // 下面網址為：綠界頁面付款成功後，頁面會導至特店的自製頁面
    // 'OrderResultURL' => 'http://localhost/project/public/ok.html',
    // 請參考 example/Payment/GetCheckoutResponse.php 範例開發
    'ReturnURL' => ' https://5484-118-163-218-100.ngrok.io/member/Member area/GetCheckoutResponse.php',
];

// 綠界支付的網址
$action = 'https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5';

// // 生成自動提交表單
$checkoutForm = $autoSubmitFormService->generate($input, $action);

// 將生成的表單回傳至前端
echo json_encode([
    'checkoutForm' => $checkoutForm
]);
