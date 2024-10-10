<?php
session_start(); // セッションを開始
$location = $_SESSION['location'];

try {
    require_once('../Model/dbModel.php');
    // データベースに接続
    $pdo = dbConnect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //1回目は回らない
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['time'])) {
        // フォームから送信された時間帯のデータを取得
        $user_id = $_SESSION['user_id']; // ログインシステムから取得
        $reservation_date = $_SESSION['reservation_date']; // 前回のPOSTで保持した日付を利用

        // 選択された時間の処理
        $times = explode(',', $_POST['time']);
        $radio_start_time = $times[0];
        $radio_end_time = $times[1];

        $area_id = $_SESSION['area_id'];
        $facility_name = $_SESSION['facility_name'];

        // SQLクエリを準備して実行
        reservationEntry($pdo, $user_id, $reservation_date, $area_id, $radio_start_time, $radio_end_time, $facility_name);
        
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
        $row = getArea($pdo, $location);

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
        $rowtimes = getTimeAll($pdo, $reservation_date, $facility_name, $status);

        if (isset($rowtimes) && count($rowtimes) > 0) {
            echo "<form method='post'>";
            foreach ($rowtimes as $rowtime) {
                $start_time = $rowtime['hour_only'];
                $end_time = $rowtime['hour_only_end'];
                echo <<<HTML
                <div class='timeSelect'>
                    <input type="radio" name="time" class="distime" value="{$start_time}:00:00,{$end_time}:00:00"> {$start_time} 時 ～ {$end_time} 時
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
