<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php if(isset($title)) {
                echo $title;
                } else {
                    echo "EcoPerks";
                }
         ?>
    </title>
    <link rel="stylesheet" href="../CSS/hannbaka.css">
    <link rel="stylesheet" href="../CSS/sidebar.css">
</head>
<body>
    <div class="left-menu">
        <div>
            <ul class="menu-list">
                <p class="text-box">アカウント情報</p>
                <li class="menu-item"><a href="admin.php" class="a_link"><img src="../img/main_user.svg" class="logo"><span class="menu-item-text">ログイン</span></a></li>
                <p class="text-box">QRコード</p>
                <li class="menu-item"><a href="adminqr.php" class="a_link"><img src="../img/QR.svg" class="logo"><span class="menu-item-text">QRコード生成ページ</span></a></li>
                <li class="menu-item"><a href="adminqr_end.php" class="a_link"><img src="../img/QR.svg" class="logo"><span class="menu-item-text">QRコード終了管理</span></a></li>
                <p class="text-box">その他/管理・表示</p>
                <li class="menu-item"><a href="data.php" class="a_link"><img src="../img/dataDB.svg" class="logo"></span><span class="menu-item-text-chat">データ管理ページ</span></a></li>
                <li class="menu-item"><a href="sankakanri.php" class="a_link"><img src="../img/user.svg" class="logo"><span class="menu-item-text">参加者管理ページ</span></a></li>
                <li class="menu-item"><a href="shift.php" class="a_link"><img src="../img/shift.svg" class="logo"><span class="menu-item-text">シフトページ</span></a></li>
                <li class="menu-item"><a href="time_change.php" class="a_link"><img src="../img/shift.svg" class="logo"><span class="menu-item-text">シフト削除</span></a></li>
                <li class="menu-item"><a href="access_log.php" class="a_link"><img src="../img/log.svg" class="logo"><span class="menu-item-text">アクセスログ表示ページ</span></a></li>
            </ul>
            <ul class="menu-list-bottom">
            </ul>
        </div>
    </div>
<div class="right-content">
