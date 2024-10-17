<?php
session_start();
require_once('db_connection.php'); // データベース接続



// データベースからアクセスログを取得
try {
    $sql = "SELECT * FROM access_logs ORDER BY access_time DESC";
    $stmt = $pdo->query($sql);
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "エラー: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>アクセスログ管理</title>
</head>
<body>
    <h3>アクセスログ一覧</h3>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>ユーザー名</th>
            <th>IPアドレス</th>
            <th>アクセス時間</th>
        </tr>
        <?php foreach ($logs as $log): ?>
        <tr>
            <td><?= htmlspecialchars($log['id']) ?></td>
            <td><?= htmlspecialchars($log['username']) ?></td>
            <td><?= htmlspecialchars($log['ip_address']) ?></td>
            <td><?= htmlspecialchars($log['access_time']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>

