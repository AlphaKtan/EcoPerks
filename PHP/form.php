<?php
header('Content-Type: text/html; charset=utf-8');

$servername = "mysql305.phy.lolipop.lan";
$username = "LAA1516370";
$password = "Piitasu2024";
$dbname = "LAA1516370-piitasu";

// フォームからのデータを取得
$providedUsername = isset($_POST["userName"]) ? $_POST["userName"] : ''; //ユーザー名
$providedPassword = isset($_POST["password"]) ? $_POST["password"] : ''; //パスワード
$providedEmail = isset($_POST["email"]) ? $_POST["email"] : '';  //メールアドレス
$first_name_kanji = isset($_POST["first_name_kanji"]) ? $_POST["first_name_kanji"] : '';  //　姓漢字
$first_name_furigana = isset($_POST["first_name_furigana"]) ? $_POST["first_name_furigana"] : ''; //姓フリガナ
$last_name_kanji = isset($_POST["last_name_kanji"]) ? $_POST["last_name_kanji"] : ''; //名前漢字
$last_name_furigana = isset($_POST["last_name_furigana"]) ? $_POST["last_name_furigana"] : ''; //名前フリガナ
$address = isset($_POST["address"]) ? $_POST["address"] : '';  //住所
$postal_code = isset($_POST["postal_code"]) ? $_POST["postal_code"] : ''; //郵便番号
$phone_number = isset($_POST["phonenumber"]) ? $_POST["phonenumber"] : '';  //電話番号

// ハッシュ化 SHA-256
$hashedPassword = hash("sha256", $providedPassword);

// データベースに接続
$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("データベース接続エラー: " . $mysqli->connect_error);
}

// トランザクションを開始
$mysqli->begin_transaction();

try {
    // 電話番号の重複をチェックするクエリ
    $stmtCheckPhone = $mysqli->prepare("SELECT COUNT(*) FROM users_kokyaku WHERE phone_number = ?");
    $stmtCheckPhone->bind_param("s", $phone_number);
    $stmtCheckPhone->execute();
    $stmtCheckPhone->bind_result($phoneCount);
    $stmtCheckPhone->fetch();
    $stmtCheckPhone->close();

    if ($phoneCount > 0) {
        // 電話番号が重複している場合
        throw new Exception("大変恐縮ではありますが、ご入力いただきました電話番号が既に登録されています。");
    }

    // ユーザーテーブルへの挿入クエリ
    $stmtUser = $mysqli->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
    $stmtUser->bind_param("sss", $providedUsername, $hashedPassword, $providedEmail);

    // ユーザーテーブルに挿入
    if (!$stmtUser->execute()) {
        throw new Exception("ユーザーテーブルへの挿入に失敗しました: " . $stmtUser->error);
    }

    // ユーザーテーブルへの挿入が成功した場合
    $user_id = $stmtUser->insert_id;
    $stmtUser->close();

    // 顧客テーブルへの挿入クエリ
    $stmtCustomer = $mysqli->prepare("INSERT INTO users_kokyaku (user_id, first_name_kanji, first_name_furigana, last_name_kanji, last_name_furigana, address, postal_code, phone_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmtCustomer->bind_param("isssssss", $user_id, $first_name_kanji, $first_name_furigana, $last_name_kanji, $last_name_furigana, $address, $postal_code, $phone_number);

    // 顧客テーブルに挿入
    if (!$stmtCustomer->execute()) {
        throw new Exception("顧客テーブルへの挿入に失敗しました: " . $stmtCustomer->error);
    }

    $stmtCustomer->close();

    // トランザクションをコミット
    $mysqli->commit();

    // リダイレクト
    header("Location: ../login.html");
    exit();
} catch (Exception $e) {
    // トランザクションをロールバック
    $mysqli->rollback();

    // エラーメッセージの表示
    echo "エラー: " . $e->getMessage();
    // リダイレクト
    header("Location: ../form.html");
    exit();
}
?>
