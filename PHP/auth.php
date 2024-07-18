
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>認証ページ</title>
</head>
<body>
    <h1>ログイン</h1>
    <form action="login_process.php" method="post">
        <label for="password">パスワード:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">ログイン</button>
    </form>
    <?php
    if (isset($_GET['error'])) {
        echo '<p style="color: red;">パスワードが間違っています。</p>';
    }
    ?>
</body>
</html>



