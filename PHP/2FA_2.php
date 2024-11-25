<?php
// セッションの開始
session_start();
//var_dump($_SESSION);
require_once('../Model/dbModel.php');
$pdo = dbConnect();
// POST データの処理
if (isset($_POST['submit'])) {
    $userEnteredCode = $_POST['verification_code'];
    // クッキーから取ってくる
    $verificationCode = isset($_SESSION['verification_code']) ? $_SESSION['verification_code'] : '';

    // 入力されたコードとクッキーから取得したコードを比較
    if ($userEnteredCode == $verificationCode) {
        // 認証成功したとき
        // クッキーに認証成功を保存する
        //setcookie('verification_code', $verificationCode, time() + 720, '/'); // 有効期限は1時間

        header("Location: ../index.html");
        exit;
    } else {
        // 認証失敗したとき
        echo '<h2 style="color: red;">認証コードが正しくありません。</h2>';
        echo "<h2><a href='../login_page.php'>ログインページよりもう一度実行してください</a></h2>";
    }
}
function getClientIp() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

// IPアドレスの取得
$ip_address = getClientIp();

// アクセス時間の取得
$access_time = date('Y-m-d H:i:s');

// ログイン中のユーザー名の取得（必要に応じて）
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';

// データベースにアクセス情報を保存
try {
    $sql = "INSERT INTO access_logs (username, ip_address, access_time) 
            VALUES (:username, :ip_address, :access_time)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':ip_address', $ip_address);
    $stmt->bindParam(':access_time', $access_time);
    $stmt->execute();
} catch (PDOException $e) {
    echo "エラー: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/2FA_2.css">
    <title>2ファクタ認証</title>
</head>
<body>
<header>
    <div class="flexBox">
        <div class="menu"></div>
        <div class="logo">
            <img src="../img/logo.jpg" alt="" class="logo2">
        </div>
        <div class="icon"></div>
    </div>
</header>
<section class=form-a>
    <h2>2ファクタ認証</h2>
    <div class="form-b">
        <br>
        <p>認証コードをecoparks202404@gmail.comからご登録いただいたメールに送信しました</p>
        <hr>
        <br>
       
        <form method="post" action="#" class="#">
            <label for="verification_code">6桁の認証コードを入力</label>
            <input type="text" id="verification_code" class="authCode" name="verification_code" required><br>
            <button type="submit" name="submit" class="example">認証する</button>
            <p>※受信箱にない場合は迷惑メールフォルダの中などをご確認ください。</p>
        </form>
    </div>
</section>

</body>
</html>