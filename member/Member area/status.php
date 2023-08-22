<?php

// 狀態映射
$statusMapping = [
    "未處理" => 1,
    "處理中" => 2,
    "已出貨" => 3,
    "已到店" => 4,
    "已收貨" => 5,
];

function renderDot($orderStatusText, $statusDates) {
    global $statusMapping;

    $statuses = [
        1 => "收到訂單",
        2 => "處理中",
        3 => "已出貨",
        4 => "已到店",
        5 => "已收貨",
    ];

    $currentStatus = $statusMapping[$orderStatusText]; // 使用狀態映射來獲得數字代碼
    $output = '<div class="schedule">';

    foreach ($statuses as $statusCode => $statusName) {
        $isCurrent = $statusCode == $currentStatus;
        $isPast = $statusCode < $currentStatus;
        $isFuture = $statusCode > $currentStatus;

        $output .= '<div class="scheduledot">';

        if ($isPast) {
            $output .= "<img src='./img/black-circle.png'>";
        } elseif ($isCurrent) {
            $output .= "<img src='./img/button.png'>";
        } elseif ($isFuture) {
            $output .= "<img src='./img/shape.png'>";
        }

        $output .= "<span>$statusName</span>";

        $dateForStatus = isset($statusDates[$statusCode - 1]) ? $statusDates[$statusCode - 1] : '';
        $output .= "<p>$dateForStatus</p>";

        $output .= '</div>';
    }

    $output .= '</div>';

    return $output;
}
?>

