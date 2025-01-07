<form action="./admin_authenticate.php" method="post">
    <?php 
        if(isset($_GET['error'])){
            $error_message = $_GET['error'];
            echo htmlspecialchars($error_message)."<br>"; 
        }
    ?>
    id:<input type="text" name="admin_id" id=""><br>
    パスワード:<input type="password" name="password" id=""><br>
    <input type="submit" value="ログイン">
</form>