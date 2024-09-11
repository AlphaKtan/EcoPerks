<?php
// データベース接続情報
$servername = "localhost";
$dbUsername = "root";
$password = "";
$dbname = "ecoperks";

try {
    // データベースに接続
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbUsername, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // データ取得用のSQLクエリ
    $sql = "SELECT `id`, `area_id`, `facility_name`, `address` FROM travel_data";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    // 取得したデータを配列に格納
    $locations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // タイムスロットを設定
    $TIME = array(9, 10, 11, 12, 13, 14, 15, 16);

    // 月ごとの日にちの設定
    $month_days = [
        6 => [4, 8, 11, 14, 18, 22, 25, 29], // 6月
        7 => [2, 5, 9, 12, 15, 19, 23, 27], // 7月
        8 => [2, 6, 9, 13, 17, 20, 23, 27, 30] // 8月
    ];
    
    // データ挿入処理
    foreach ($month_days as $month => $days) {
        foreach ($days as $day) {
            foreach ($TIME as $start_hour) {
                $end_hour = $start_hour + 1;

                // 日時フォーマットでstart_time, end_timeを作成
                $start_time = "2025-$month-$day $start_hour:00:00";
                $end_time = "2025-$month-$day $end_hour:00:00";

                foreach ($locations as $location) {
                    $area_id = $location['area_id'];
                    $facility_name = $location['facility_name'];
    
                    // SQLクエリを準備して実行
                    $sql = "INSERT INTO time_change (id, start_time, end_time, facility_name, areaid, status) 
                    VALUES (null, :start_time, :end_time, :facility_name, :areaid, '1')";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':start_time', $start_time, PDO::PARAM_STR); // datetimeなのでSTR型に変更
                    $stmt->bindParam(':end_time', $end_time, PDO::PARAM_STR); // 同じくSTR型に変更
                    $stmt->bindParam(':facility_name', $facility_name, PDO::PARAM_STR);
                    $stmt->bindParam(':areaid', $area_id, PDO::PARAM_INT);
                    $stmt->execute();
                }
            }
        }
    }
    
    echo "データが正常に挿入されました";

} catch (PDOException $e) {
    echo "エラー: " . $e->getMessage();
}
?>
