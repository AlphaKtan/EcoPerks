<?php
// ログアウト処理スクリプトの例

// データベース接続情報
$servername = "mysql305.phy.lolipop.lan";
$username = "LAA1516370";
$password = "ecoperks2024";
$dbname = "LAA1516370-ecoperks";

// タイムゾーンを日本時間に設定
date_default_timezone_set('Asia/Tokyo');

$conn = new mysqli($servername, $username, $password, $dbname);

// 接続確認
if ($conn->connect_error) {
    die("接続失敗: " . $conn->connect_error);
}

// セッションの開始
session_start();
$user_id = $_SESSION['user_id'];

// 最新のセッションを取得してログアウト時刻を更新
$logout_time = date('Y-m-d H:i:s');
$is_logged_in = 0;
$sql = "UPDATE user_sessions SET logout_time = ?, is_logged_in = ? WHERE user_id = ? AND is_logged_in = 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sii", $logout_time, $is_logged_in, $user_id);
$stmt->execute();

// セッションの終了
session_destroy();

echo "ログアウト成功";

$stmt->close();
$conn->close();
?>



<!-- 以下ログインシステム改造に関わるメモ
 機能としてはログインした時に時刻を取得しそれをデータベースに追加する。
 ログアウトも同様な機能を追加予定
 これにより管理者が現在のログイン者数などを把握できるのち、
 これを応用し参加者数などのリアルタイム解析の足枷となる -->


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
    $stmt = $conn->prepare("SELECT id, providedPassword FROM users WHERE username = ?");
    $stmt->bind_param("s", $providedUsername);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $storedHashedPassword);
        $stmt->fetch();

        // SHA256でハッシュ化
        $hashedPassword = hash("sha256", $providedPassword);

        if (hash_equals($storedHashedPassword, $hashedPassword)) {
            // ログイン成功

            // 6桁の2ファクタ認証コード生成
            $verificationCode = sprintf("%06d", mt_rand(0, 999999));
            //$verificationCode = '123456';

            // 2ファクタ認証コードをセッションに保存
            $_SESSION['verification_code'] = $verificationCode;
            $_SESSION['username'] = $providedUsername;

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
                    // ログイン時間を更新する
                    $login_time = date('Y-m-d H:i:s');
                    $is_logged_in = 1;
                    $sql = "INSERT INTO user_sessions (user_id, login_time, is_logged_in) 
                            VALUES (?, ?, ?)
                            ON DUPLICATE KEY UPDATE login_time = VALUES(login_time), is_logged_in = VALUES(is_logged_in), logout_time = NULL";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("isi", $user_id, $login_time, $is_logged_in);
                    $stmt->execute();

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
?>