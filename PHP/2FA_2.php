<?php
header('Content-Type: text/html; charset=utf-8');

// セッションの開始
session_start();

// セッションから認証コードを取得
$verificationCode = isset($_SESSION['verification_code']) ? $_SESSION['verification_code'] : '';
?>

<!-- 2FA_2.php -->

<!-- 二段階認証コードの入力画面の処理 -->
<html lang="jp">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="#">
        <title>2ファクタ認証</title>
    </head>
    <body>

        <h2 class="h2">2ファクタ認証</h2>
        <div class="#">
            <h2 class="#">認証コードをecoparks202404@gmail.comからご登録いただいたメールに送信しました。<br>受信箱にない場合は迷惑メールフォルダの中などをご確認ください。</h2>
            
            <?php
            // POST データの処理
            if (isset($_POST['submit'])) {
                $userEnteredCode = $_POST['verification_code'];

                // 入力されたコードとセッションから取得したコードを比較
                if ($userEnteredCode == $verificationCode) {
                    // 認証成功したとき
                    header("Location: ../map.html");
                    exit;
                } else {
                    // 認証失敗したとき
                    echo '<h2 style="color: red;">認証コードが正しくありません。</h2>';
                    echo "<h2><a href='../login.html'>ログインページよりもう一度実行してください</a></h2>";
                }
            }
            ?>
            
            <hr>
            <form method="post" action="" class="#">
                <label for="verification_code" size>6桁の認証コードを入力</label>
                <h5 class="#">↓　↓　↓</h5>
                <input type="text" id="verification_code" name="verification_code" required ><br>
                <button type="submit" name="submit" class="example">認証する</button>
            </form>
        </div>
    </body>
</html>
