<?php
session_start();
session_regenerate_id(true);

header('Content-Type: text/html; charset=utf-8');

date_default_timezone_set('Asia/Tokyo');

$servername = "mysql305.phy.lolipop.lan";
$username = "LAA1516370";
$password = "ecoperks2024";
$dbname = "LAA1516370-ecoperks";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("データベースに接続できないよ？ちゃんとみなおしてよね(-V-): " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $providedUsername = isset($_POST["providedUsername"]) ? $_POST["providedUsername"] : '';
    $providedPassword = isset($_POST["providedPassword"]) ? $_POST["providedPassword"] : '';

    $getHashedPasswordSql = "SELECT password FROM users WHERE username = '$providedUsername'";
    $getHashedPasswordResult = $conn->query($getHashedPasswordSql);

    if ($getHashedPasswordResult->num_rows > 0) {
        $userRow = $getHashedPasswordResult->fetch_assoc();
        $storedHashedPassword = $userRow['password'];
                //SHA256でハッシュ化
        $hashedPassword = hash("sha256", $providedPassword);

        if (hash_equals($storedHashedPassword, $hashedPassword)) {
            // ログイン成功

            // 6桁の2ファクタ認証コード生成
            $verificationCode = sprintf("%06d", mt_rand(0, 999999));

            // 2ファクタ認証コードをセッションに保存
            $_SESSION['verification_code'] = $verificationCode;
            $_SESSION['username'] = $providedUsername;

            // ユーザーのメールアドレスをデータベースから取得
            $getUserEmailSql = "SELECT email FROM users WHERE username = '$providedUsername'";
            $userEmailResult = $conn->query($getUserEmailSql);

            if ($userEmailResult->num_rows > 0) {
                $userEmailRow = $userEmailResult->fetch_assoc();
                $userEmail = $userEmailRow['email'];

                // ローカルでメール送信
                if (sendVerificationCodeByEmailLocal($userEmail, $verificationCode)) {
                    // メール送信が成功した場合にのみリダイレクト
                    header("Location: 2FA_2.php");
                    exit;
                } else {
                    // エラー: メール送信が失敗した場合
                    echo '<p style="color: red;">エラー: メールの送信に失敗しました。</p>';
                }
            } else {
                // エラー: メールアドレスが見つからない場合の処理
                echo '<p style="color: red;">エラー: メールアドレスが見つかりませんでした。</p>';
            }
        }
    }

    // ログイン失敗時の処理
    echo '<h2 style="color: red;">ユーザーネームとパスワードを確認してください。</h2>';
    echo "<h2><a href='../login.html'>入力されたパスワードが一致しなかったため、<br>
    お手数ではございますがもう一度ログインページよりログインしてください。</a></h2>";
    //echo '<h2 id="countdown"></h2>';
    //echo '<script src="../JS/login_countdown.js"></script>';
}

$conn->close();

// ローカルでメール送信する関数
function sendVerificationCodeByEmailLocal($userEmail, $verificationCode) {
    $to = $userEmail;
    $subject = '2ファクタ認証コード';
    $message = '２ファクタ認証コードです。第三者には絶対に教えないでください。';
    $message .= '2ファクタ認証コード: ' . $verificationCode;
    $message .= '心当たりがない場合は無視してください';
    $headers = "From: pitasuweb@gmail.com";

    // メール送信
    return mail($to, $subject, $message, $headers);
}
