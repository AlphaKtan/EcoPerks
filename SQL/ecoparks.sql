-- ログインID及びパスワード
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    providedPassword VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL
);

-- 顧客情報（機密性が高い情報：電話番号が含まれます。）
CREATE TABLE users_kokyaku (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    first_name_kanji VARCHAR(255) NOT NULL,
    first_name_furigana VARCHAR(255) NOT NULL,
    last_name_kanji VARCHAR(255) NOT NULL,
    last_name_furigana VARCHAR(255) NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
-- ユーザーセッションの記録

CREATE TABLE user_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255),
    login_time DATETIME NOT NULL,
    logout_time DATETIME,
    is_logged_in BOOLEAN NOT NULL DEFAULT TRUE,
    FOREIGN KEY (username) REFERENCES users(username)
);

CREATE TABLE travel_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    area_id INT NOT NULL, -- エリアを識別するためのカラム
    facility_name VARCHAR(255) NOT NULL,
    address VARCHAR(255) NOT NULL
);

CREATE TABLE yoyaku (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL, -- ユーザーID
    reservation_date DATE NOT NULL, -- 予約日
    start_time TIME NOT NULL, -- 予約開始時間
    end_time TIME NOT NULL, -- 予約終了時間
    status VARCHAR(20) DEFAULT 'pending', -- 予約ステータス（例: pending, confirmed, cancelled）
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- 作成日時
);
