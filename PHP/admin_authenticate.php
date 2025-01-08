<?php
session_start();

require_once('../Model/dbModel.php');
// データベースに接続
$pdo = dbConnect();

$_SESSION['facility'] = null;
$_SESSION['location_id'] = null;
$_SESSION['area_id'] = null;
$_SESSION['romaji'] = null;
$_SESSION['kana'] = null;

// フォームから送信されたユーザー名とパスワードを取得します
$admin_id = $_POST['admin_id'];
$password = $_POST['password'];

// パスワードの検証
$sql = "SELECT id, area_id, facility_name, address, romaji, kana, login_id FROM travel_data WHERE login_id = :admin_id AND pass = :pass";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
$stmt->bindParam(':pass', $password, PDO::PARAM_STR);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if(!empty($result)) {
    $_SESSION['admin_id'] = $result['login_id'];
    $_SESSION['facility'] = $result['facility_name'];
    $_SESSION['location_id'] = $result['id'];
    $_SESSION['admin_area_id'] = $result['area_id'];
    $_SESSION['romaji'] = $result['romaji'];
    $_SESSION['kana'] = $result['kana'];

    echo <<<HTML
    <!DOCTYPE html>
    <html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>お知らせ</title>
        <script type="text/javascript">
            let URL = "{$_SESSION['URL']}";
            let countdown = 3; // カウントダウンの初期値

            // カウントダウンを表示する関数
            function updateCountdown() {
                const countdownElement = document.getElementById('countdown');
                countdownElement.textContent = countdown;
                if (countdown === 0) {
                    window.location.href = URL;
                } else {
                    countdown--;
                }
            }

            // 1秒ごとにカウントダウンを更新
            setInterval(updateCountdown, 1000);
        </script>
    </head>
    <body>
        <p>あと <span id="countdown">3</span> 秒で元のページに戻ります...</p>
    </body>
    </html>
    HTML;

    echo $_SESSION['facility']."でログインしました";

} else {
    $error_message = "ログイン番号もしくはパスワードが間違えています。";
    header("Location: admin_login.php?error=".urlencode($error_message));
    exit();
}
?>