-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2024-09-08 14:39:45
-- サーバのバージョン： 10.4.28-MariaDB
-- PHP のバージョン: 8.2.4

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
-- テーブルの構造 `cleaning_records`
--

CREATE TABLE `cleaning_records` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `area_id` int(11) NOT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `area_id` int(11) NOT NULL,
  `facility_name` varchar(255) DEFAULT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `status` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `travel_data`
--

CREATE TABLE `travel_data` (
  `id` int(11) NOT NULL,
  `area_id` int(11) NOT NULL,
  `facility_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `travel_data`
--

INSERT INTO travel_data (`id`, `area_id`, `facility_name`, `address`) VALUES
(2, 1, '金閣寺', '京都府京都市北区金閣寺町１'),
(3, 1, '今宮神社', '京都府京都市北区紫野今宮町21'),
(4, 1, '大徳寺', '京都府京都市北区紫野大徳寺町５３'),
(5, 1, '正伝寺', '京都府京都市北区西賀茂北鎮守菴町72'),
(6, 1, '源光庵', '京都府京都市北区鷹峯北鷹峯町４７'),
(7, 1, '上賀茂神社', '京都府京都市北区上賀茂本山３３９'),
(8, 2, '大田神社', '京都府京都市北区上賀茂本山３４０'),
(9, 2, '圓通寺', '京都府京都市左京区岩倉幡枝町３８９'),
(10, 2, '下鴨神社', '京都府京都市左京区下鴨泉川町５９'),
(11, 2, '妙円寺（松ヶ崎大黒天）', '京都府京都市左京区松ケ崎東町'),
(26, 3, '大覚寺', '〒616-8411 京都府京都市右京区嵯峨大沢町４'),
(27, 3, '嵯峨野竹林の小径', '〒616-8393 京都府京都市右京区嵯峨野々宮町'),
(28, 3, '愛宕念仏寺', '〒616-8439 京都府京都市右京区嵯峨鳥居本深谷町２−５'),
(29, 3, '渡月橋', '〒616-8384 京都府京都市右京区嵯峨天龍寺芒ノ馬場町１−５'),
(30, 4, '仁和寺', '〒616-8092 京都府京都市右京区御室大内３３'),
(31, 4, '太秦中筋町七地蔵尊', '〒616-8184 京都府京都市右京区太秦中筋町７ 嵯峨スチューデントハウス'),
(32, 5, '晴明神社', '〒602-8222 京都府京都市上京区晴明町８０６'),
(33, 5, '元離宮二条城', '〒604-8301 京都府京都市中京区二条城町５４１'),
(34, 5, '車折神社', '〒616-8343 京都府京都市右京区嵯峨朝日町２３'),
(35, 5, '白峯神宮', '〒602-0054 京都府京都市上京区飛鳥井町２６１'),
(36, 6, '平安神宮', '〒606-8341 京都府京都市左京区岡崎西天王町９７'),
(37, 6, '護王神社', '〒602-8011 京都府京都市上京区烏丸通下長者町下ル桜鶴圓町３８５'),
(38, 7, '東山慈照寺', '〒606-8402 京都府京都市左京区銀閣寺町２'),
(39, 7, '大豊神社', '〒606-8424 京都府京都市左京区鹿ケ谷宮ノ前町１'),
(40, 7, '南禅寺', '〒606-8435 京都府京都市左京区南禅寺福地町８６'),
(41, 7, '法然院', '〒606-8422 京都府京都市左京区鹿ケ谷御所ノ段町３０番地'),
(42, 12, '正法寺', '京都府京都市西京区大原野南春日町1102'),
(43, 12, '大原野神社', '京都府京都市西京区大原野南春日町1152'),
(44, 12, '千弥農園', '京都府京都市西京区大枝西長町1-60'),
(45, 12, '京都竹の郷温泉 万葉の湯', '京都府京都市西京区大原野東境谷町2-4'),
(46, 13, '仁左衛門の湯', '京都府京都市西京区樫原盆山5'),
(47, 13, '京都市洛西竹林公園', '京都府京都市西京区大枝北福西町2-300-3'),
(48, 15, '伏見稲荷大社', '〒612-0882 京都府京都市伏見区深草藪之内町６８'),
(49, 15, '勝林寺', '京都府京都市東山区本町１５丁目７９５'),
(50, 15, '泉涌寺', '京都府京都市東山区泉涌寺山内町２７'),
(51, 16, '大石神社', '京都府京都市山科区西野山桜ノ馬場町１１６'),
(52, 17, '十輪寺', '京都府京都市西京区大原野小塩町４８１'),
(53, 17, '光明寺', ' 京都府長岡京市粟生西条ノ内26-1'),
(54, 18, '向日神社', ' 京都府向日市向日町北山６５'),
(55, 18, '乙訓寺', '京都府長岡京市今里３丁目１４−７'),
(56, 20, '月桂冠大倉記念館', '京都府京都市伏見区南浜町２４７'),
(57, 20, '伏見桃山城', '京都府京都市伏見区桃山町大蔵４５'),
(58, 20, '御香宮神社', '京都府京都市伏見区御香宮門前町１７４'),
(59, 21, '大岩神社', '京都府京都市伏見区深草向ケ原町８９−２'),
(60, 21, '醍醐寺', ' 京都府京都市伏見区醍醐東大路町２２'),
(61, 21, '一言寺（金剛王院）', '京都府京都市伏見区醍醐柏森町２−３'),
(62, 21, '法界寺', '京都府京都市伏見区日野西大道町１９'),
(63, 22, '勝竜寺城公園', '〒617-0836 京都府長岡京市勝竜寺１２−１'),
(64, 23, 'さすてな京都', '〒612-8253 京都府京都市伏見区横大路八反田２９−２９'),
(65, 25, '萬福寺', '〒611-0011 京都府宇治市五ケ庄三番割３４'),
(66, 25, '三室戸寺 しだれ梅園', '〒611-0013 京都府宇治市莵道滋賀谷２１'),
(67, 10, '八坂神社', '〒605-0073 京都府京都市東山区祇園町北側６２５'),
(68, 8, '桂離宮', '京都府京都市西京区桂御園'),
(69, 10, '建仁寺', '〒605-0811 京都府京都市東山区大和大路通四条下る小松町５８４番地'),
(70, 10, '高台寺', '〒605-0825 京都府京都市東山区 高台寺下河原町５２６'),
(71, 8, '梅宮大社', '京都府京都市右京区梅津フケノ川町３０'),
(72, 10, '産寧坂（三年坂）', '〒605-0862 京都府京都市東山区清水２丁目２１１'),
(73, 9, '壬生寺', '京都府京都市中京区壬生梛ノ宮町３１'),
(74, 10, '清水寺', '〒605-0862 京都府京都市東山区清水１丁目２９４'),
(75, 10, 'ニデック京都タワー', '〒600-8216 京都府京都市下京区烏丸通七条下る東塩小路町７２１−１'),
(76, 10, '総本山 智積院', '〒605-0951 京都府京都市東山区東瓦町964番地'),
(77, 10, '瀧尾神社', '〒605-0981 京都府京都市東山区本町１１丁目７１８'),
(78, 11, '本圀寺', '〒607-8403 京都府京都市山科区御陵大岩６'),
(79, 11, '毘沙門堂', '〒607-8003 京都府京都市山科区安朱稲荷山町１８'),
(80, 10, '山科疏水', '〒607-8006 京都府京都市山科区安朱馬場ノ東町６０７ 8006'),
(81, 11, '元慶寺', '〒607-8476 京都府京都市山科区北花山河原町１３'),
(82, 11, '疏水公園', '〒607-8025 京都府京都市山科区四ノ宮泉水町２９−６'),
(83, 11, '東山浄苑 東本願寺', '〒607-8461 京都府京都市山科区上花山旭山町８−１');

-- --------------------------------------------------------

--
-- テーブルの構造 `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `providedPassword` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `users_kokyaku`
--

CREATE TABLE `users_kokyaku` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `first_name_kanji` varchar(255) NOT NULL,
  `first_name_furigana` varchar(255) NOT NULL,
  `last_name_kanji` varchar(255) NOT NULL,
  `last_name_furigana` varchar(255) NOT NULL,
  `phone_number` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `user_sessions`
--

CREATE TABLE `user_sessions` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `login_time` datetime NOT NULL,
  `logout_time` datetime DEFAULT NULL,
  `is_logged_in` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `yoyaku`
--

CREATE TABLE `yoyaku` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `reservation_date` date NOT NULL,
  `area_id` int(11) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `status` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `location` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `yoyaku`
--

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `cleaning_records`
--
ALTER TABLE `cleaning_records`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `facility_name` (`facility_name`);

--
-- テーブルのインデックス `travel_data`
--
ALTER TABLE `travel_data`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `facility_name` (`facility_name`);

--
-- テーブルのインデックス `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- テーブルのインデックス `users_kokyaku`
--
ALTER TABLE `users_kokyaku`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- テーブルのインデックス `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`);

--
-- テーブルのインデックス `yoyaku`
--
ALTER TABLE `yoyaku`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `cleaning_records`
--
ALTER TABLE `cleaning_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `travel_data`
--
ALTER TABLE `travel_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- テーブルの AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `users_kokyaku`
--
ALTER TABLE `users_kokyaku`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `user_sessions`
--
ALTER TABLE `user_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `yoyaku`
--
ALTER TABLE `yoyaku`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- ダンプしたテーブルの制約
--

--
-- テーブルの制約 `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`facility_name`) REFERENCES `travel_data` (`facility_name`);

--
-- テーブルの制約 `users_kokyaku`
--
ALTER TABLE `users_kokyaku`
  ADD CONSTRAINT `users_kokyaku_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- テーブルの制約 `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD CONSTRAINT `user_sessions_ibfk_1` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
