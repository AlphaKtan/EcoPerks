<?php 

require '../Model/dbModel.php';

// DB接続
$pdo = dbConnect();

try {
    // POSTデータの取得
    $start_time = $_POST['start_time'] ?? null;
    $end_time = $_POST['end_time'] ?? null;

    // 重複チェッククエリ
    $checkSql = "SELECT 1 FROM preset WHERE start_time = :start_time AND end_time = :end_time";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->bindParam(':start_time', $start_time, PDO::PARAM_STR);
    $checkStmt->bindParam(':end_time', $end_time, PDO::PARAM_STR);
    $checkStmt->execute();

    if ($checkStmt->fetch()) {
        // 重複が見つかった場合
        echo json_encode("以下が重複しています<br> 開始時間: $start_time, 終了時間: $end_time");
        exit;
    }

    // 新規挿入処理
    $insertSql = "INSERT INTO preset (start_time, end_time) VALUES (:start_time, :end_time)";
    $insertStmt = $pdo->prepare($insertSql);
    $insertStmt->bindParam(':start_time', $start_time, PDO::PARAM_STR);
    $insertStmt->bindParam(':end_time', $end_time, PDO::PARAM_STR);
    $insertStmt->execute();

    echo json_encode("正常に完了");

} catch (Exception $e) {
    // エラーハンドリング
    echo json_encode("エラー: " . $e->getMessage());
}
