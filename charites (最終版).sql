-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2023-08-22 08:50:32
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
CREATE DEFINER=`root`@`localhost` PROCEDURE `emailregister` (`myemail` VARCHAR(70))   BEGIN
    DECLARE email_count INT;
    -- 查詢符合結果的數量
    SELECT COUNT(*) INTO email_count FROM UserInfo WHERE email=myemail;
    
    -- 判断 email_count 的值
    IF email_count > 0 THEN
        SELECT 'email已存在' AS result;
    ELSE
        -- 在这里执行插入或其他操作，表示 email 不存在的情况
        SELECT '成功註冊' AS result;
    END IF;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `l1` (IN `myuid` VARCHAR(50), IN `mypwd` VARCHAR(50))   BEGIN
    DECLARE n INT DEFAULT 0;
    DECLARE mytoken VARCHAR(40) DEFAULT uuid();

    SELECT COUNT(*) INTO n FROM userinfo WHERE uid = myuid AND pwd = mypwd;

    IF n = 1 THEN
        UPDATE userinfo SET token = mytoken WHERE uid = myuid;
        SELECT 'welcome.php' AS result, mytoken AS token;
        INSERT INTO log (body) VALUES ('使用者',myuid,'登入成功'); -- 在儲存過程中新增登入成功記錄
    ELSE
        SELECT 'error.html' AS result, NULL AS token;
    END IF;
END$$

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

