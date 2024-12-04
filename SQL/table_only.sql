-- ここではテーブルのみの記述をしております。


---------------------------------------------------------------
-- クーポン
CREATE TABLE `coupons` (
  `coupon_id` int NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `coupon_code` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `discount` int NOT NULL,
  `expiry_date` datetime NOT NULL,
  `status` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- QRだよ
CREATE TABLE `qr_codes` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `area_id` int NOT NULL,
  `facility_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL, -- 施設名を追加
  `username` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `generated_time` datetime NOT NULL,
  `expiry_time` datetime NOT NULL,
  `used` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- アクセスログ

CREATE TABLE `access_logs` (
  `id` int NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `access_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルの構造 `cleaning_records`
--

CREATE TABLE `cleaning_records` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `username` varchar(255) NOT NULL,
  `area_id` int(11) NOT NULL,
  `facility_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL, -- 施設名を追加
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `preset`
--

CREATE TABLE `preset` (
  `id` int(11) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- テーブルの構造 `test_time_change`
--

CREATE TABLE `test_time_change` (
  `id` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `facility_name` varchar(255) NOT NULL,
  `areaid` int(11) NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- テーブルの構造 `travel_data`
--

CREATE TABLE `travel_data` (
  `id` int(11) NOT NULL,
  `area_id` int(11) NOT NULL,
  `facility_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ユーザー情報

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `providedPassword` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `imgpath` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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


-- テーブルの構造 `user_sessions`
--

CREATE TABLE `user_sessions` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `login_time` datetime NOT NULL,
  `logout_time` datetime DEFAULT NULL,
  `is_logged_in` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
