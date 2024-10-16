<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/indexStyle.css">
    <link rel="stylesheet" href="../CSS/yoyakukakuninStyle.css">
    <title>予約フォーム</title>
</head>
<body>

<header>
    <div class="flexBox">
        <div class="menu"></div>
        <div class="logo">
            <img src="../img/logo.jpg" alt="" class="logo2">
        </div>
        <div class="icon"></div>
    </div>
</header>

<?php
session_start();
$user_id = $_SESSION['user_id'];

// データベース接続情報
require_once('db_local.php'); // データベース接続

try {
    // データベースに接続
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbUsername, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $yoyakusql = "SELECT username, id, reservation_date, start_time, end_time, location FROM yoyaku WHERE username = :user_id";
    $stmt = $pdo->prepare($yoyakusql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $usersql = "SELECT username FROM users_kokyaku INNER JOIN users ON users_kokyaku.user_id = users.id WHERE users.id = :user_id";
    $stmt = $pdo->prepare($usersql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    $userrow = $stmt->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "<p>データベースエラー: " . $e->getMessage() . "</p>";
} catch (Exception $e) {
    echo "<p>エラー: " . $e->getMessage() . "</p>";
}
?>

<div class="container">
    <h1>予約確認フォーム</h1>
    <?php
        if($row){
            foreach($row as $rows){
                $username = $userrow['username'];
                $location = $rows['location'];
                $reservation_date = $rows['reservation_date'];
                $start_time = $rows['start_time'];
                $end_time = $rows['end_time'];

                echo "<li><h2>施設名: $location</h2>";
                echo "<p>ユーザー名: $username</p>";
                echo "<p>日程: $reservation_date</p>";
                echo "<p>開始時間: $start_time</p>";
                echo "<p>終了時間: $end_time</p>";
                echo <<<HTML
                <form action="./resv_change.php" method="post">
                    <div class='resvChange'>
                        <input type="hidden" name="reservation_id" value="{$rows['id']}">
                        <input type="submit" class="" value="変更">
                    </div>
                </form>
                </li>
                HTML;
            }
        } else {
            echo "指定された施設が見つかりません。";
        }
    ?>
</div>
</body>
</html>
