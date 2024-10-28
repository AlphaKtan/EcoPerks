<?php session_start(); // セッションを開始 ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/yoyakuoutputstyle.css">
    <title>予約システム</title>
</head>
<body>
<?php
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
        
        $_SESSION['resv_comp'] = "予約が正常に完了しました！";
        header('Location: homeback.php');


    } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // 初回のPOSTリクエストの処理
        if (isset($_POST['reservation_date'])) {
            $reservation_date = $_POST['reservation_date'];
            $_SESSION['reservation_date'] = $reservation_date; // 後のPOST用にセッションに保存
        } else {
            echo "予約日が指定されていません。";
            echo '<input type="button" onclick="window.history.back();" value="直前のページに戻る">';
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
        $i = 0;

        // 時間帯の取得
        $rowtimes = getTimeAll($pdo, $reservation_date, $facility_name, $status);

        if (isset($rowtimes) && count($rowtimes) > 0) {
            echo <<<HTML
                <form method='post'>
                <div class='container'>
                <h1>予約時間を選択してください</h1>
                <form action="submit.php" method="POST">
                <div class="radioBoxGroup">
            HTML;
            for ($hour = 9; $hour < 17; $hour++) {
                $start_time = sprintf("%02d", $hour);
                $end_time = sprintf("%02d", $hour + 1);
                echo "<div class='timeSelect'>
                        <label class='checkbox-label' for='radioLabel$hour'>
                            <input type='radio' name='time' class='distime' id='radioLabel$hour' value='{$start_time}:00:00,{$end_time}:00:00'> 
                            {$start_time} 時 ～ {$end_time} 時
                        </label>
                      </div>";
            }
            


            
            echo "</div><input type='submit' value='予約'></form>";
        } else {
            echo <<<HTML
                <div class="error-container">
                    <p class="error-message">指定された時間が見つかりません。</p>
                    <button class="back-button" onclick="window.history.back();">直前のページに戻る</button>
                </div>
            HTML;
        }
    }

} catch (PDOException $e) {
    echo "<p>データベースエラー: " . $e->getMessage() . "</p>";
} catch (Exception $e) {
    echo "<p>エラー: " . $e->getMessage() . "</p>";
}
?>
</form>
    </div>
</body>
</html>