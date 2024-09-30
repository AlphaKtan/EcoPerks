<?php
session_start(); // セッションを開始
$location = $_SESSION['location'];

// データベース接続情報
$servername = "localhost";
$dbUsername = "root";
$password = "";
$dbname = "ecoperks";

try {
    // データベースに接続
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbUsername, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //1回目は回らない
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['time'])) {
        // フォームから送信された時間帯のデータを取得
        $user_id = 1; // 仮のユーザーID（本来はログインシステムから取得）
        $reservation_date = $_SESSION['reservation_date']; // 前回のPOSTで保持した日付を利用

        // 選択された時間の処理
        $times = explode(',', $_POST['time']);
        $radio_start_time = $times[0];
        $radio_end_time = $times[1];

        $area_id = $_SESSION['area_id'];
        $facility_name = $_SESSION['facility_name'];

        // SQLクエリを準備して実行
        $sql = "INSERT INTO yoyaku (username, reservation_date, area_id, start_time, end_time, location) 
                VALUES (:username, :reservation_date, :area_id,:start_time, :end_time, :facility_name)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':reservation_date', $reservation_date, PDO::PARAM_STR);
        $stmt->bindParam(':area_id', $area_id, PDO::PARAM_INT);
        $stmt->bindParam(':start_time', $radio_start_time, PDO::PARAM_STR);
        $stmt->bindParam(':end_time', $radio_end_time, PDO::PARAM_STR);
        $stmt->bindParam(':facility_name', $facility_name, PDO::PARAM_STR);

        if (empty($radio_start_time) || empty($radio_end_time)) {
            throw new Exception("開始時間または終了時間が設定されていません。");
        }

        $stmt->execute();
        echo "<p>予約が正常に完了しました！</p>";

    } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // 初回のPOSTリクエストの処理
        if (isset($_POST['reservation_date'])) {
            $reservation_date = $_POST['reservation_date'];
            $_SESSION['reservation_date'] = $reservation_date; // 後のPOST用にセッションに保存
        } else {
            echo "予約日が指定されていません。";
        }

        // エリア情報の取得
        $locationSql = "SELECT id,area_id, facility_name, address FROM travel_data WHERE id = :location";
        $newStmt = $pdo->prepare($locationSql);
        $newStmt->bindParam(':location', $location, PDO::PARAM_INT);
        $newStmt->execute();

        $row = $newStmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $facility_name = $row['facility_name'];
            $_SESSION['facility_name'] = $row['facility_name'];
            $_SESSION['area_id'] = $row['area_id'];
        } else {
            throw new Exception("指定された施設が見つかりません。");
        }

        // デバッグ用の出力
        // echo "<pre>";
        // print_r($row);
        // echo "</pre>";

        $status = 1;

        // 時間帯の取得
        $timeSql = "SELECT DATE_FORMAT(start_time, '%H') AS hour_only, DATE_FORMAT(end_time, '%H') AS hour_only_end FROM time_change
                    WHERE DATE_FORMAT(start_time, '%Y-%m-%d') = :reservation_date AND facility_name = :facility_name AND status = :status";
        $timeStmt = $pdo->prepare($timeSql);
        $timeStmt->bindParam(':reservation_date', $reservation_date, PDO::PARAM_STR);
        $timeStmt->bindParam(':facility_name', $facility_name, PDO::PARAM_STR);
        $timeStmt->bindParam(':status', $status, PDO::PARAM_INT);
        $timeStmt->execute();

        $rowtimes = $timeStmt->fetchAll(PDO::FETCH_ASSOC);

        if (isset($rowtimes) && count($rowtimes) > 0) {
            echo "<form method='post'>";
            foreach ($rowtimes as $rowtime) {
                $start_time = $rowtime['hour_only'];
                $end_time = $rowtime['hour_only_end'];
                echo <<<HTML
                <div class='timeSelect'>
                    <input type="radio" name="time" value="{$start_time}:00:00,{$end_time}:00:00"> {$start_time} 時 ～ {$end_time} 時
                </div>
                HTML;
            }
            echo "<input type='submit' value='予約'></form>";
        } else {
            echo "<p>指定された時間が見つかりません。</p>";
        }
    }

} catch (PDOException $e) {
    echo "<p>データベースエラー: " . $e->getMessage() . "</p>";
} catch (Exception $e) {
    echo "<p>エラー: " . $e->getMessage() . "</p>";
}
?>
