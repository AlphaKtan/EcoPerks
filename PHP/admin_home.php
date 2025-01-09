<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$URL = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$_SESSION['URL'] = null;
$_SESSION['URL'] = $URL;
if (!isset($_SESSION['admin_id'])) {
    $_SESSION['login_message'] = "ログインしてください。"; // メッセージをセッションに保存
    header('Location: ./admin_message.php');
    exit;
}
if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
}
?>
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

                $directory = "管理者ページ";
         ?>
    </title>
    <link rel="stylesheet" href="../CSS/indexStyle.css">
    <link rel="stylesheet" href="../CSS/sidebar.css">
</head>
<body>
<header style = "height:65px">

        <div class="flexBox">
            <div class="menu">
            </div>
            <div class="logo">
                <img src="../img/logo_yoko.svg" alt="" class="logo2">
            </div>
            <div class="icon"></div>
        </div>

        <div class="sub_header">
            <div class="sub_header_box1">
                <div style="display: flex;">
                    <p style="padding-left: 10px; color:#ffff;"><?php echo $directory; ?></p>
                </div>
            </div>
            <div class="sub_header_box2" style="border-left:solid 1px #ffff;">
            </div>
        </div>

</header>
  
    <div class="left-menu">
        <div>
            <ul class="menu-list">
                <p class="text-box">アカウント情報</p>
                <li class="menu-item admin_login"><a href="./admin_login.php" class="a_link"><img src="../img/main_user.svg" class="logo"><span class="menu-item-text">ログイン</span></a></li>
                <p class="text-box">QRコード</p>
                <li class="menu-item create_qr"><a href="create_qr.php" class="a_link"><img src="../img/QR.svg" class="logo"><span class="menu-item-text">QRコード参加ページ</span></a></li>
                <li class="menu-item end_create_qr"><a href="end_create_qr.php" class="a_link"><img src="../img/QR.svg" class="logo"><span class="menu-item-text">QRコード終了ページ</span></a></li>
                <p class="text-box">その他/管理・表示</p>
                <li class="menu-item data"><a href="data.php" class="a_link"><img src="../img/dataDB.svg" class="logo"></span><span class="menu-item-text-chat">データ管理ページ</span></a></li>
                <li class="menu-item sankakanri"><a href="sankakanri.php" class="a_link"><img src="../img/user.svg" class="logo"><span class="menu-item-text">参加者管理ページ</span></a></li>
                <li class="menu-item shift"><a href="shift.php" class="a_link"><img src="../img/shift.svg" class="logo"><span class="menu-item-text">シフトページ</span></a></li>
                <li class="menu-item time_change"><a href="time_change.php" class="a_link"><img src="../img/shift.svg" class="logo"><span class="menu-item-text">シフト削除</span></a></li>
                <li class="menu-item access_log"><a href="access_log.php" class="a_link"><img src="../img/log.svg" class="logo"><span class="menu-item-text">アクセスログ表示ページ</span></a></li>
            </ul>
            <ul class="menu-list-bottom">
            <script>
                let directory = <?= json_encode(pathinfo(basename($_SERVER['REQUEST_URI']), PATHINFO_FILENAME)); ?>;
                console.log(directory);
                let element = document.querySelector('create_qr');
                console.log(element);
                
                element.style.background = 'gainsboro';
            </script>


            </ul>
        </div>
    </div>
<div class="right-content">
