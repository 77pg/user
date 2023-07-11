<?php
    require('db.php');


    $uid = $_REQUEST['uid'];
    
    
    $sql = "
    select UserInfo.uid, ifnull(cname, '無') as cname, ifnull(address, '無') as address, ifnull(tel, '無') as tel
    from UserInfo left join Live
       on UserInfo.uid  = Live.uid
       left join House
       on live.hid = House.hid
       left join phone
       on Phone.hid = House.hid
    where UserInfo.uid = '{$uid}'
    ";
    $result = $mysqli->query($sql);
    while($row = $result->fetch_assoc()) {
       echo "{$row['cname']},{$row['address']},{$row['tel']}\n";}
?>