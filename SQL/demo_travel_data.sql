-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2024-12-07 15:17:58
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
-- テーブルの構造 `demo_travel_data`
--

CREATE TABLE `demo_travel_data` (
  `id` int(11) NOT NULL,
  `area_id` int(11) NOT NULL,
  `facility_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `romaji` varchar(255) NOT NULL,
  `kana` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `demo_travel_data`
--

INSERT INTO `demo_travel_data` (`id`, `area_id`, `facility_name`, `address`, `romaji`, `kana`) VALUES
(1, 1, '金閣寺', '京都府京都市北区金閣寺町１', 'kinkakuji', 'きんかくじ'),
(2, 1, '今宮神社', '京都府京都市北区紫野今宮町21', 'imamiyajinja', 'いまみやじんじゃ'),
(3, 1, '大徳寺', '京都府京都市北区紫野大徳寺町５３', 'daitokuji', 'だいとくじ'),
(4, 1, '正伝寺', '京都府京都市北区西賀茂北鎮守菴町72', 'shodenshi', 'しょうでんじ'),
(5, 1, '源光庵', '京都府京都市北区鷹峯北鷹峯町４７', 'genkouan', 'げんこうあん'),
(6, 1, '上賀茂神社', '京都府京都市北区上賀茂本山３３９', 'kamigamojinja', 'かみがもじんじゃ'),
(7, 2, '大田神社', '京都府京都市北区上賀茂本山３４０', 'otajinja', 'おおたじんじゃ'),
(8, 2, '圓通寺', '京都府京都市左京区岩倉幡枝町３８９', 'entoji', 'えんとうじ'),
(9, 2, '下鴨神社', '京都府京都市左京区下鴨泉川町５９', 'shimogamojinja', 'しもがもじんじゃ'),
(10, 2, '妙円寺（松ヶ崎大黒天）', '京都府京都市左京区松ケ崎東町', 'myoenji', 'みょうえんじ'),
(11, 3, '大覚寺', '京都府京都市右京区嵯峨大沢町４', 'daikakuji', 'だいかくじ'),
(12, 3, '嵯峨野竹林の小径', '京都府京都市右京区嵯峨野々宮町', 'saganotakebuko', 'さがのたけふけのこみち'),
(13, 3, '愛宕念仏寺', '京都府京都市右京区嵯峨鳥居本深谷町２−５', 'atagonembutsuji', 'あたごねんぶつじ'),
(14, 3, '渡月橋', '京都府京都市右京区嵯峨天龍寺芒ノ馬場町１−５', 'togetsukyo', 'とげつきょう'),
(15, 4, '仁和寺', '京都府京都市右京区御室大内３３', 'ninnaji', 'にんわじ'),
(16, 4, '太秦中筋町七地蔵尊', '京都府京都市右京区太秦中筋町７ 嵯峨スチューデントハウス', 'uzumasa', 'うずまさ'),
(17, 5, '晴明神社', '京都府京都市上京区晴明町８０６', 'seimei', 'せいめいじんじゃ'),
(18, 5, '元離宮二条城', '京都府京都市中京区二条城町５４１', 'nijojo', 'にじょうじょう'),
(19, 5, '車折神社', '京都府京都市右京区嵯峨朝日町２３', 'kurumazaka', 'くるまざかじんじゃ'),
(20, 5, '白峯神宮', '京都府京都市上京区飛鳥井町２６１', 'shiraminejingu', 'しらみねじんぐう'),
(21, 6, '平安神宮', '京都府京都市左京区岡崎西天王町９７', 'heianjingu', 'へいあんじんぐう'),
(22, 6, '護王神社', '京都府京都市上京区烏丸通下長者町下ル桜鶴圓町３８５', 'gojingu', 'ごじんぐう'),
(23, 7, '東山慈照寺', '京都府京都市左京区銀閣寺町２', 'tozanjishoji', 'とうざんじしょうじ'),
(24, 7, '大豊神社', '京都府京都市左京区鹿ケ谷宮ノ前町１', 'otoyo', 'おおとよじんじゃ'),
(25, 7, '南禅寺', '京都府京都市左京区南禅寺福地町８６', 'nanzenji', 'なんぜんじ'),
(26, 7, '法然院', '京都府京都市左京区鹿ケ谷御所ノ段町３０番地', 'honenin', 'ほうねんいん'),
(27, 8, '桂離宮', '京都府京都市西京区桂御園', 'katsura rikyu', 'かつらりきゅう'),
(28, 8, '梅宮大社', '京都府京都市右京区梅津フケノ川町３０', 'umemiya taisha', 'うめみやたいしゃ'),
(29, 9, '壬生寺', '京都府京都市中京区壬生梛ノ宮町３１', 'mibuji', 'みぶじ'),
(30, 10, '八坂神社', '京都府京都市東山区祇園町北側６２５', 'yasakajinja', 'やさかじんじゃ'),
(31, 10, '建仁寺', '京都府京都市東山区 高台寺下河原町５２６', 'kodai-ji', 'こうだいじ'),
(32, 10, '産寧坂（三年坂）', '京都府京都市東山区清水２丁目２１１', 'sanneizaka', 'さんねいざか'),
(33, 10, '清水寺', '京都府京都市東山区清水１丁目２９４', 'kiyomizudera', 'きよみずでら'),
(34, 10, 'ニデック京都タワー', '京都府京都市下京区烏丸通七条下る東塩小路町７２１−１', 'nidec kyoto tower', 'にでっくきょうとたわー'),
(35, 10, '総本山 智積院', '京都府京都市東山区東瓦町964番地', 'chisekiin', 'ちせきいん'),
(36, 10, '瀧尾神社', '京都府京都市東山区本町１１丁目７１８', 'takiojinja', 'たきおじんじゃ'),
(37, 11, '本圀寺', '京都府京都市山科区御陵大岩６', 'honkokuji', 'ほんこくじ'),
(38, 11, '毘沙門堂', '京都府京都市山科区安朱稲荷山町１８', 'bishamondo', 'びしゃもんどう'),
(39, 11, '元慶寺', '京都府京都市山科区北花山河原町１３', 'genkyoji', 'げんきょうじ'),
(40, 11, '疏水公園', '京都府京都市山科区四ノ宮泉水町２９−６', 'sosuikoen', 'そすいこうえん'),
(41, 11, '東山浄苑 東本願寺', '京都府京都市山科区上花山旭山町８−１', 'higashiyamajouen', 'ひがしやまじょうえん'),
(42, 12, '正法寺', '京都府京都市西京区大原野南春日町1102', 'shouhouji', 'しょうほうじ'),
(43, 12, '大原野神社', '京都府京都市西京区大原野南春日町1152', 'ooharanojinja', 'おおはらのじんじゃ'),
(44, 12, '千弥農園', '京都府京都市西京区大枝西長町1-60', 'senyanouen', 'せんやのうえん'),
(45, 12, '京都竹の郷温泉 万葉の湯', '京都府京都市西京区大原野東境谷町2-4', 'kyoutotakenosatounsen manyounoyu', 'きょうとたけのさとおんせん まんようのゆ'),
(46, 13, '仁左衛門の湯', '京都府京都市西京区樫原盆山5', 'nizaemonoyu', 'にざえもんのゆ'),
(47, 13, '京都市洛西竹林公園', '京都府京都市西京区大枝北福西町2-300-3', 'kyoutoshirakushitakurinkouen', 'きょうとしらくしちくりんこうえん'),
(48, 15, '伏見稲荷大社', '京都府京都市伏見区深草藪之内町６８', 'fushimiinari taisha', 'ふしみいなりたいしゃ'),
(49, 15, '勝林寺', '京都府京都市東山区本町１５丁目７９５', 'shourinji', 'しょうりんじ'),
(50, 15, '泉涌寺', '京都府京都市東山区泉涌寺山内町２７', 'senyuuji', 'せんゆうじ'),
(51, 16, '大石神社', '京都府京都市山科区西野山桜ノ馬場町１１６', 'ooishijinja', 'おおいしじんじゃ'),
(52, 17, '十輪寺', '京都府京都市西京区大原野小塩町４８１', 'juurinji', 'じゅうりんじ'),
(53, 17, '光明寺', ' 京都府長岡京市粟生西条ノ内26-1', 'koumyouji', 'こうみょうじ'),
(54, 18, '向日神社', ' 京都府向日市向日町北山６５', 'mukoujinja', 'むこうじんじゃ'),
(55, 18, '乙訓寺', '京都府長岡京市今里３丁目１４−７', 'otokonji', 'おとこにんじ'),
(56, 20, '月桂冠大倉記念館', '京都府京都市伏見区南浜町２４７', 'gekkeikan okura kinenkan', 'げっけいかんおおくらきねんかん'),
(57, 20, '伏見桃山城', '京都府京都市伏見区桃山町大蔵４５', 'fushimimomoyamajou', 'ふしみももやまじょう'),
(58, 20, '御香宮神社', '京都府京都市伏見区御香宮門前町１７４', 'goukoumiya jinja', 'ごこうみやじんじゃ'),
(59, 21, '大岩神社', '京都府京都市伏見区深草向ケ原町８９−２', 'oiwajinja', 'おおいわじんじゃ'),
(60, 21, '醍醐寺', ' 京都府京都市伏見区醍醐東大路町２２', 'daigoji', 'だいごじ'),
(61, 21, '一言寺（金剛王院）', '京都府京都市伏見区醍醐柏森町２−３', 'hitokotoji kongououin', 'ひとことじ こんごうおういん'),
(62, 21, '法界寺', '京都府京都市伏見区日野西大道町１９', 'houkaiji', 'ほうかいじ'),
(63, 22, '勝竜寺城公園', '京都府長岡京市勝竜寺１２−１', 'shoryuujijoukouen', 'しょうりゅうじじょうこうえん'),
(64, 23, 'さすてな京都', '京都府京都市伏見区横大路八反田２９−２９', 'sasutena kyoto', 'さすてなきょうと'),
(65, 25, '萬福寺', '京都府宇治市五ケ庄三番割３４', 'banpukuji', 'ばんぷくじ'),
(66, 25, '三室戸寺 しだれ梅園', '京都府宇治市莵道滋賀谷２１', 'mimurotoji shidarebaien', 'みむろとじ しだればいえん');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `demo_travel_data`
--
ALTER TABLE `demo_travel_data`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `demo_travel_data`
--
ALTER TABLE `demo_travel_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
