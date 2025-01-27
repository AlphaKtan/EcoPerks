<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者側ログイン</title>
    <link rel="stylesheet" href="../CSS/loginStyle.css">
</head>
<body>
    <form action="./admin_authenticate.php" method="post"  class="login_form">
        <img src="../img/logo_yoko.svg" class="login_img">
        <?php 
            if(isset($_GET['error'])){
                $error_message = $_GET['error'];
                echo htmlspecialchars($error_message)."<br>"; 
            }
        ?>
        <b class="b">管理者のログイン</b>
        <div class="form-group"><input type="text" name="admin_id" id="login" placeholder="ログインID"></div>
        <div class="form-group"><input type="password" name="password" id="pass" placeholder="パスワード"></div>
        <input type="submit" value="ログイン" id="submitBtn">
    </form>
</body>
</html>