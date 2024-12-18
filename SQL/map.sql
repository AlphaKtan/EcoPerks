-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2024-12-18 06:47:34
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
-- テーブルの構造 `map`
--

CREATE TABLE `map` (
  `id` int(11) NOT NULL,
  `area_id` int(11) NOT NULL,
  `coord` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `map`
--

INSERT INTO `map` (`id`, `area_id`, `coord`) VALUES
(1, 1, '(9196, 4151)'),
(2, 2, '(9197, 4151)'),
(3, 3, '(9194, 4152)'),
(4, 4, '(9195, 4152)'),
(5, 5, '(9196, 4152)'),
(6, 6, '(9197, 4152)'),
(7, 7, '(9198, 4152)'),
(8, 8, '(9195, 4153)'),
(9, 9, '(9196, 4153)'),
(10, 10, '(9197, 4153)'),
(11, 11, '(9198, 4153)'),
(12, 12, '(9194, 4154)'),
(13, 13, '(9195, 4154)'),
(14, 14, '(9196, 4154)'),
(15, 15, '(9197, 4154)'),
(16, 16, '(9198, 4154)'),
(17, 17, '(9194, 4155)'),
(18, 18, '(9195, 4155)'),
(19, 19, '(9196, 4155)'),
(20, 20, '(9197, 4155)'),
(21, 21, '(9198, 4155)'),
(22, 22, '(9195, 4156)'),
(23, 23, '(9196, 4156)'),
(24, 24, '(9197, 4156)'),
(25, 25, '(9198, 4156)');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `map`
--
ALTER TABLE `map`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `map`
--
ALTER TABLE `map`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
