<?php 
$first_name_frigana_value = "";
$last_name_frigana_value = "";
$phonenumber_value = "";
$email_value = "";
$userName_value = "";
$errorMessage = "";
// フォームが送信された場合の処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name_frigana_value = $_POST["first_name_furigana"] ?? '';
    $last_name_frigana_value = $_POST["last_name_furigana"] ?? '';
    $phonenumber_value = $_POST["phonenumber"] ?? '';
    $email_value = $_POST["email"] ?? '';
    $userName_value = $_POST["userName"] ?? '';

    session_start();

    try {
        require_once('db_local.php'); // データベース接続ファイル

        $mysqli = new mysqli($servername, $dbUsername, $password, $dbname);

        if ($mysqli->connect_error) {
            die("データベース接続エラー: " . $mysqli->connect_error);
        }

        // フォームからのデータを取得
        $providedUsername = $_POST["userName"] ?? ''; // ユーザー名
        $providedPassword = $_POST["password"] ?? ''; // パスワード
        $providedEmail = $_POST["email"] ?? '';  // メールアドレス
        $first_name_furigana = $_POST["first_name_furigana"] ?? ''; // 姓フリガナ
        $last_name_furigana = $_POST["last_name_furigana"] ?? ''; // 名前フリガナ
        $phone_number = $_POST["phonenumber"] ?? '';  // 電話番号

        // パスワードの要件チェック
        if (strlen($providedPassword) < 8 ||
            !preg_match("#[0-9]+#", $providedPassword) ||
            !preg_match("#[a-z]+#", $providedPassword) ||
            !preg_match("#[A-Z]+#", $providedPassword)) {
            throw new Exception("パスワードは英数字を含む8桁以上で、大文字・小文字をそれぞれ1文字以上含めて設定してください。");
        }

        // パスワードのハッシュ化
        $hashedPassword = hash("sha256", $providedPassword);

        // トランザクションを開始
        $mysqli->begin_transaction();

        // 電話番号の重複チェック
        $stmtCheckPhone = $mysqli->prepare("SELECT COUNT(*) FROM users_kokyaku WHERE phone_number = ?");
        $stmtCheckPhone->bind_param("s", $phone_number);
        $stmtCheckPhone->execute();
        $stmtCheckPhone->bind_result($phoneCount);
        $stmtCheckPhone->fetch();
        $stmtCheckPhone->close();

        if ($phoneCount > 0) {
            throw new Exception("ご入力いただきました電話番号が既に登録されています。");
        }

        // メールアドレスの重複チェック
        $stmtCheckEmail = $mysqli->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmtCheckEmail->bind_param("s", $providedEmail);
        $stmtCheckEmail->execute();
        $stmtCheckEmail->bind_result($emailCount);
        $stmtCheckEmail->fetch();
        $stmtCheckEmail->close();

        if ($emailCount > 0) {
            throw new Exception("ご入力いただきましたメールアドレスが既に登録されています。");
        }

        // ユーザー名の重複チェック
        $stmtCheckUsername = $mysqli->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmtCheckUsername->bind_param("s", $providedUsername);
        $stmtCheckUsername->execute();
        $stmtCheckUsername->bind_result($usernameCount);
        $stmtCheckUsername->fetch();
        $stmtCheckUsername->close();

        if ($usernameCount > 0) {
            throw new Exception("ご入力いただきましたユーザー名が既に登録されています。");
        }

        // ユーザーテーブルへの挿入
        $stmtUser = $mysqli->prepare("INSERT INTO users (username, providedPassword, email) VALUES (?, ?, ?)");
        $stmtUser->bind_param("sss", $providedUsername, $hashedPassword, $providedEmail);
        if (!$stmtUser->execute()) {
            throw new Exception("ユーザーテーブルへの挿入に失敗しました: " . $stmtUser->error);
        }

        $user_id = $stmtUser->insert_id;
        $stmtUser->close();
        $_SESSION['user_id'] = $user_id;

        // 顧客テーブルへの挿入
        $stmtCustomer = $mysqli->prepare("INSERT INTO users_kokyaku (user_id, first_name_furigana, last_name_furigana, phone_number) VALUES (?, ?, ?, ?)");
        $stmtCustomer->bind_param("isss", $user_id, $first_name_furigana, $last_name_furigana, $phone_number);
        if (!$stmtCustomer->execute()) {
            throw new Exception("顧客テーブルへの挿入に失敗しました: " . $stmtCustomer->error);
        }

        $stmtCustomer->close();

        // トランザクションをコミット
        $mysqli->commit();
        
        header("Location: ../login_page.php");
        exit();
    } catch (Exception $e) {
        $mysqli->rollback();
        $errorMessage = "<p style='color:red;'>" . $e->getMessage() . "</p>";
    }
    $mysqli->close();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/formStyle.css">
    <title>アカウント登録</title>
</head>
<body>
    <header>
        <div class="flexBox">
            <div class="menu">
                <button class="menu_button" type="button">管理者?</button>
            </div>
            <div class="logo">
                <img src="img/logo.jpg" alt="" class="logo2">
            </div>
            <div class="icon"></div>
        </div>
    </header>

    <section class="login_form">
        <h1>ご登録が完了した場合ログインページへ移動します。</h1>
        <form action="" method="post" autocomplete="off">
            <div class="form-group">
                <label for="first_name_furigana">姓フリガナ first_name_furigana </label>
                <input type="text" id="first_name_furigana" name="first_name_furigana" required value="<?php echo $first_name_frigana_value; ?>">
            </div>
            <div class="form-group">
                <label for="last_name_furigana">名フリガナ last_name_furigana</label>
                <input type="text" id="last_name_furigana" name="last_name_furigana" required value="<?php echo $last_name_frigana_value; ?>">
            </div>
            <div class="form-group">
                <label for="phonenumber">電話番号 phonenumber</label>
                <input type="tel" id="phonenumber" name="phonenumber" required value="<?php echo $phonenumber_value; ?>">
            </div>
            <div class="form-group">
                <label for="email">メールアドレス email</label>
                <input type="email" name="email" required value="<?php echo $email_value; ?>">
            </div>
            <div class="form-group">
                <label for="userName">ユーザーネーム username</label>
                <input type="text" id="userName" name="userName" required value="<?php echo $userName_value; ?>">
            </div>
            <div class="form-group">
                <label for="password">パスワード password<?php if($errorMessage){
                    echo $errorMessage;
                    } 
                    ?></label>
                <input type="password" id="password" name="password" required autocomplete="new-password">
            </div>
            <div class="form-group">
                <label for="confirmPassword">再パスワード</label>
                <input type="password" id="confirmPassword" name="confirmPassword" required autocomplete="new-password">
            </div>
            <button type="submit" name="add">登録</button>
            <br><br>
            <button onclick="window.location.href='../index.html'" type="button" class="home_button">ホームに戻る</button>
        </form>
    </section>
</body>
</html>

