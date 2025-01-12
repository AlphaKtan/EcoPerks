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

// SQLの並べ替え順を動的に変更する関数
if ($order_by == 'asc') {
    $order_sql = "ORDER BY reservation_date ASC";
} elseif ($order_by == 'desc') {
    $order_sql = "ORDER BY reservation_date DESC";
} else {
    // 今日から近い順
    $order_sql = "ORDER BY ABS(DATEDIFF(reservation_date, CURDATE())) ASC";
}

//参加か不参加か参加中かを変更する関数
$selected_status = $_GET['status'] ?? 'all';
$status_condition = match ($selected_status) {
    '0' => "AND status = 0", // 不参加
    '1' => "AND status = 1", // 参加中
    '2' => "AND status = 2", // 参加済み
    'all' => "", // 全部表示
    default => "",
};



try {
    require_once('../Model/dbModel.php');
    require_once('../Model/Delete_Reserve.php');
    // データベースに接続
    $pdo = dbConnect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $yoyakusql = "SELECT username, id, reservation_date, start_time, end_time, location, status,
                        CASE 
                            WHEN status = 0 THEN '不参加'
                            WHEN status = 1 THEN '参加中'
                            WHEN status = 2 THEN '参加済み'
                            ELSE '未設定'
                        END AS status_label,
                        CASE 
                            WHEN reservation_date < CURDATE() THEN 'past' 
                            ELSE 'future'
                        END AS reservation_status
                  FROM yoyaku 
                  WHERE username = :user_id
                  AND reservation_date >= CURDATE() - INTERVAL 5 YEAR
                  $status_condition
                  $order_sql";
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

        <label for="status">参加状態: </label>
        <select name="status" id="status">
            <option value="all" <?= $selected_status === 'all' ? 'selected' : '' ?>>すべて</option>
            <option value="0" <?= $selected_status === '0' ? 'selected' : '' ?>>不参加</option>
            <option value="1" <?= $selected_status === '1' ? 'selected' : '' ?>>参加中</option>
            <option value="2" <?= $selected_status === '2' ? 'selected' : '' ?>>参加済み</option>
        </select>

        <input type="submit" value="適用">
    </form>

    <?php
        if($row){
            foreach($row as $rows){
                $username = $userrow['username'];
                $location = $rows['location'];
                $reservation_date = $rows['reservation_date'];
                $start_time = $rows['start_time'];
                $end_time = $rows['end_time'];
                $status_label = $rows['status_label'];
                $id = $rows['id'];

                // 今日以前の予約をグレーアウト
                $status = (strtotime($reservation_date) < strtotime('today')) ? 'past' : 'future';
                $class = ($status === 'past') ? 'gray-out' : '';
                
                echo '<li class="' . $class . '"><h2>施設名: ' . $location . '</h2>';
                echo "<p>ユーザー名: $username</p>";
                echo "<p>日程: $reservation_date</p>";
                echo "<p>開始時間: $start_time</p>";
                echo "<p>終了時間: $end_time</p>";
                echo "<p>参加状態: $status_label</p>";
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