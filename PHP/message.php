<?php
session_start();

// セッションにメッセージが保存されていない場合は、直接ログインページへ
if (!isset($_SESSION['login_message'])) {
    header('Location: ../login_page.php');
    exit;
}

// セッションからメッセージを取得
$message = $_SESSION['login_message'];

// メッセージを表示後に削除
unset($_SESSION['login_message']);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お知らせ</title>
    <script type="text/javascript">
        // 3秒後にリダイレクトする
        setTimeout(function() {
            window.location.href = '../login_page.php';
        }, 3000); // 3000ミリ秒 = 3秒
    </script>
</head>
<body>
    <p style="color: red;"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
    <p>3秒後にログインページに移動します...</p>
</body>
</html>


