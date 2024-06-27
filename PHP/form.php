<!-- 顧客登録 -->
 <!-- テーブル二つまたぐ　users users_kokyaku -->
<?php
header('Content-Type: text/html; charset=utf-8');

// セッションの開始
session_start();

$servername = "mysql305.phy.lolipop.lan";
$username = "LAA1516370";
$password = "ecoperks2024";
$dbname = "LAA1516370-ecoperks";

// フォームからのデータを取得
$providedUsername = $_POST["userName"] ?? ''; //ユーザー名
$providedPassword = $_POST["password"] ?? ''; //パスワード
$providedEmail = $_POST["email"] ?? '';  //メールアドレス
$first_name_kanji = $_POST["first_name_kanji"] ?? '';  //姓漢字
$first_name_furigana = $_POST["first_name_furigana"] ?? ''; //姓フリガナ
$last_name_kanji = $_POST["last_name_kanji"] ?? ''; //名前漢字
$last_name_furigana = $_POST["last_name_furigana"] ?? ''; //名前フリガナ
$phone_number = $_POST["phonenumber"] ?? '';  //電話番号

// パスワードのハッシュ化 (SHA256)
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
    if (!$stmtCheckPhone) {
        throw new Exception("電話番号重複チェックの準備に失敗しました: " . $mysqli->error);
    }
    $stmtCheckPhone->bind_param("s", $phone_number);
    $stmtCheckPhone->execute();
    $stmtCheckPhone->bind_result($phoneCount);
    $stmtCheckPhone->fetch();
    $stmtCheckPhone->close();

    if ($phoneCount > 0) {
        // 電話番号が重複している場合
        throw new Exception("大変恐縮ではありますが、ご入力いただきました電話番号が既に登録されています。");
    }

    // メールアドレスの重複をチェックするクエリ
    $stmtCheckEmail = $mysqli->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    if (!$stmtCheckEmail) {
        throw new Exception("メールアドレス重複チェックの準備に失敗しました: " . $mysqli->error);
    }
    $stmtCheckEmail->bind_param("s", $providedEmail);
    $stmtCheckEmail->execute();
    $stmtCheckEmail->bind_result($emailCount);
    $stmtCheckEmail->fetch();
    $stmtCheckEmail->close();

    if ($emailCount > 0) {
        // メールアドレスが重複している場合
        throw new Exception("大変恐縮ではありますが、ご入力いただきましたメールアドレスが既に登録されています。");
    }

    // ユーザー名の重複をチェックするクエリ
    $stmtCheckUsername = $mysqli->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    if (!$stmtCheckUsername) {
        throw new Exception("ユーザー名重複チェックの準備に失敗しました: " . $mysqli->error);
    }
    $stmtCheckUsername->bind_param("s", $providedUsername);
    $stmtCheckUsername->execute();
    $stmtCheckUsername->bind_result($usernameCount);
    $stmtCheckUsername->fetch();
    $stmtCheckUsername->close();

    if ($usernameCount > 0) {
        // ユーザー名が重複している場合
        throw new Exception("大変恐縮ではありますが、ご入力いただきましたユーザー名が既に登録されています。");
    }

    // ユーザーテーブルへの挿入クエリ
    $stmtUser = $mysqli->prepare("INSERT INTO users (username, providedPassword, email) VALUES (?, ?, ?)");
    if (!$stmtUser) {
        throw new Exception("ユーザー情報挿入クエリの準備に失敗しました: " . $mysqli->error);
    }
    $stmtUser->bind_param("sss", $providedUsername, $hashedPassword, $providedEmail);

    // ユーザーテーブルに挿入
    if (!$stmtUser->execute()) {
        throw new Exception("ユーザーテーブルへの挿入に失敗しました: " . $stmtUser->error);
    }

    // ユーザーテーブルへの挿入が成功した場合
    $user_id = $stmtUser->insert_id;
    $stmtUser->close();

    // 顧客テーブルへの挿入クエリ
    $stmtCustomer = $mysqli->prepare("INSERT INTO users_kokyaku (user_id, first_name_kanji, first_name_furigana, last_name_kanji, last_name_furigana, phone_number) VALUES (?, ?, ?, ?, ?, ?)");
    if (!$stmtCustomer) {
        throw new Exception("顧客情報挿入クエリの準備に失敗しました: " . $mysqli->error);
    }
    $stmtCustomer->bind_param("isssss", $user_id, $first_name_kanji, $first_name_furigana, $last_name_kanji, $last_name_furigana, $phone_number);

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
}
$mysqli->close();


