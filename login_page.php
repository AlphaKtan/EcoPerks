<!DOCTYPE html>
<!-- ログイン-->
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="CSS/loginStyle.css">
    <link rel="stylesheet" href="CSS/indexStyle.css">
    <title>ログイン</title>
    <script src="JS/update_time.js" defer></script>
</head>
<body>
    <header>
        <div class="flexBox">
            <div class="menu">
                <button class="menu_button" type="button">
                    管理者?
                </button>
            </div>
            <div class="logo">
                <img src="img/logo.jpg" alt="" class="logo2">
            </div>
            <div class="icon"></div>
        </div>
        <div class="sub_header">
            <div class="sub_header_box1">
                <div style="display: flex;">
                    <p style="padding-left: 10px; color:#ffff;">マップ > ログイン</p>
                </div>
                </div>
                    <div class="sub_header_box2" style="border-left:solid 1px #ffff;">
                </div>
            </div>
    </header>

<section class="login_form">
    <h1>アカウントにサインアップ</h1>
    <p id="time">現在の時刻: </p>
    <form action="php/login.php" method="post">
        <div class="form-group">
            <label for="providedUsername">ユーザーネーム</label>
            <input type="text" name="providedUsername" required>
        </div>
        <div class="form-group">
            <label for="providedPassword">パスワード</label>
            <input type="password" name="providedPassword" required>
        </div>
        <button type="submit">続行</button>

    </form>
    <p>続行すると、利用規約とプライバシーポリシーを理解し、同意したことになります。</p>
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