CREATE DEFINER=`root`@`localhost` PROCEDURE `memberupdate` (`mytoken` VARCHAR(50), `mycname` VARCHAR(50), `myphone` VARCHAR(20), `myemail` VARCHAR(70))   BEGIN
    DECLARE existing_token VARCHAR(50);
    DECLARE existing_phone VARCHAR(20);
    DECLARE existing_email VARCHAR(70);

    -- 檢查是否存在該會員
    SELECT token, phone, email INTO existing_token, existing_phone, existing_email FROM UserInfo WHERE token = mytoken;

    IF existing_token IS NOT NULL THEN
        IF existing_email <> myemail THEN
            IF EXISTS (SELECT email FROM UserInfo WHERE email = myemail) THEN
             SELECT '更改失敗，電子信箱已綁定' AS result;
            ELSE
                IF existing_phone <> myphone THEN
                    IF EXISTS (SELECT phone FROM UserInfo WHERE phone = myphone) THEN
                     SELECT '更改失敗，手機號碼已綁定' AS result;
                    ELSE
                        -- 更新會員資料
                        UPDATE UserInfo
                        SET cname = mycname,
                            phone = myphone,
                            email = myemail
                        WHERE token = mytoken;
                        SELECT '會員更改成功' AS result;
                    END IF;
                ELSE
                    -- 更新會員資料
                        UPDATE UserInfo
                        SET cname = mycname,
                            phone = myphone,
                            email = myemail
                        WHERE token = mytoken;
                        SELECT '會員更改成功' AS result;
                END IF;
            END IF;
        ELSE
            IF existing_phone <> myphone THEN
                IF EXISTS (SELECT phone FROM UserInfo WHERE phone = myphone) THEN
                 SELECT '更改失敗，手機號碼已綁定' AS result;
                ELSE
                     -- 更新會員資料
                        UPDATE UserInfo
                        SET cname = mycname,
                            phone = myphone,
                            email = myemail
                        WHERE token = mytoken;
                        SELECT '會員更改成功' AS result;
                END IF;
            ELSE
                -- 更新會員資料
                        UPDATE UserInfo
                        SET cname = mycname,
                            phone = myphone,
                            email = myemail
                        WHERE token = mytoken;
                        SELECT '會員更改成功' AS result;
            END IF;
        END IF;
    ELSE
        SELECT '更改失敗' AS result;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `register` (`myuid` VARCHAR(50), `mycname` VARCHAR(50), `mypwd` VARCHAR(50), `myphone` VARCHAR(20), `myemail` VARCHAR(70), `mygender` VARCHAR(20))   BEGIN
   
    IF EXISTS (SELECT phone FROM UserInfo WHERE phone = myphone) THEN
         SELECT '註冊失敗，手機號碼已綁定' AS result;
    ELSEIF EXISTS (SELECT email FROM UserInfo WHERE email = myemail) THEN
        SELECT '註冊失敗，電子郵件已綁定' AS result;
    ELSE
        -- 新增一筆資料到userinfo
        INSERT into UserInfo (uid,cname, pwd, phone, email, gender) VALUES (myuid,mycname, mypwd, myphone, myemail, mygender);
        -- 在这里执行插入或其他操作，表示 uid 不存在的情况
        SELECT '成功註冊' AS result;
       
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RegisterMember` (`myuid` VARCHAR(50), `mycname` VARCHAR(50), `mypwd` VARCHAR(50), `myphone` VARCHAR(20), `myemail` VARCHAR(70), `mygender` VARCHAR(20), `mystate` INT)   BEGIN
    IF EXISTS (SELECT phone FROM UserInfo WHERE phone = myphone) THEN
        SELECT '註冊失敗，手機號碼已綁定' AS result;
    ELSE
        -- 新增一筆資料到 userinfo，包括 state 欄位
        INSERT INTO UserInfo (uid, cname, pwd, phone, email, gender, state)
        VALUES (myuid, mycname, mypwd, myphone, myemail, mygender, mystate);
        -- 在这里执行插入或其他操作，表示 uid 不存在的情况
        SELECT '成功註冊' AS result;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `uidregister` (`myuid` VARCHAR(50))   BEGIN
    DECLARE uid_count INT;
    
    -- 查询符合条件的记录数量
    SELECT COUNT(*) INTO uid_count FROM UserInfo WHERE uid = myuid;
    
    -- 判断 uid_count 的值
    IF uid_count > 0 THEN
        SELECT 'UID存在' AS result;
    ELSE
        -- 在这里执行插入或其他操作，表示 uid 不存在的情况
        SELECT '成功註冊' AS result;
    END IF;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateMember` (`myuid` VARCHAR(50), `mycname` VARCHAR(50), `mypwd` VARCHAR(50), `myphone` VARCHAR(20), `myemail` VARCHAR(70), `mygender` VARCHAR(20), `mystate` INT)   BEGIN
    DECLARE existing_uid VARCHAR(50);
    DECLARE existing_phone VARCHAR(20);
    DECLARE existing_email VARCHAR(70);

    -- 檢查是否存在該會員
    SELECT uid, phone, email INTO existing_uid, existing_phone, existing_email FROM UserInfo WHERE uid = myuid;

    IF existing_uid IS NOT NULL THEN
        IF existing_email <> myemail THEN
            IF EXISTS (SELECT email FROM UserInfo WHERE email = myemail) THEN
            	SELECT '更改失敗，電子信箱已綁定' AS result;
            ELSE
                IF existing_phone <> myphone THEN
                    IF EXISTS (SELECT phone FROM UserInfo WHERE phone = myphone) THEN
                    	SELECT '更改失敗，手機號碼已綁定' AS result;
                    ELSE
                        -- 更新會員資料
                        UPDATE UserInfo
                        SET cname = mycname,
                            pwd = mypwd,
                            phone = myphone,
                            email = myemail,
                            gender = mygender,
                            state = mystate
                        WHERE uid = myuid;
                        SELECT '會員更改成功' AS result;
                    END IF;
                ELSE
                    -- 更新會員資料
                    UPDATE UserInfo
                    SET cname = mycname,
                        pwd = mypwd,
                        phone = myphone,
                        email = myemail,
                        gender = mygender,
                        state = mystate
                    WHERE uid = myuid;                    
					SELECT '會員更改成功' AS result;
                END IF;
            END IF;
        ELSE
            IF existing_phone <> myphone THEN
                IF EXISTS (SELECT phone FROM UserInfo WHERE phone = myphone) THEN
                	SELECT '更改失敗，手機號碼已綁定' AS result;
                ELSE
                    -- 更新會員資料
                    UPDATE UserInfo
                    SET cname = mycname,
                        pwd = mypwd,
                        phone = myphone,
                        email = myemail,
                        gender = mygender,
                        state = mystate
                    WHERE uid = myuid;
                    SELECT '會員更改成功' AS result;
                END IF;
            ELSE
                -- 更新會員資料
                UPDATE UserInfo
                SET cname = mycname,
                    pwd = mypwd,
                    phone = myphone,
                    email = myemail,
                    gender = mygender,
                    state = mystate
                WHERE uid = myuid;                
				SELECT '會員更改成功' AS result;
            END IF;
        END IF;
    ELSE
        SELECT '更改失敗' AS result;
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- 資料表結構 `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(20) NOT NULL,
  `uid` varchar(50) NOT NULL,
  `pid` varchar(20) NOT NULL,
  `pname` varchar(20) NOT NULL,
  `count` int(11) DEFAULT NULL,
  `delivery_fee` int(11) DEFAULT NULL,
  `discounted_price` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `cart`
--

INSERT INTO `cart` (`cart_id`, `uid`, `pid`, `pname`, `count`, `delivery_fee`, `discounted_price`) VALUES
(1, 'A01', '1', '八雲殷紅魅影客製款', 2, 0, 0);

-- --------------------------------------------------------

--
-- 資料表結構 `collect`
--

CREATE TABLE `collect` (
  `uid` varchar(50) NOT NULL,
  `pid` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `collect`
--

INSERT INTO `collect` (`uid`, `pid`) VALUES
('A01', '1'),
('A01', '18'),
('amy002', '16'),
('wyuec77', '1'),
('wyuec77', '2');

-- --------------------------------------------------------

--
-- 資料表結構 `log`
--

CREATE TABLE `log` (
  `id` int(11) NOT NULL,
  `body` varchar(200) NOT NULL,
  `dd` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `log`
--

INSERT INTO `log` (`id`, `body`, `dd`) VALUES
(1, '使用者 A09 登入成功', '2023-07-18 15:14:27'),
(2, '使用者 A09 登出成功', '2023-07-18 15:16:13'),
(3, '使用者 A01 登入成功', '2023-07-31 09:48:08'),
(4, '使用者 A01 登出成功', '2023-07-31 09:49:10'),
(5, '使用者 A01 登入成功', '2023-07-31 09:50:36'),
(6, '使用者 A01 登出成功', '2023-07-31 09:51:33'),
(7, '使用者 A01 登入成功', '2023-07-31 09:51:58'),
(8, '使用者 A01 登出成功', '2023-07-31 10:08:47'),
(9, '使用者 A01 登入成功', '2023-07-31 10:16:13'),
(10, '使用者 A01 登出成功', '2023-07-31 10:17:08'),
(11, '使用者 A01 登入成功', '2023-07-31 10:17:14'),
(12, '使用者 A01 登出成功', '2023-07-31 10:20:42'),
(13, '使用者 A01 登入成功', '2023-07-31 10:21:03'),
(14, '使用者 A01 登出成功', '2023-07-31 10:22:47'),
(15, '使用者 A01 登入成功', '2023-07-31 10:28:35'),
(16, '使用者 A01 登出成功', '2023-07-31 11:21:53'),
(17, '使用者 A01 登入成功', '2023-07-31 11:22:13'),
(18, '使用者 A01 登出成功', '2023-07-31 11:26:37'),
(19, '使用者 A01 登入成功', '2023-07-31 11:27:10'),
(20, '使用者 A01 登出成功', '2023-07-31 11:48:03'),
(21, '使用者 A02 登入成功', '2023-07-31 13:47:25'),
(22, '使用者 A02 登出成功', '2023-07-31 14:27:28'),
(23, '使用者 A02 登入成功', '2023-07-31 14:31:56'),
(24, '使用者 A02 登出成功', '2023-07-31 14:48:37'),
(25, '使用者 Z01 登入成功', '2023-07-31 15:47:33'),
(26, '使用者 Z01 登出成功', '2023-07-31 15:47:54'),
(27, '已新增一筆Z09的資料', '2023-08-08 10:59:45'),
(28, '已刪除一筆Z09的資料', '2023-08-08 11:00:04'),
(29, '已新增一筆Z00的資料', '2023-08-08 13:48:30'),
(30, '已刪除一筆Z00的資料', '2023-08-08 13:50:00'),
(31, '已新增一筆Z02的資料', '2023-08-08 13:50:30'),
(32, '已刪除一筆Z02的資料', '2023-08-08 13:50:44'),
(33, '已新增一筆Z00的資料', '2023-08-08 13:59:00'),
(34, '已刪除一筆Z00的資料', '2023-08-08 13:59:30'),
(35, '已新增一筆Z00的資料', '2023-08-08 13:59:42'),
(36, '已刪除一筆Z00的資料', '2023-08-08 14:02:53'),
(37, '已新增一筆Z00的資料', '2023-08-08 14:03:04'),
(38, '已刪除一筆Z00的資料', '2023-08-08 14:04:56'),
(39, '已新增一筆Z00的資料', '2023-08-08 14:05:12'),
(40, '已刪除一筆Z00的資料', '2023-08-08 14:05:43'),
(41, '已新增一筆Z02的資料', '2023-08-08 15:21:29'),
(42, '已刪除一筆Z02的資料', '2023-08-08 15:21:44'),
(43, '已新增一筆Z02的資料', '2023-08-09 14:40:02'),
(44, '已新增一筆adsf的資料', '2023-08-09 14:44:15'),
(45, '已刪除一筆adsf的資料', '2023-08-09 14:44:28'),
(46, '已刪除一筆Z02的資料', '2023-08-09 14:44:34'),
(47, '使用者 Z01 更新了密碼', '2023-08-09 22:07:12'),
(48, '使用者 A01 登入成功', '2023-08-09 22:23:24'),
(49, '使用者 A01 登出成功', '2023-08-09 22:52:15'),
(50, '使用者 A01 登入成功', '2023-08-09 22:58:51'),
(51, '使用者 A01 登出成功', '2023-08-09 22:58:56'),
(52, '使用者 A01 登入成功', '2023-08-09 23:01:45'),
(53, '使用者 A01 登出成功', '2023-08-09 23:03:31'),
(54, '使用者 A01 登入成功', '2023-08-09 23:06:22'),
(55, '使用者 A01 登出成功', '2023-08-09 23:34:29'),
(56, '使用者 A01 登入成功', '2023-08-09 23:55:09'),
(57, '使用者 A01 登出成功', '2023-08-09 23:56:42'),
(58, '已新增一筆A01fd的資料', '2023-08-10 00:10:57'),
(59, '已新增一筆A010的資料', '2023-08-10 00:18:30'),
(60, '已刪除一筆A01fd的資料', '2023-08-10 00:31:59'),
(61, '已刪除一筆A010的資料', '2023-08-10 00:32:02'),
(62, '已新增一筆Z02的資料', '2023-08-10 00:39:18'),
(63, '已刪除一筆Z02的資料', '2023-08-10 00:41:35'),
(64, '已新增一筆Z02的資料', '2023-08-10 00:41:42'),
(65, '已刪除一筆Z02的資料', '2023-08-10 00:43:29'),
(66, '使用者 A01 登入成功', '2023-08-10 10:02:22'),
(67, '使用者 A01 登出成功', '2023-08-10 10:27:04'),
(68, '使用者 A01 登入成功', '2023-08-11 09:22:29'),
(69, '使用者 A01 登出成功', '2023-08-11 10:30:03'),
(70, '使用者 A02 登入成功', '2023-08-11 10:30:18'),
(71, '使用者 A01 登入成功', '2023-08-11 11:34:20'),
(72, '使用者 A01 更新了密碼', '2023-08-11 13:32:41'),
(73, '使用者 A01 更新了密碼', '2023-08-11 13:38:26'),
(74, '使用者 A01 更新了密碼', '2023-08-11 13:43:28'),
(75, '使用者 A01 更新了密碼', '2023-08-11 13:44:20'),
(76, '使用者 A01 更新了密碼', '2023-08-11 13:48:09'),
(77, '使用者 A01 更新了密碼', '2023-08-11 13:59:31'),
(78, '使用者 A01 更新了密碼', '2023-08-11 14:09:37'),
(79, '使用者 A01 更新了密碼', '2023-08-11 14:12:34'),
(80, '使用者 A01 更新了密碼', '2023-08-11 14:16:25'),
(81, '使用者 A01 更新了密碼', '2023-08-11 14:18:36'),
(82, '使用者 A01 更新了密碼', '2023-08-11 14:19:09'),
(83, '使用者 A01 更新了密碼', '2023-08-11 14:35:15'),
(84, '使用者 A01 更新了密碼', '2023-08-11 14:38:00'),
(85, '使用者 A01 更新了密碼', '2023-08-11 14:39:11'),
(86, '使用者 A01 更新了密碼', '2023-08-11 15:10:10'),
(87, '使用者 A01 更新了密碼', '2023-08-11 15:13:31'),
(88, '使用者 A01 更新了密碼', '2023-08-11 15:13:50'),
(89, '使用者 A01 更新了密碼', '2023-08-11 15:21:09'),
(90, '使用者 A01 更新了密碼', '2023-08-11 15:31:05'),
(91, '使用者 A01 更新了密碼', '2023-08-11 15:32:07'),
(92, '使用者 A01 更新了密碼', '2023-08-11 15:33:54'),
(93, '使用者 A01 更新了密碼', '2023-08-11 15:34:07'),
(94, '使用者 A01 更新了密碼', '2023-08-11 16:37:14'),
(95, '使用者 A01 更新了密碼', '2023-08-11 16:40:43'),
(96, '使用者 A01 更新了密碼', '2023-08-14 09:37:17'),
(97, '使用者 A01 更新了密碼', '2023-08-14 09:45:39'),
(98, '使用者 A01 登出成功', '2023-08-14 09:45:51'),
(99, '使用者 A02 更新了密碼', '2023-08-14 09:53:32'),
(100, '使用者 A02 更新了密碼', '2023-08-14 09:54:19'),
(101, '使用者 A01 登入成功', '2023-08-15 11:24:30'),
(102, '使用者 A01 登出成功', '2023-08-15 11:40:26'),
(103, '已新增一筆Z00的資料', '2023-08-15 13:02:01'),
(104, '使用者 Z01 更新了密碼', '2023-08-15 13:04:17'),
(105, '使用者 Z01 更新了密碼', '2023-08-15 13:09:40'),
(106, '使用者 Z01 更新了密碼', '2023-08-15 13:10:17'),
(107, '已刪除一筆Z00的資料', '2023-08-15 13:11:34'),
(108, '已刪除一筆Z01的資料', '2023-08-15 13:11:36'),
(109, '已新增一筆Z01的資料', '2023-08-15 13:15:49'),
(110, '已刪除一筆Z01的資料', '2023-08-15 13:18:48'),
(111, '已新增一筆Z01的資料', '2023-08-15 13:20:36'),
(112, '使用者 Z01 登入成功', '2023-08-15 13:22:06'),
(113, '使用者 A01 登入成功', '2023-08-15 14:31:37'),
(114, '使用者 A01 更新了密碼', '2023-08-15 15:52:20'),
(115, '已新增一筆Z02的資料', '2023-08-18 10:47:44'),
(116, '已刪除一筆Z02的資料', '2023-08-18 10:48:06'),
(117, '已新增一筆Z02的資料', '2023-08-18 10:48:15'),
(118, '已刪除一筆A01的資料', '2023-08-18 12:04:22'),
(119, '已新增一筆A01的資料', '2023-08-18 12:09:03'),
(120, '使用者 Z01 更新了密碼', '2023-08-18 12:14:22'),
(121, '使用者 A01 登入成功', '2023-08-18 12:20:36'),
(122, '已新增一筆david001的資料', '2023-08-21 13:16:40'),
(123, '已刪除一筆wyuec77的資料', '2023-08-21 13:39:19'),
(124, '已新增一筆chichi0707的資料', '2023-08-21 13:48:24'),
(125, '已新增一筆wyuec77的資料', '2023-08-21 14:03:26'),
(126, '使用者 amy002 登出成功', '2023-08-22 11:38:39'),
(127, '使用者 amy002 登入成功', '2023-08-22 13:03:04'),
(128, '使用者 amy002 登出成功', '2023-08-22 13:04:33'),
(129, '使用者 wyuec77 登入成功', '2023-08-22 13:04:45'),
(130, '使用者 wyuec77 登出成功', '2023-08-22 13:45:44'),
(131, '使用者 wyuec77 登入成功', '2023-08-22 13:59:37'),
(132, '使用者 wyuec77 更新了密碼', '2023-08-22 14:10:02'),
(133, '使用者 wyuec77 登出成功', '2023-08-22 14:10:28'),
(134, '使用者 wyuec77 登入成功', '2023-08-22 14:10:48'),
(135, '使用者 wyuec77 登出成功', '2023-08-22 14:12:53'),
(136, '使用者 wyuec77 登入成功', '2023-08-22 14:18:52'),
(137, '使用者 wyuec77 更新了密碼', '2023-08-22 14:19:53'),
(138, '使用者 wyuec77 更新了密碼', '2023-08-22 14:23:34'),
(139, '使用者 wyuec77 登出成功', '2023-08-22 14:23:34'),
(140, '使用者 wyuec77 登入成功', '2023-08-22 14:24:15'),
(141, '使用者 wyuec77 更新了密碼', '2023-08-22 14:24:38'),
(142, '使用者 wyuec77 登出成功', '2023-08-22 14:24:38'),
(143, '使用者 wyuec77 登入成功', '2023-08-22 14:26:29'),
(144, '使用者 wyuec77 更新了密碼', '2023-08-22 14:26:54'),
(145, '使用者 wyuec77 登出成功', '2023-08-22 14:26:54'),
(146, '使用者 wyuec77 更新了密碼', '2023-08-22 14:28:10'),
(147, '使用者 wyuec77 更新了密碼', '2023-08-22 14:29:18'),
(148, '使用者 wyuec77 登入成功', '2023-08-22 14:29:25'),
(149, '使用者 wyuec77 更新了密碼', '2023-08-22 14:30:26'),
(150, '使用者 wyuec77 登出成功', '2023-08-22 14:30:26'),
(151, '使用者 wyuec77 更新了密碼', '2023-08-22 14:49:04'),
(152, '使用者 wyuec77 登入成功', '2023-08-22 14:49:08'),
(153, '使用者 wyuec77 登出成功', '2023-08-22 14:49:55');

-- --------------------------------------------------------

--
-- 資料表結構 `orders`
--

CREATE TABLE `orders` (
  `order_id` varchar(20) NOT NULL,
  `uid` varchar(50) NOT NULL,
  `cart_id` varchar(20) DEFAULT NULL,
  `o_count` int(11) DEFAULT NULL,
  `o_total` int(11) DEFAULT NULL,
  `delivery` varchar(11) DEFAULT NULL,
  `purchaser` varchar(20) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` varchar(51) NOT NULL,
  `order_date` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `orders`
