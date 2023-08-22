<?php
session_start();
$uid = 'A01';
error_reporting(E_ALL);
ini_set('log_errors', '1');
ini_set('error_log', '../../logfile.txt');
ini_set('display_errors', '0');



require('db.php');

use Ecpay\Sdk\Factories\Factory;
use Ecpay\Sdk\Response\VerifiedArrayResponse;

require ("../vendor/autoload.php");

$factory = new Factory([
    'hashKey' => '5294y06JbISpM5x9',
    'hashIv' => 'v77hoKGq4kWxNNIS',
]);
$checkoutResponse = $factory->create(VerifiedArrayResponse::class);

// 取得由綠界發送的 POST 請求的資料
$postData = $_POST;

// file_put_contents("../../log.txt", json_encode($postData) . PHP_EOL, FILE_APPEND);
$response = $checkoutResponse->get($postData);

// var_dump($response);

if ($response['RtnCode'] == 1) {
    // 如果交易成功，呼叫createOrderc()
    $order_id = createOrder($mysqli, $response, $uid);
    if ($order_id) {
        createOrderItems($mysqli, $order_id, $uid);
    }
}

// 訂單寫入資料庫
function  createOrder($mysqli, $response, $uid)
{
    $purchaser = '王小明';
    $phone = '0933444555';
    $address = '台中市南屯區大業路';
    $sql = "INSERT INTO orders (uid,order_id, o_total, purchaser, phone, address) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('ssisss', $uid, $response['MerchantTradeNo'], $response['TradeAmt'], $purchaser, $phone, $address);
    $stmt->execute();

    // if ($stmt->affected_rows) {
    //     echo "訂單成功寫入";
    // } else {
    //     echo "訂單寫入失敗";
    // }
    $insertOrderId = $response['MerchantTradeNo'];
    $stmt->close();
    return $insertOrderId;
}

function createOrderItems($mysqli, $order_id, $uid)
{
    // 以uid從購物車中獲取商品pid
    $sql = "SELECT c.pid, p.price, c.count
    FROM cart c
    JOIN product p ON c.pid = p.pid 
    WHERE c.uid = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('s', $uid);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $pid = $row['pid'];
        // 將商品ID和訂單ID存入order_item表中
        $sqlInsert = "INSERT INTO order_item (order_id, pid, price, quantity) VALUES (?, ?, ?, ?)";
        $stmtInsert = $mysqli->prepare($sqlInsert);
        $stmtInsert->bind_param('siii', $order_id, $pid, $row['price'], $row['count']);
        $stmtInsert->execute();
        $stmtInsert->close();
    }
    $stmt->close();
    $mysqli->close();
}
