<!DOCTYPE html>
<!-- ログイン-->
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="CSS/loginStyle.css">
    <link rel="stylesheet" href="CSS/indexStyle.css">
    <title>ログイン</title>
</head>
<body>
  

<section class="login_form">
    <img src="img/logo_yoko.svg" class="login_img">
    <b class="b">ユーザーのログイン</b>
    <form action="php/login.php" method="post">
        <div class="form-group">
            <input type="text" name="providedUsername" placeholder="ユーザーネーム" required>
        </div>
        <div class="form-group">
            <input type="password" name="providedPassword"  id="providedPassword" placeholder="パスワード"required>
        </div>
        <button type="submit">ログイン</button>
    </form>
    <a href="PHP/regist.php">新規登録</a>
    <div>管理者の方は<a href="PHP/admin_home.php">こちら</a></div>
    <div>発表会用<a href="PHP/start_user.php">こちら</a></div>
</section>
<script>
    let inputText = ''; // 入力されたテキストを保持する変数

    // body全体にキー入力を検出するリスナーを設定
    document.body.addEventListener('keydown', function(event) {
        // Enterキーが押されたかどうかを確認
        if (event.key === 'Enter') {
            event.preventDefault(); // Enterキーのデフォルト動作を防止
            console.log(inputText);
            // 入力されたテキストが "[start]" ならリダイレクト
            if (inputText === 'start') {
                window.location.href = 'PHP/start_user.php'; // リダイレクト先のURL
            }

            // 入力をリセット
            inputText = '';
        } else if (event.key.length === 1) {
            // アルファベットや数字などの1文字のキーが押された場合のみ追加
            inputText += event.key;
            
        }
    });
</script>
</body>
</html>




