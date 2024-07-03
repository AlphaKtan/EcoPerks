<?php
// エラーレポートを有効にする
// もし不具合が起きれば以下のコードを有効にする。
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// セッションが開始されていない場合のみセッションを開始
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}




header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set('Asia/Tokyo');

$servername = "mysql305.phy.lolipop.lan";
$username = "LAA1516370";
$password = "ecoperks2024";
$dbname = "LAA1516370-ecoperks";

// データベース接続
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("データベースに接続できないちゃんと確認して: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $providedUsername = isset($_POST["providedUsername"]) ? $_POST["providedUsername"] : '';
    $providedPassword = isset($_POST["providedPassword"]) ? $_POST["providedPassword"] : '';

    // 準備されたステートメントを使用してSQLインジェクション対策
    $stmt = $conn->prepare("SELECT providedPassword FROM users WHERE username = ?");
    $stmt->bind_param("s", $providedUsername);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($storedHashedPassword);
        $stmt->fetch();

        // SHA256でハッシュ化
        $hashedPassword = hash("sha256", $providedPassword);

        if (hash_equals($storedHashedPassword, $hashedPassword)) {
            // ログイン成功

            // 2024/6/19 katayama ここに可能であればQRコードで2段階認証スキップを追加予定

            // 6桁の2ファクタ認証コード生成
            $verificationCode = sprintf("%06d", mt_rand(0, 999999));
            //$verificationCode = '123456';
            
            // 2ファクタ認証コードをセッションに保存
            $_SESSION['verification_code'] = $verificationCode;
            $_SESSION['username'] = $providedUsername;
            //setcookie('verification_code', $verificationCode, time() + 720, '/');

            // ユーザーのメールアドレスをデータベースから取得
            $stmt = $conn->prepare("SELECT email FROM users WHERE username = ?");
            $stmt->bind_param("s", $providedUsername);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($userEmail);
                $stmt->fetch();

                // ローカルでメール送信
                if (sendVerificationCodeByEmailLocal($userEmail, $verificationCode)) {
                    // メール送信が成功した場合にのみリダイレクト
                    //echo $verificationCode;
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
    echo "<h2><a href='../login.html'>入力されたパスワードが一致しなかったため、<br>お手数ではございますがもう一度ログインページよりログインしてください。</a></h2>";
    $stmt->close();
}

$conn->close();

// ローカルでメール送信する関数
function sendVerificationCodeByEmailLocal($userEmail, $verificationCode) {
    $to = $userEmail;
    $subject = '2ファクタ認証コード';
    $message = '２ファクタ認証コードです。第三者には絶対に教えないでください。';
    $message .= '2ファクタ認証コード: ' . $verificationCode;
    $message .= '心当たりがない場合は無視してください';
    $headers = "From: ecoparks202404@gmail.com";

    // メール送信
    return mail($to, $subject, $message, $headers);
}
