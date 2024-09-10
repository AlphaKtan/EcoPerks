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
    $TIME = array(9, 10, 11, 12, 13, 14, 15);
    
    // データ挿入処理
    foreach ($TIME as $start_time) {
        $end_time = $start_time + 1;
    
        foreach ($locations as $location) {
            $area_id = $location['area_id'];
            $facility_name = $location['facility_name'];
    
            // SQLクエリを準備して実行
            $sql = "INSERT INTO time_change (id, start_time, end_time, facility_name, areaid, status) 
            VALUES (null, :start_time, :end_time, :facility_name, :areaid, '1')";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':start_time', $start_time, PDO::PARAM_INT);
            $stmt->bindParam(':end_time', $end_time, PDO::PARAM_INT);
            $stmt->bindParam(':facility_name', $facility_name, PDO::PARAM_STR);
            $stmt->bindParam(':areaid', $area_id, PDO::PARAM_INT);
            $stmt->execute();
        }
    }
    



    // 結果を全て取得
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "エラー: " . $e->getMessage();
}
?>