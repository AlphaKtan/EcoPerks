<?php
session_start();
require_once('../Model/dbmodel.php');

try {
    $pdo = dbConnect(); // データベース接続
} catch (PDOException $e) {
    echo "データベース接続エラー: " . $e->getMessage();
    exit;
}

// データを取得するSQL
try {
    $sql = "SELECT survey_id AS survey_id, gomi, body, image_path, created_at, areaid FROM survey_responses ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC); // 結果を取得
} catch (PDOException $e) {
    echo "データ取得エラー: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/formStyle.css">
    <title>アンケートフォーム</title>
</head>
<body>

<header>
    <div class="flexBox">
        <div class="menu"></div>
        <div class="logo">
            <img src="../img/logo.jpg" alt="" class="logo2">
        </div>
        <div class="icon"></div>
    </div>
</header>

<section class="data_display">
    <h1>アンケートデータ一覧</h1>
    <?php if (!empty($results)): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ゴミの量</th>
                    <th>お問い合わせ内容</th>
                    <th>画像</th>
                    <th>作成日時</th>
                    <th>エリアID</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['survey_id'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td>
                            <?php 
                                $gomi_values = ['4' => '多い', '3' => 'まぁまぁ多い', '2' => 'まぁまぁ少ない', '1' => '少ない'];
                                echo $gomi_values[$row['gomi']] ?? '不明';
                            ?>
                        </td>
                        <td><?php echo nl2br(htmlspecialchars($row['body'], ENT_QUOTES, 'UTF-8')); ?></td>
                        <td>
                            <?php if (!empty($row['image_path'])): ?>
                                <img src="<?php echo htmlspecialchars($row['image_path'], ENT_QUOTES, 'UTF-8'); ?>" alt="Image" style="max-width: 100px;">
                            <?php else: ?>
                                画像なし
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['created_at'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row['areaid'], ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>データがありません。</p>
    <?php endif; ?>
</section>
<a href="Survey_Customer.php">あんけーと</a>
</body>
</html>
