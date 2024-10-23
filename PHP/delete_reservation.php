<?php

// POSTデータの取得
$reservation_id = isset($_POST['reservation_id']) ? $_POST['reservation_id'] : null;

if ($reservation_id === null) {
    echo json_encode(['success' => false, 'message' => '予約IDが指定されていません。']);
    exit;
}

try {
    require '../Model/dbModel.php';

    // DB接続
    $pdo = dbConnect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 予約を削除するSQL文
    $stmt = $pdo->prepare("UPDATE time_change SET status = 0 WHERE id = :id");
    $stmt->bindParam(':id', $reservation_id, PDO::PARAM_INT);
    
    // SQLを実行
    $success = $stmt->execute();

    // 削除結果を返す
    if ($success) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => '削除に失敗しました。']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'エラーが発生しました: ' . $e->getMessage()]);
}

// データベース接続を閉じる
$pdo = null;
?>
