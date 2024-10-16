<?php
session_start();
$_SESSION['user_id'] = 1;
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
            window.history.back();
        }, 3000); // 3000ミリ秒 = 3秒
    </script>
</head>
<body>
    <p>3秒後に元のページに戻ります...</p>
</body>
</html>