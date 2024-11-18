<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/formStyle.css">
    <title>アカウント登録</title>
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
    </header>

<?php
// データベース接続情報

session_start();
// if (!isset($_SESSION['user_id'])) {
//     $_SESSION['login_message'] = "ログインしてください。"; // メッセージをセッションに保存
//     header('Location: message.php');
//     exit;
// }

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
}

require_once('../Model/dbmodel.php');

try {
    $pdo = dbConnect();
} catch (PDOException $e) {
    echo $e->getMessage();
}

if (isset($_SESSION['user_id'])) {
    $sql = "SELECT imgpath FROM users WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $user_id);
    $stmt->execute();
    $image = $stmt->fetch();
}
        
?>

<h1>画像表示</h1>
<img src="../images/<?php echo $image['imgpath']; ?>" width="300" height="300">
<a href="upload.php">画像アップロード</a>
<a href="Mypage_user.php">マイページ</a>

<script src="../JS/pass.js"></script>

</body>
</html>