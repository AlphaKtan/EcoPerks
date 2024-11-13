<?php
session_start();
require_once('db_connection.php'); // データベース接続ファイル

// ユーザーがログインしているか確認
if (!isset($_SESSION['username'])) {
    $_SESSION['login_message'] = "ログインしてください。";
    header('Location: message.php');
    exit;
}

$username = $_SESSION['username'];

// クーポン一覧を取得
$sql = "SELECT coupon_code, discount, expiry_date, status 
        FROM coupons 
        WHERE username = :username 
        ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':username', $username);
$stmt->execute();
$coupons = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>クーポン一覧</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2><?php echo htmlspecialchars($username); ?>さんのクーポン一覧</h2>
    <?php if ($coupons): ?>
        <table>
            <tr>
                <th>クーポンコード</th>
                <th>割引額</th>
                <th>有効期限</th>
                <th>ステータス</th>
            </tr>
            <?php foreach ($coupons as $coupon): ?>
                <tr>
                    <td><?php echo htmlspecialchars($coupon['coupon_code']); ?></td>
                    <td><?php echo htmlspecialchars($coupon['discount']); ?>円引き</td>
                    <td><?php echo htmlspecialchars($coupon['expiry_date']); ?></td>
                    <td><?php echo $coupon['status'] ? '使用済み' : '未使用'; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>現在利用可能なクーポンはありません。</p>
    <?php endif; ?>
</body>
</html>

