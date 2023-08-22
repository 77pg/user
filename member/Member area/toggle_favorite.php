<?php
require('db.php'); 

if(isset($_POST['uid']) && isset($_POST['pid'])) {
    $uid = $_POST['uid'];
    $pid = $_POST['pid'];

    // 查詢該用戶是否已經收藏了該商品
    $query = "SELECT * FROM collect WHERE uid=? AND pid=?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('si', $uid, $pid);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {  // 如果收藏已存在，則刪除它
        $query = "DELETE FROM collect WHERE uid=? AND pid=?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('si', $uid, $pid);
        $stmt->execute();

        // 回傳JSON
        echo json_encode(['isFavorite' => false]);
    } else {  // 如果收藏不存在，則添加它
        $query = "INSERT INTO collect (uid, pid) VALUES (?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('si', $uid, $pid);
        $stmt->execute();

        // 回傳JSON
        echo json_encode(['isFavorite' => true]);
    }
} else {
    // 回傳錯誤信息
    echo json_encode(['error' => 'Missing parameters']);
}
?>
