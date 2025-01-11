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
    .gray-out {
        color: gray;
        opacity: 0.6;
        pointer-events: none;
    }
</style>
<body>



<?php
session_start();
$user_id = $_SESSION['user_id'];
$directory = '<a href="../index.php">マップ</a> > <a href="./ReserveCheck_Customer.php">予約確認ページ</a>';

$order_by = isset($_GET['order']) ? $_GET['order'] : 'asc';

// SQLの並べ替え順を動的に変更
if ($order_by == 'asc') {
    $order_sql = "ORDER BY reservation_date ASC";
} elseif ($order_by == 'desc') {
    $order_sql = "ORDER BY reservation_date DESC";
} else {
    // 今日から近い順
    $order_sql = "ORDER BY ABS(DATEDIFF(reservation_date, CURDATE())) ASC";
}


try {
    require_once('../Model/dbModel.php');
    require_once('../Model/Delete_Reserve.php');
    // データベースに接続
    $pdo = dbConnect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $yoyakusql = "SELECT username, id, reservation_date, start_time, end_time, location,
                    CASE 
                    WHEN reservation_date < CURDATE() THEN 'past' 
                    ELSE 'future'
                    END AS reservation_status
                    FROM yoyaku 
                    WHERE username = :user_id
                    AND reservation_date >= CURDATE() - INTERVAL 5 YEAR
                    " . $order_sql;
    $stmt = $pdo->prepare($yoyakusql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $usersql = "SELECT username 
                FROM users_kokyaku 
                INNER JOIN users 
                ON users_kokyaku.user_id = users.id 
                WHERE users.id = :user_id";
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

<?php include 'header.php';?>

<div class="container">
    <h1>予約確認フォーム</h1>
    <p class="p">予約を変更する場合は、一度予約を削除し、再度予約をしてください</p>
    <form method="GET" action="ReserveCheck_Customer.php">
        <label for="order">並べ替え順: </label>
        <select name="order" id="order">
            <option value="asc">昇順（古い日付順）</option>
            <option value="desc">降順（新しい日付順）</option>
            <option value="near">今日から近い順</option>
        </select>
        <input type="submit" value="並べ替え">
    </form>
    <?php
        if($row){
            foreach($row as $rows){
                $username = $userrow['username'];
                $location = $rows['location'];
                $reservation_date = $rows['reservation_date'];
                $start_time = $rows['start_time'];
                $end_time = $rows['end_time'];
                $id = $rows['id'];

                // 今日以前の予約をグレーアウト
                $status = (strtotime($reservation_date) < strtotime('today')) ? 'past' : 'future';
                $class = ($status === 'past') ? 'gray-out' : '';
                
                echo '<li class="' . $class . '"><h2>施設名: ' . $location . '</h2>';
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