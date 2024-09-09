<?php
// データベース接続情報
$servername = "localhost";
$dbUsername = "root";
$password = "";
$dbname = "ecoperks";

try {
    // データベースに接続
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbUsername, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //PHP例外フロー

    // SQLクエリを準備して実行
    $sql = "SELECT username, location FROM yoyaku";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // 結果を全て取得
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "エラー: " . $e->getMessage();
}
?>

<style>
    table, th, td {
        border: 1px solid black;
        border-collapse: collapse; /* セルの境界線を重ねずに表示 */
    }
    th, td {
        padding: 8px; /* セル内に余白を追加 */
    }
</style>

<table>
    <tr><th>ユーザーID</th><th>場所</th></tr>
    <?php foreach ($rows as $row): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($row['location'], ENT_QUOTES, 'UTF-8'); ?></td>
        </tr>
    <?php endforeach; ?>
</table>
