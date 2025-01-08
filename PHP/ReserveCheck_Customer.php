<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/indexStyle.css">
    <link rel="stylesheet" href="../CSS/yoyakukakuninStyle.css">
    <title>予約フォーム</title>
</head>
<style>
    
</style>
<body>

<header>
    <div class="flexBox">
        <div class="menu">
        <button class="link_button" onclick="location.href='../index.php';">戻る</button>
        </div>
        <div class="logo">
            <img src="../img/logo_yoko.svg" alt="" class="logo2">
        </div>
        <div class="icon"></div>
    </div>
    <div class="sub_header">
        <div class="sub_header_box1">
            <div style="display: flex;">
                <p style="padding-left: 10px; color:#ffff;">マップ > マイページ > 予約確認</p>
            </div>
        </div>
        <div class="sub_header_box2" style="border-left:solid 1px #ffff;">
        </div>
    </div>
</header>



<?php
session_start();
$user_id = $_SESSION['user_id'];

try {
    require_once('../Model/dbModel.php');
    require_once('../Model/Delete_Reserve.php');
    // データベースに接続
    $pdo = dbConnect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $yoyakusql = "SELECT username, id, reservation_date, start_time, end_time, location FROM yoyaku WHERE username = :user_id AND reservation_date >= DATE(NOW())";
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
    <p class="p">予約を変更する場合は、一度予約を削除し、再度予約をしてください</p>
    <?php
        if($row){
            foreach($row as $rows){
                $username = $userrow['username'];
                $location = $rows['location'];
                $reservation_date = $rows['reservation_date'];
                $start_time = $rows['start_time'];
                $end_time = $rows['end_time'];
                $id = $rows['id'];

                echo "<li><h2>施設名: $location</h2>";
                echo "<p>ユーザー名: $username</p>";
                echo "<p>日程: $reservation_date</p>";
                echo "<p>開始時間: $start_time</p>";
                echo "<p>終了時間: $end_time</p>";
                echo <<<HTML
                <form action="./resv_change.php" method="post">
                    <div class='resvChange'>
                        <input type="hidden" name="reservation_id" value="$id">
                        <input type="submit" class="changeStyle" value="変更">
                    </div>
                </form>

                <form method='POST' action='' onsubmit='return confirmDelete();'>
                    <input type='hidden' name='delete_id' value='$id'>
                    <input type='submit' name='delete' value='削除' class='deleteStyle'>
                </form>
                </li>
                HTML;
            }
        } else {
            echo "<p>予約情報が見つかりませんでした。</p>";
        }
    ?>
</div>
</body>
</html>
<script>
    function confirmDelete() {
        return confirm("本当に削除してもよろしいですか？");
    }
</script>