--

INSERT INTO `orders` (`order_id`, `uid`, `cart_id`, `o_count`, `o_total`, `delivery`, `purchaser`, `phone`, `address`, `order_date`) VALUES
('1691737494', 'A01', NULL, 2, 7899, '999', '王大明', '0911222333', '台中市南屯區公益路', '2023-08-13 08:53:46'),
('1691907892', 'A01', NULL, 2, 7899, NULL, '王大明', '0911222333', '台中市南屯區公益路', '2023-08-13 07:47:28'),
('1691910205', 'A02', NULL, 2, 9600, NULL, '王小明', '0933444555', '台中市南屯區大業路', '2023-08-13 16:00:15'),
('1692077248', 'A01', NULL, NULL, 7899, NULL, '王小明', '0933444555', '台中市南屯區大業路', '2023-08-15 05:28:06'),
('1692077483', 'A01', NULL, NULL, 7899, NULL, '王小明', '0933444555', '台中市南屯區大業路', '2023-08-15 05:32:13'),
('1692078140', 'A01', NULL, NULL, 7899, NULL, '王小明', '0933444555', '台中市南屯區大業路', '2023-08-15 05:43:03'),
('1692082770', 'A01', NULL, NULL, 3649, NULL, '王小明', '0933444555', '台中市南屯區大業路', '2023-08-15 07:00:24');

