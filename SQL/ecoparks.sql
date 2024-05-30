
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



--address VARCHAR(255) NOT NULL, 
--  postal_code VARCHAR(10) NOT NULL,
