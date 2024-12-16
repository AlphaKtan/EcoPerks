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
        let URL = "<?=$_SESSION['URL']; ?>"; 
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