--
-- 觸發器 `orders`
--
DELIMITER $$
CREATE TRIGGER `after_order_insert` AFTER INSERT ON `orders` FOR EACH ROW BEGIN
    INSERT INTO order_status (order_id, status_id, status, status_timestamp) VALUES (NEW.order_id, 1,'未處理', NEW.order_date);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- 資料表結構 `order_item`
--

CREATE TABLE `order_item` (
  `orderItem_id` int(11) NOT NULL,
  `order_id` varchar(20) NOT NULL,
  `pid` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `order_item`
--

INSERT INTO `order_item` (`orderItem_id`, `order_id`, `pid`, `price`, `quantity`) VALUES
(11, '1691737494', 1, 1100, 2),
(12, '1691737494', 148, 1000, 3),
(15, '1691907892', 1, 1100, 2),
(16, '1691907892', 148, 1000, 3),
(17, '1691910205', 1, 1100, 2),
(18, '1691910205', 148, 1000, 3),
(19, '1692077248', 1, 1100, 2),
(20, '1692077248', 148, 1000, 3),
(21, '1692077483', 1, 1100, 2),
(22, '1692077483', 148, 1000, 3),
(23, '1692078140', 1, 1100, 2),
(24, '1692078140', 148, 1000, 3),
(25, '1692082770', 1, 1100, 2),
(26, '1692082770', 148, 1000, 3);

-- --------------------------------------------------------

--
-- 資料表結構 `order_status`
--

CREATE TABLE `order_status` (
  `order_id` varchar(20) NOT NULL,
  `status_id` int(11) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `status_timestamp` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `order_status`
--

INSERT INTO `order_status` (`order_id`, `status_id`, `status`, `status_timestamp`) VALUES
('1691737494', NULL, '未處理', '2023-08-15 02:23:03'),
('1691907892', NULL, '未處理', '2023-08-13 06:25:32'),
('1691910205', NULL, '未處理', '2023-08-13 12:53:48'),
('1691737494', NULL, '處理中', '2023-08-15 02:23:20'),
('1691910205', NULL, '處理中', '2023-08-13 12:53:21'),
('1691910205', NULL, '準備出貨', '2023-08-13 12:53:29'),
('1692077248', 1, '未處理', '2023-08-15 05:28:06'),
('1692077248', NULL, '處理中', '2023-08-15 05:29:36'),
('1692077483', 1, '未處理', '2023-08-15 05:32:13'),
('1692078140', 1, '未處理', '2023-08-15 05:43:03'),
('1692082770', 1, '未處理', '2023-08-15 07:00:24');

-- --------------------------------------------------------

--
-- 資料表結構 `product`
--

CREATE TABLE `product` (
  `pid` int(20) NOT NULL,
  `pname` varchar(20) NOT NULL,
  `species_id` int(11) NOT NULL,
  `pimage` varchar(101) DEFAULT NULL,
  `pimage_1` varchar(101) DEFAULT NULL,
  `pimage_2` varchar(101) DEFAULT NULL,
  `pimage_3` varchar(101) DEFAULT NULL,
  `pimage_4` varchar(101) DEFAULT NULL,
  `price` int(11) NOT NULL,
  `P_discount` float NOT NULL,
  `price_final` int(11) NOT NULL,
  `stock` int(11) NOT NULL,
  `p_status` tinyint(1) DEFAULT NULL,
  `pcontent` varchar(101) NOT NULL,
  `pcontent_spec` varchar(101) DEFAULT NULL,
  `pcontent_main` varchar(3000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `product`
--

INSERT INTO `product` (`pid`, `pname`, `species_id`, `pimage`, `pimage_1`, `pimage_2`, `pimage_3`, `pimage_4`, `price`, `P_discount`, `price_final`, `stock`, `p_status`, `pcontent`, `pcontent_spec`, `pcontent_main`) VALUES
(1, '八雲 殷紅魅影 客製款', 2, 'img/029.jpg', 'img/517223.jpg', 'img/029.jpg', 'img/517223.jpg', 'img/009.jpg', 1100, 0.9, 99, 44, 1, '一圈璀璨，繞在你的腕間。我們的手環不僅是一個時尚配飾，更是你個性的展示。每一個手環都蘊含著細膩的工藝和無限的創意，為你的手腕增添一絲光彩。無論是與其他飾品堆疊，或獨立佩戴，這款手環將為你的造型帶來獨特的', '手鍊', '<p>天然石手環：\r\n\r\n描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。\r\n製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。\r\n適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p><a href=\"https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>2天然石手環：\r\n\r\n描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。\r\n製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。\r\n適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p>圖片來源：pexels Pixabay &nbsp;</p><p>&nbsp;</p><p>3天然石手環：\r\n\r\n描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。\r\n製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。\r\n適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p><a href=\"https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"><img src=\" https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>天然石手環：\r\n\r\n描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。\r\n製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。\r\n適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p><a href=\" https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>4天然石手環：\r\n\r\n描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。\r\n製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。\r\n適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p>圖片來源：pexels Pixabay &nbsp;</p><p><a href=\"https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"><img src=\" https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>5天然石手環：\r\n\r\n描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。\r\n製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。\r\n適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p>\r\n'),
(2, '八雲  回到故鄉的那日 客製款', 2, 'img/028.jpg', 'img/LINE_ALBUM_手串IG圖_230721_1.jpg', 'img/028.jpg', 'img/LINE_ALBUM_手串IG圖_230721_1.jpg', 'img/009.jpg', 1000, 0.9, 540, 44, 1, '一圈璀璨，繞在你的腕間。我們的手環不僅是一個時尚配飾，更是你個性的展示。每一個手環都蘊含著細膩的工藝和無限的創意，為你的手腕增添一絲光彩。無論是與其他飾品堆疊，或獨立佩戴，這款手環將為你的造型帶來獨特的', '手鍊', '<p>天然石手環：\r\n\r\n描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。\r\n製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。\r\n適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p><a href=\"https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>2天然石手環：\r\n\r\n描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。\r\n製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。\r\n適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p>圖片來源：pexels Pixabay &nbsp;</p><p>&nbsp;</p><p>3天然石手環：\r\n\r\n描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。\r\n製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。\r\n適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p><a href=\"https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"><img src=\" https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>天然石手環：\r\n\r\n描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。\r\n製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。\r\n適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p><a href=\" https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>4天然石手環：\r\n\r\n描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。\r\n製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。\r\n適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p>圖片來源：pexels Pixabay &nbsp;</p><p><a href=\"https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"><img src=\" https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>5天然石手環：\r\n\r\n描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。\r\n製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。\r\n適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p>\r\n'),
(3, '八雲 傾聽海風 客製款', 2, 'img/027.jpg', 'img/517222.jpg', 'img/027.jpg', 'img/517222.jpg', 'img/009.jpg', 3500, 0.95, 99, 44, 1, '一圈璀璨，繞在你的腕間。我們的手環不僅是一個時尚配飾，更是你個性的展示。每一個手環都蘊含著細膩的工藝和無限的創意，為你的手腕增添一絲光彩。無論是與其他飾品堆疊，或獨立佩戴，這款手環將為你的造型帶來獨特的', '手環', '<p>天然石手環：\r\n\r\n描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。\r\n製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。\r\n適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p><a href=\"https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>2天然石手環：\r\n\r\n描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。\r\n製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。\r\n適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p>圖片來源：pexels Pixabay &nbsp;</p><p>&nbsp;</p><p>3天然石手環：\r\n\r\n描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。\r\n製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。\r\n適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p><a href=\"https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"><img src=\" https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>天然石手環：\r\n\r\n描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。\r\n製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。\r\n適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p><a href=\" https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>4天然石手環：\r\n\r\n描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。\r\n製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。\r\n適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p>圖片來源：pexels Pixabay &nbsp;</p><p><a href=\"https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"><img src=\" https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>5天然石手環：\r\n\r\n描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。\r\n製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。\r\n適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p>\r\n'),
(4, '啖天 永不消散的焰火', 0, 'img/016.jpg', 'img/016.jpg', 'img/016.jpg', 'img/', 'img/', 1999, 0.9, 0, 0, NULL, '一圈璀璨，繞在你的腕間。我們的手環不僅是一個時尚配飾，更是你個性的展示。每一個手環都蘊含著細膩的工藝和無限的創意，為你的手腕增添一絲光彩。無論是與其他飾品堆疊，或獨立佩戴，這款手環將為你的造型帶來獨特的', '手鍊', '<p>天然石手環：\r\n\r\n描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。\r\n製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。\r\n適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p><a href=\"https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>2天然石手環：\r\n\r\n描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。\r\n製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。\r\n適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p>圖片來源：pexels Pixabay &nbsp;</p><p>&nbsp;</p><p>3天然石手環：\r\n\r\n描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。\r\n製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。\r\n適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p><a href=\"https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"><img src=\" https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>天然石手環：\r\n\r\n描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。\r\n製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。\r\n適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p><a href=\" https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>4天然石手環：\r\n\r\n描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。\r\n製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。\r\n適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p>圖片來源：pexels Pixabay &nbsp;</p><p><a href=\"https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"><img src=\" https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>5天然石手環：\r\n\r\n描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。\r\n製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。\r\n適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p>\r\n'),
(5, '布儡 偶像實習 客製款', 2, 'img/025.jpg', 'img/016.jpg', 'img/025.jpg', 'img/016.jpg', 'img/016.jpg', 1599, 0.9, 90, 33, 1, '一圈璀璨，繞在你的腕間。我們的手環不僅是一個時尚配飾，更是你個性的展示。每一個手環都蘊含著細膩的工藝和無限的創意，為你的手腕增添一絲光彩。無論是與其他飾品堆疊，或獨立佩戴，這款手環將為你的造型帶來獨特的', '手作珍貴礦物手環，呈現輕盈細緻之美。', '<p>天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p><a href=\"https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>2天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p>圖片來源：pexels Pixabay &nbsp;</p><p>&nbsp;</p><p>3天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p><a href=\"https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"><img src=\" https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p><a href=\" https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>4天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p>圖片來源：pexels Pixabay &nbsp;</p><p><a href=\"https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"><img src=\" https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>5天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p>'),
(6, '崑西 追逐悠遠之約', 0, 'img/017.jpg', 'img/016.jpg', 'img/016.jpg', 'img/', 'img/', 1550, 0.95, 0, 0, NULL, '一圈璀璨，繞在你的腕間。我們的手環不僅是一個時尚配飾，更是你個性的展示。每一個手環都蘊含著細膩的工藝和無限的創意，為你的手腕增添一絲光彩。無論是與其他飾品堆疊，或獨立佩戴，這款手環將為你的造型帶來獨特的', '手鍊', '<p>天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p><a href=\"https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>2天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p>圖片來源：pexels Pixabay &nbsp;</p><p>&nbsp;</p><p>3天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p><a href=\"https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"><img src=\" https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p><a href=\" https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>4天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p>圖片來源：pexels Pixabay &nbsp;</p><p><a href=\"https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"><img src=\" https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>5天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p>'),
(7, '布儡 魔人偶 客製款', 0, 'img/018.jpg', 'img/016.jpg', 'img/016.jpg', 'img/', 'img/', 1500, 0.95, 0, 0, NULL, '一圈璀璨，繞在你的腕間。我們的手環不僅是一個時尚配飾，更是你個性的展示。每一個手環都蘊含著細膩的工藝和無限的創意，為你的手腕增添一絲光彩。無論是與其他飾品堆疊，或獨立佩戴，這款手環將為你的造型帶來獨特的', '手鍊', '<p>天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p><a href=\"https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>2天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p>圖片來源：pexels Pixabay &nbsp;</p><p>&nbsp;</p><p>3天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p><a href=\"https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"><img src=\" https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p><a href=\" https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>4天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p>圖片來源：pexels Pixabay &nbsp;</p><p><a href=\"https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"><img src=\" https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>5天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p>'),
(26, '艾德蒙特 白色戀人', 0, 'img/020.jpg', 'img/016.jpg', 'img/016.jpg', 'img/', 'img/', 1000, 0.9, 0, 0, NULL, '一圈璀璨，繞在你的腕間。我們的手環不僅是一個時尚配飾，更是你個性的展示。每一個手環都蘊含著細膩的工藝和無限的創意，為你的手腕增添一絲光彩。無論是與其他飾品堆疊，或獨立佩戴，這款手環將為你的造型帶來獨特的', '手鍊', '<p>天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p><a href=\"https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>2天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p>圖片來源：pexels Pixabay &nbsp;</p><p>&nbsp;</p><p>3天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p><a href=\"https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"><img src=\" https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p><a href=\" https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>4天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p>圖片來源：pexels Pixabay &nbsp;</p><p><a href=\"https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"><img src=\" https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>5天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p>'),
(27, '布儡 戰鬥人偶與回憶', 0, 'img/024.jpg', 'img/024.jpg', 'img/016.jpg', 'img/', 'img/', 1300, 0.95, 0, 0, NULL, '一圈璀璨，繞在你的腕間。我們的手環不僅是一個時尚配飾，更是你個性的展示。每一個手環都蘊含著細膩的工藝和無限的創意，為你的手腕增添一絲光彩。無論是與其他飾品堆疊，或獨立佩戴，這款手環將為你的造型帶來獨特的', '手鍊', '<p>天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p><a href=\"https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>2天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p>圖片來源：pexels Pixabay &nbsp;</p><p>&nbsp;</p><p>3天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p><a href=\"https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"><img src=\" https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p><a href=\" https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>4天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p>圖片來源：pexels Pixabay &nbsp;</p><p><a href=\"https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"><img src=\" https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>5天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p>'),
(28, '玖夜 穿梭盤月之煦', 0, 'img/023.jpg', 'img/024.jpg', 'img/016.jpg', 'img/', 'img/', 999, 0.95, 0, 0, NULL, '一圈璀璨，繞在你的腕間。我們的手環不僅是一個時尚配飾，更是你個性的展示。每一個手環都蘊含著細膩的工藝和無限的創意，為你的手腕增添一絲光彩。無論是與其他飾品堆疊，或獨立佩戴，這款手環將為你的造型帶來獨特的', '手鍊', '<p>天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p><a href=\"https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>2天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p>圖片來源：pexels Pixabay &nbsp;</p><p>&nbsp;</p><p>3天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p><a href=\"https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"><img src=\" https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p><a href=\" https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>4天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p>圖片來源：pexels Pixabay &nbsp;</p><p><a href=\"https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"><img src=\" https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>5天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p>'),
(29, '艾德蒙特 初露芬芳的甜味', 2, 'img/022.jpg', 'img/016.jpg', 'img/016.jpg', 'img/016.jpg', 'img/016.jpg', 1999, 0.9, 90, 33, 1, '一圈璀璨，繞在你的腕間。我們的手環不僅是一個時尚配飾，更是你個性的展示。每一個手環都蘊含著細膩的工藝和無限的創意，為你的手腕增添一絲光彩。無論是與其他飾品堆疊，或獨立佩戴，這款手環將為你的造型帶來獨特的', '手作珍貴礦物手環，呈現輕盈細緻之美。', '<h2>7-11開賣全新「DIONE漸層愛心冰棒」</h2><p>7-11又有新冰品了，這回是美美的愛心冰棒！7-11趁著七夕前夕推出這款浪漫的立陶宛「DIONE漸層愛心冰棒」，特別採用立陶宛當地自然放牧的草飼牛牛乳製作，高品質原料做出頂級冰淇淋，再以比利時白巧克力包裹，超美的漸層愛心冰棒外層的大理石漸層花紋每一支都不一樣，直接把冰棒變成藝術品！</p><p><a href=\"https://www.msn.com/zh-tw/lifestyle/other/7-11%E9%96%8B%E8%B3%A3-%E6%84%9B%E5%BF%83%E5%86%B0%E6%A3%92-%E6%BC%B8%E5%B1%A4%E6%84%9B%E5%BF%83%E5%B7%A7%E5%85%8B%E5%8A%9B%E5%8C%85%E9%A6%99%E8%8D%89%E5%86%B0%E6%B7%87%E6%B7%8B-%E5%86%8D%E6%8E%A8%E7%84%A6%E7%B3%96%E7%88%86%E7%B1%B3%E8%8A%B1%E7%94%9C%E7%AD%9279%E6%8A%98%E5%84%AA%E6%83%A0%E9%96%8B%E5%90%83/ar-AA1eZpEo?ocid=msedgntp&amp;cvid=a66c48ab1054470e922525e2ea7fbcd7&amp;ei=5&amp;fullscreen=true#image=3\"><img src=\"https://img-s-msn-com.akamaized.net/tenant/amp/entityid/AA1eZpE9.img?w=768&amp;h=556&amp;m=6\" alt=\"7-11開賣「愛心冰棒」！漸層愛心巧克力包香草冰淇淋，再推焦糖爆米花甜筒79折優惠開吃\"></a></p><p>7-11開賣「愛心冰棒」！漸層愛心巧克力包香草冰淇淋，再推焦糖爆米花甜筒79折優惠開吃© 由 BEAUTY美人圈 提供</p><p>圖片來源：SUNFRIEND MOUTH&nbsp;</p><h2>7-11同步開賣KAR KAR焦糖爆米花甜筒</h2><p>&nbsp;</p><p>除了「DIONE漸層愛心冰棒」全新開賣之外，7-11同步開賣「KAR KAR焦糖爆米花甜筒」！由DIONE子品牌 KAR KAR推出的「焦糖爆米花風味甜筒」，同樣以高規格原料打造，冰淇淋上方的爆米花吃起來依舊保有鬆脆口感，綿密滑順的冰淇淋包覆濃厚的焦糖及爆米花，表面再沾上巧克力漿，搭配帶有一些鹹味的餅乾甜筒，多層次的美味真的讓人一吃就愛上！</p><p><a href=\"https://www.msn.com/zh-tw/lifestyle/other/7-11%E9%96%8B%E8%B3%A3-%E6%84%9B%E5%BF%83%E5%86%B0%E6%A3%92-%E6%BC%B8%E5%B1%A4%E6%84%9B%E5%BF%83%E5%B7%A7%E5%85%8B%E5%8A%9B%E5%8C%85%E9%A6%99%E8%8D%89%E5%86%B0%E6%B7%87%E6%B7%8B-%E5%86%8D%E6%8E%A8%E7%84%A6%E7%B3%96%E7%88%86%E7%B1%B3%E8%8A%B1%E7%94%9C%E7%AD%9279%E6%8A%98%E5%84%AA%E6%83%A0%E9%96%8B%E5%90%83/ar-AA1eZpEo?ocid=msedgntp&amp;cvid=a66c48ab1054470e922525e2ea7fbcd7&amp;ei=5&amp;fullscreen=true#image=4\"><img src=\"https://img-s-msn-com.akamaized.net/tenant/amp/entityid/AA1eZu1J.img?w=768&amp;h=497&amp;m=6\" alt=\"7-11開賣「愛心冰棒」！漸層愛心巧克力包香草冰淇淋，再推焦糖爆米花甜筒79折優惠開吃\"></a></p><p>7-11開賣「愛心冰棒」！漸層愛心巧克力包香草冰淇淋，再推焦糖爆米花甜筒79折優惠開吃© 由 BEAUTY美人圈 提供</p><p>圖片來源：SUNFRIEND MOUTH&nbsp;</p><h2>冰品優惠！7-11推新品優惠79折開吃！</h2><p>以上二款來自立陶宛的頂級冰品「DIONE漸層愛心冰棒」、「KAR KAR焦糖爆米花甜筒」即日起都在7-11開賣，8月9日起至9月5日還可以享有嚐鮮優惠價79折，喜愛吃冰品的人可別錯過囉！</p><p><a href=\"https://www.msn.com/zh-tw/lifestyle/other/7-11%E9%96%8B%E8%B3%A3-%E6%84%9B%E5%BF%83%E5%86%B0%E6%A3%92-%E6%BC%B8%E5%B1%A4%E6%84%9B%E5%BF%83%E5%B7%A7%E5%85%8B%E5%8A%9B%E5%8C%85%E9%A6%99%E8%8D%89%E5%86%B0%E6%B7%87%E6%B7%8B-%E5%86%8D%E6%8E%A8%E7%84%A6%E7%B3%96%E7%88%86%E7%B1%B3%E8%8A%B1%E7%94%9C%E7%AD%9279%E6%8A%98%E5%84%AA%E6%83%A0%E9%96%8B%E5%90%83/ar-AA1eZpEo?ocid=msedgntp&amp;cvid=a66c48ab1054470e922525e2ea7fbcd7&amp;ei=5&amp;fullscreen=true#image=5\"><img src=\"https://img-s-msn-com.akamaized.net/tenant/amp/entityid/AA1eZn9D.img?w=768&amp;h=497&amp;m=6\" alt=\"7-11開賣「愛心冰棒」！漸層愛心巧克力包香草冰淇淋，再推焦糖爆米花甜筒79折優惠開吃\"></a></p><p>7-11開賣「愛心冰棒」！漸層愛心巧克力包香草冰淇淋，再推焦糖爆米花甜筒79折優惠開吃© 由 BEAUTY美人圈 提供</p><p>圖片來源：SUNFRIEND MOUTH&nbsp;</p><h3>7-11新品「DIONE漸層愛心冰棒」、「KAR KAR焦糖爆米花甜筒」販售資訊</h3><p>開賣日：即日起陸續開賣</p><p>品項/售價：DIONE漸層愛心冰棒139元/件、KAR KAR焦糖爆米花甜筒109元/件</p><p>哪裡買：全台7-11門市</p><p>優惠資訊：嚐鮮優惠價79折</p>'),
(30, '布儡 偶像實習 客製款', 2, 'img/025.jpg', 'img/016.jpg', 'img/025.jpg', 'img/016.jpg', 'img/016.jpg', 1599, 0.9, 90, 33, 1, '一圈璀璨，繞在你的腕間。我們的手環不僅是一個時尚配飾，更是你個性的展示。每一個手環都蘊含著細膩的工藝和無限的創意，為你的手腕增添一絲光彩。無論是與其他飾品堆疊，或獨立佩戴，這款手環將為你的造型帶來獨特的', '手作珍貴礦物手環，呈現輕盈細緻之美。', '<p>天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p><a href=\"https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>2天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p>圖片來源：pexels Pixabay &nbsp;</p><p>&nbsp;</p><p>3天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p><a href=\"https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"><img src=\" https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p><a href=\" https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>4天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p>圖片來源：pexels Pixabay &nbsp;</p><p><a href=\"https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"><img src=\" https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>5天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p>'),
(32, '八雲 傾聽海風 客製款', 2, 'img/027.jpg', 'img/517222.jpg', 'img/010.jpg', 'img/010.jpg', 'img/009.jpg', 3500, 0.95, 99, 44, 1, '一圈璀璨，繞在你的腕間。我們的手環不僅是一個時尚配飾，更是你個性的展示。每一個手環都蘊含著細膩的工藝和無限的創意，為你的手腕增添一絲光彩。無論是與其他飾品堆疊，或獨立佩戴，這款手環將為你的造型帶來獨特的', '手鍊', '<p>天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p><a href=\"https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>2天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p>圖片來源：pexels Pixabay &nbsp;</p><p>&nbsp;</p><p>3天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p><a href=\"https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"><img src=\" https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p><a href=\" https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>4天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p>圖片來源：pexels Pixabay &nbsp;</p><p><a href=\"https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"><img src=\" https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>5天然石手環：  描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。 製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。 適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p>'),
(33, '八雲 殷紅魅影 客製款', 2, 'img/029.jpg', 'img/517223.jpg', 'img/029.jpg', 'img/517223.jpg', 'img/009.jpg', 1100, 0.9, 99, 44, 1, '一圈璀璨，繞在你的腕間。我們的手環不僅是一個時尚配飾，更是你個性的展示。每一個手環都蘊含著細膩的工藝和無限的創意，為你的手腕增添一絲光彩。無論是與其他飾品堆疊，或獨立佩戴，這款手環將為你的造型帶來獨特的', '手鍊', '<p>天然石手環：\n\n描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。\n製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。\n適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p><a href=\"https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>2天然石手環：\n\n描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。\n製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。\n適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p>圖片來源：pexels Pixabay &nbsp;</p><p>&nbsp;</p><p>3天然石手環：\n\n描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。\n製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。\n適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p><a href=\"https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"><img src=\" https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>天然石手環：\n\n描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。\n製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。\n適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p><a href=\" https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>4天然石手環：\n\n描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。\n製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。\n適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p>圖片來源：pexels Pixabay &nbsp;</p><p><a href=\"https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"><img src=\" https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>5天然石手環：\n\n描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。\n製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。\n適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p>\n'),
(148, '艾德蒙特 白色戀人', 2, 'img/020.jpg', 'img/016.jpg', 'img/020.jpg', 'img/016.jpg', 'img/020.jpg', 1000, 0.9, 150, 100, NULL, '一圈璀璨，繞在你的腕間。我們的手環不僅是一個時尚配飾，更是你個性的展示。每一個手環都蘊含著細膩的工藝和無限的創意，為你的手腕增添一絲光彩。無論是與其他飾品堆疊，或獨立佩戴，這款手環將為你的造型帶來獨特的', '手鍊', '<p>天然石手環：\r\n\r\n描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。\r\n製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。\r\n適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p><a href=\"https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>2天然石手環：\r\n\r\n描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。\r\n製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。\r\n適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p>圖片來源：pexels Pixabay &nbsp;</p><p>&nbsp;</p><p>3天然石手環：\r\n\r\n描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。\r\n製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。\r\n適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p><a href=\"https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"><img src=\" https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>天然石手環：\r\n\r\n描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。\r\n製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。\r\n適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p><a href=\" https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>4天然石手環：\r\n\r\n描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。\r\n製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。\r\n適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p><p>圖片來源：pexels Pixabay &nbsp;</p><p><a href=\"https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"><img src=\" https://images.pexels.com/photos/371285/pexels-photo-371285.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2\"></a></p><p>5天然石手環：\r\n\r\n描述：這款手工製作的手環使用天然石珠串成，每種石頭都有獨特的能量和意義。\r\n製作步驟：選擇不同顏色和種類的天然石珠，使用結繩或彈性線串起來。您可以根據每種石頭的特性選擇組合方式。\r\n適用場合：適合作為個人佩戴，或送給親朋好友，以展示自然美和對特定能量的追求。</p>\r\n');

-- --------------------------------------------------------

--
-- 資料表結構 `userinfo`
--

CREATE TABLE `userinfo` (
  `uid` varchar(50) NOT NULL,
  `gender` int(11) NOT NULL,
  `cname` varchar(50) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `email` varchar(70) DEFAULT NULL,
  `state` int(11) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `token` varchar(40) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_valid` varchar(255) DEFAULT NULL,
  `TIME` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `userinfo`
--

INSERT INTO `userinfo` (`uid`, `gender`, `cname`, `pwd`, `email`, `state`, `phone`, `address`, `token`, `reset_token`, `reset_token_valid`, `TIME`) VALUES
('amy002', 0, 'Amy', '4567', 'amy002@gmail.com', 1, '2222', '台中市一中街2號', NULL, NULL, '', '2023-08-22 13:04:33'),
('chichi0707', 0, '李大媽', 'a1234567', 'chi007@gmail.com', 1, NULL, NULL, NULL, NULL, NULL, '2023-08-21 13:48:24'),
('dada0202', 1, '達達', 'z1234567', 'dada0202@gmail.com', 0, '0912345687', NULL, NULL, NULL, NULL, '2023-07-18 18:52:52'),
('david001', 1, ' Edison', 'd1234567', ' Edison001@gmail.com', 0, '0911335689', NULL, NULL, NULL, NULL, '2023-08-21 13:28:51'),
('ming01', 1, '黎明', 'z1234567', 'liming01@gmail.com', 0, '0988999666', NULL, NULL, NULL, '', '2023-05-05 12:15:00'),
('wangdaming01', 1, '王大明', 'a1234567', 'wangdaming01@gmail.com', 0, '0912345689', '台北市中山路一段一號', '', NULL, NULL, '2021-02-18 12:20:36'),
('wyuec77', 0, '王玥晴', 'w1234567', 'wyuec77@gmail.com', 0, '0911222332', NULL, NULL, NULL, NULL, '2023-08-22 14:49:55'),
('Zhang0707', 0, '小張', 'q1234567', 'XiaoZhang0707@gmail.com', 1, '0912345682', NULL, '', NULL, '', '2022-12-12 05:33:03');

--
-- 觸發器 `userinfo`
--
DELIMITER $$
CREATE TRIGGER `logdel` AFTER DELETE ON `userinfo` FOR EACH ROW BEGIN
	INSERT into log(body) VALUES (concat('已刪除一筆',old.uid,'的資料'));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `logins` AFTER INSERT ON `userinfo` FOR EACH ROW BEGIN
	INSERT into log(body) VALUES (concat('已新增一筆',new.uid,'的資料'));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `logupdate` AFTER UPDATE ON `userinfo` FOR EACH ROW BEGIN
    IF NEW.token IS NOT NULL AND OLD.token IS NULL THEN
        INSERT INTO log (body) VALUES (CONCAT('使用者 ', NEW.uid, ' 登入成功'));
        -- 在這裡執行登入成功後的操作
    END IF;

    IF NEW.token IS NULL AND OLD.token IS NOT NULL THEN
        INSERT INTO log (body) VALUES (CONCAT('使用者 ', NEW.uid, ' 登出成功'));
        -- 在這裡執行登出成功後的操作
    END IF;

    IF NEW.pwd <> OLD.pwd THEN
        INSERT INTO log (body) VALUES (CONCAT('使用者 ', NEW.uid, ' 更新了密碼'));
        -- 在這裡執行密碼變更後的操作
    END IF;

    
END
$$
DELIMITER ;

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `pid` (`pid`);

--
-- 資料表索引 `collect`
--
ALTER TABLE `collect`
  ADD PRIMARY KEY (`uid`,`pid`);

--
-- 資料表索引 `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `uid` (`uid`);

--
-- 資料表索引 `order_item`
--
ALTER TABLE `order_item`
  ADD PRIMARY KEY (`orderItem_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `pid` (`pid`);

--
-- 資料表索引 `order_status`
--
ALTER TABLE `order_status`
  ADD UNIQUE KEY `order_id` (`order_id`,`status`);

--
-- 資料表索引 `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`pid`),
  ADD KEY `price` (`price`);

--
-- 資料表索引 `userinfo`
--
ALTER TABLE `userinfo`
  ADD PRIMARY KEY (`uid`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `log`
--
ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=154;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `order_item`
--
ALTER TABLE `order_item`
  MODIFY `orderItem_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `product`
--
ALTER TABLE `product`
  MODIFY `pid` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=149;

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `order_status`
--
ALTER TABLE `order_status`
  ADD CONSTRAINT `fk_order_status_order_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
