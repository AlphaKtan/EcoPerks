-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2024-12-18 07:00:37
-- サーバのバージョン： 10.4.32-MariaDB
-- PHP のバージョン: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `ecoperks`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `survey_responses`
--

CREATE TABLE `survey_responses` (
  `survey_id` int(11) NOT NULL,
  `gomi` tinyint(4) NOT NULL,
  `body` text NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `areaid` int(11) DEFAULT NULL,
  `coord` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `survey_responses`
--

INSERT INTO `survey_responses` (`survey_id`, `gomi`, `body`, `image_path`, `created_at`, `areaid`, `coord`) VALUES
(8, 2, 'ｄｇｓｆｚｃヴぁｈｆｄｇんｂｖ86120', '../images/1352344694675a4e39e32cd2.31628963.png', '2024-12-12 02:48:53', 18, ''),
(9, 1, 'ｄｇｓｆｚｃヴぁｈｆｄｇんｂｖ86120', '../images/1393892183675a4f187e05d3.14164069.png', '2024-12-12 02:48:57', 18, ''),
(10, 4, 'ｄｇｓｆｚｃヴぁｈｆｄｇんｂｖ86120', '../images/933247600675a4f2ea82004.55544586.png', '2024-12-12 02:49:19', 25, ''),
(11, 2, 'wezsxrdctfvyg54156\r\n341+4', '../images/1709066249675a4fde5bb9c4.87126838.png', '2024-12-12 02:52:15', 24, ''),
(12, 4, 'fadf', '../images/1732946465676233b4f19d20.25033385.png', '2024-12-18 02:30:14', 18, ''),
(13, 4, 'fadf', '../images/1493600699676233c1cc06e4.56042968.png', '2024-12-18 02:30:26', 18, ''),
(14, 3, 'dsafadfdafsafds', '', '2024-12-18 02:31:37', 10, ''),
(15, 3, 'dsafadfdafsafds', '', '2024-12-18 02:32:45', 10, ''),
(16, 2, 'dsafadfdafsafds', '', '2024-12-18 02:35:02', 10, ''),
(17, 2, 'dsafadfdafsafds', '', '2024-12-18 02:35:28', 10, ''),
(18, 1, 'dsafadfdafsafds', '', '2024-12-18 02:36:55', 10, ''),
(19, 4, 'あｆｄｓ', '../images/3886974056762359be07cf4.37616003.jpg', '2024-12-18 02:38:21', 14, ''),
(20, 4, 'あｆｄｓ', '../images/9433489906762374c62f385.34458878.jpg', '2024-12-18 02:45:33', 14, ''),
(21, 4, 'あｆｄｓ', '../images/1487805760676238a5271ed6.71717330.jpg', '2024-12-18 02:51:18', 1, ''),
(22, 1, 'あｆｄｓ', '../images/695983043676238bb2935e5.27946576.jpg', '2024-12-18 02:51:39', 1, ''),
(23, 4, 'あｆｄｓ', '../images/879806517676239c9f17f84.79768150.jpg', '2024-12-18 02:56:10', 6, ''),
(24, 3, 'あｆｄｓ', '../images/1433065601676239e9248a09.89611850.jpg', '2024-12-18 02:56:41', 7, ''),
(25, 1, 'あｆｄｓ', '../images/395645259676239f436cb17.71328292.jpg', '2024-12-18 02:56:52', 7, ''),
(26, 4, 'あｆｄｓ', '../images/57262381067623a1bcee282.21372220.jpg', '2024-12-18 02:57:32', 10, ''),
(27, 3, 'あｆｄｓ', '../images/20989200167623a3bef33b6.46097920.jpg', '2024-12-18 02:58:05', 10, ''),
(28, 4, 'あｆｄｓ', '../images/127771752467623a487faba0.52994021.jpg', '2024-12-18 02:58:17', 10, ''),
(29, 4, 'あｆｄｓ', '../images/145791972167623a631d82a8.26835951.jpg', '2024-12-18 02:58:43', 1, ''),
(30, 4, 'あｆｄｓ', '../images/41308960067623a8615e9e3.92909094.jpg', '2024-12-18 02:59:18', 1, ''),
(31, 3, 'あｆｄｓ', '../images/15210009967623aabae5fe6.16223308.jpg', '2024-12-18 02:59:56', 2, ''),
(32, 3, 'あｆｄｓ', '../images/115578725767623af6859a40.17519938.jpg', '2024-12-18 03:01:11', 2, ''),
(33, 4, 'あｆｄさｓふぁｄｆ', '', '2024-12-18 03:01:27', 1, ''),
(34, 4, 'ｄｆｓだｓｆｄｆｓｄｆ', '', '2024-12-18 03:03:13', 3, '');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `survey_responses`
--
ALTER TABLE `survey_responses`
  ADD PRIMARY KEY (`survey_id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `survey_responses`
--
ALTER TABLE `survey_responses`
  MODIFY `survey_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
