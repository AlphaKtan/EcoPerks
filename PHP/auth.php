
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>認証ページ</title>
</head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/destyle.css@1.0.15/destyle.css"/>
    <link rel="stylesheet" href="../CSS/hannbaka.css">
    <link rel="stylesheet" href="../CSS/adminqr.css">
    <link rel="stylesheet" href="../CSS/sidebar.css">
    <header>
        <div class="flexBox">
            <div class="menu">
            </div>
            <div class="logo">
                <img src="../img/logo_yoko.svg" alt="" class="logo2">
            </div>
            <div class="icon">
                <form action="logout.php" method="post" class = "logout_form">
                    <button type="submit" class="logout1"><a href="admin_home.php" onclick="history.back()">戻る</a></button>
                </form>
            </div>
        </div>
    </header>    
<body>
<div class="left-menu">
        <div>
            <ul class="menu-list">
                <p class="text-box">アカウント情報</p>
                <li class="menu-item"><a href="admin.php" class="a_link"><img src="../img/hito.png" class="logo"><span class="menu-item-text">ログイン</span></a></li>
                <p class="text-box">QRコード</p>
                <li class="menu-item"><a href="adminqr.php" class="a_link"><img src="../img/qr.png" class="logo"><span class="menu-item-text">QRコード生成ページ</span></a></li>
                <li class="menu-item"><a href="adminqr_end.php" class="a_link"><img src="../img/qr.png" class="logo"><span class="menu-item-text">QRコード終了管理</span></a></li>
                <p class="text-box">その他/管理・表示</p>
                <li class="menu-item"><a href="data.php" class="a_link"><img src="../img/DB.png" class="logo"></span><span class="menu-item-text-chat">データ管理ページ</span></a></li>
                <li class="menu-item"><a href="sankakanri.php" class="a_link"><img src="../img/tuika.png" class="logo"><span class="menu-item-text">参加者管理ページ</span></a></li>
                <li class="menu-item"><a href="shift.php" class="a_link"><img src="../img/shift.png" class="logo"><span class="menu-item-text">シフトページ</span></a></li>
                <li class="menu-item"><a href="time_change.php" class="a_link"><img src="../img/shiftsakujo.png" class="logo"><span class="menu-item-text">シフト削除</span></a></li>
                <li class="menu-item"><a href="access_log.php" class="a_link"><img src="../img/log2.png" class="logo"><span class="menu-item-text">アクセスログ表示ページ</span></a></li>
            </ul>
            <ul class="menu-list-bottom">
            </ul>
        </div>
    </div>
<div class="right-content">
</ul>
    <h1 class = "rogu">ログイン</h1>
    <form action="login_process.php" method="post">
        <label for="password">パスワード:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit" class ="box">ログイン</button>
    </form>
    <?php
    if (isset($_GET['error'])) {
        echo '<p style="color: red;">パスワードが間違っています。</p>';
    }
    ?>
</body>
</html>



