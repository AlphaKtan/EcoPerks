<meta name="viewport" content="width=device-width, initial-scale=1.0">
<form action="./admin_authenticate.php" method="post">
    <?php 
        if(isset($_GET['error'])){
            $error_message = $_GET['error'];
            echo htmlspecialchars($error_message)."<br>"; 
        }
    ?>
    ログイン番号:<input type="text" name="admin_id" id="login"><br>
    パスワード:<input type="password" name="password" id="pass"><br>
    <input type="submit" value="ログイン">
</form>