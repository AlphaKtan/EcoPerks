<?php
// データベース接続情報
$servername = "mysql305.phy.lolipop.lan";
$dbUsername = "LAA1516370";
$password = "ecoperks2024";
$dbname = "LAA1516370-ecoperks";

try {
    // データベース接続
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbUsername, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // QRコードからの情報を取得
    if (isset($_GET['location_id']) && isset($_GET['action'])) {
        $area_id = $_GET['location_id']; // QRコードに含まれる地点ID（area_id）
        $action = $_GET['action'];
        $username = "testuser"; // 仮のユーザー名。ログインシステムとの連携が必要
        $current_time = date("Y-m-d H:i:s");

        if ($action === 'start') {
            // ゴミ拾い開始の処理
            $sql = "INSERT INTO cleaning_records (username, area_id, start_time) VALUES (:username, :area_id, :start_time)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':area_id', $area_id);
            $stmt->bindParam(':start_time', $current_time);
            $stmt->execute();

            echo "ゴミ拾いが地点 $area_id で開始されました！";

        } elseif ($action === 'end') {
            // ゴミ拾い終了の処理
            $sql = "UPDATE cleaning_records SET end_time = :end_time WHERE username = :username AND area_id = :area_id AND end_time IS NULL";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':area_id', $area_id);
            $stmt->bindParam(':end_time', $current_time);
            $stmt->execute();

            echo "ゴミ拾いが地点 $area_id で終了しました！";
        } else {
            echo "無効なアクションです。";
        }
    } else {
        echo "QRコードの情報が不完全です。";
    }

} catch (PDOException $e) {
    echo "データベースエラー: " . $e->getMessage();
}


