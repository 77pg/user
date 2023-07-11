-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： localhost
-- 產生時間： 2023-07-11 18:55:25
-- 伺服器版本： 10.4.28-MariaDB
-- PHP 版本： 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `charites`
--

DELIMITER $$
--
-- 程序
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `login` (`myuid` VARCHAR(20), `mypwd` VARCHAR(20))   BEGIN
 DECLARE n int DEFAULT 0;
        DECLARE mytoken varchar(40) DEFAULT uuid();
    SELECT COUNT(*)INTO n FROM userinfo WHERE uid=myuid and pwd = mypwd;
    IF n=1 THEN
        update userinfo set token=mytoken where uid =myuid;
     SELECT 'welcome.php' as result,mytoken as token;
    else 
     SELECT 'error.html' as result,null as token;
      
    END IF ;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `logout` (`mytoken` VARCHAR(40))   BEGIN 
 UPDATE userinfo set token=null WHERE token=mytoken;
end$$

DELIMITER ;

-- --------------------------------------------------------

--
-- 資料表結構 `userinfo`
--

CREATE TABLE `userinfo` (
  `uid` varchar(50) NOT NULL,
  `gender` varchar(20) NOT NULL,
  `cname` varchar(50) NOT NULL,
  `pwd` varchar(50) NOT NULL,
  `image` mediumblob DEFAULT NULL,
  `email` varchar(70) DEFAULT NULL,
  `phone` int(11) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `token` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `userinfo`
--

INSERT INTO `userinfo` (`uid`, `gender`, `cname`, `pwd`, `image`, `email`, `phone`, `address`, `token`) VALUES
('A01', 'male', '王大明', '1234', NULL, 'wangdaming01@example.com', 1111, '台北市中山路一段一號', ''),
('A02', 'female', 'Amy', '4567', NULL, 'amy002@example.com', 2222, '台中市一中街2號', '');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `userinfo`
--
ALTER TABLE `userinfo`
  ADD PRIMARY KEY (`uid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
