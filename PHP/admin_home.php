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
    <link rel="stylesheet" href="../CSS/sidebar.css">
</head>
<body>
<?php include './admin_header.php' ?>  

    <script>
        let directory = <?= json_encode(pathinfo(basename($_SERVER['REQUEST_URI']), PATHINFO_FILENAME)); ?>;
        console.log(directory);
        let element = document.querySelector(`.${directory}`);
        element.style.background = '#DBDBDB';
    </script>