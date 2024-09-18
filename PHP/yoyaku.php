<?php 
session_start();

if (isset($_GET['location'])) {
    $location = $_GET['location'];
    $_SESSION['location'] = $location;
} else {
    throw new Exception("locationが指定されていません。");
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/indexStyle.css">
    <link rel="stylesheet" href="../CSS/yoyakuStyle.css">
    <title>予約フォーム</title>
</head>
<body>

    <header>
        <div class="flexBox">
            <div class="menu">
            </div>
            <div class="logo">
                <img src="../img/logo.jpg" alt="" class="logo2">
            </div>
            <div class="icon"></div>
        </div>
    </header>



    <form class="yoyaku_form" action="yoyaku_output.php" method="post">
        <h1>予約フォーム</h1>
        <label for="reservation_date">予約日:</label>
        <input type="date" id="reservation_date" name="reservation_date" required>
    
        <input type="submit" value="時間を確認">

    </form>
</body>
</html>
