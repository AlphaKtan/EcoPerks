<?php
session_start();
require_once('../Model/dbModel.php');
$pdo = dbConnect();

// POSTリクエストが送信された場合に施設名をセッションに保存
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['facility_id'])) {
    $facility_id = htmlspecialchars($_POST['facility_id'], ENT_QUOTES, 'UTF-8');

    // travel_data テーブルから施設情報を取得
    $stmt = $pdo->prepare("SELECT id, area_id, facility_name FROM travel_data WHERE id = :facility_id");
    $stmt->bindParam(':facility_id', $facility_id, PDO::PARAM_INT);
    $stmt->execute();
    $facility = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($facility) {
        $_SESSION['facility_name'] = $facility['facility_name'];
        $_SESSION['area_id'] = $facility['area_id']; // 必要に応じて使用
        header('Location: owari.php'); // QRコード生成ページにリダイレクト
        exit();
    } else {
        echo "施設が見つかりませんでした。";
    }
}

// travel_data テーブルから全施設を取得
$stmt = $pdo->query("SELECT id, facility_name FROM travel_data");
$facilities = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>施設選択ページ</title>
</head>
<body>
    <h2>施設を選択してください</h2>
    <form action="" method="post">
        <label for="facility_id">施設名:</label>
        <select name="facility_id" id="facility_id">
            <?php foreach ($facilities as $facility): ?>
                <option value="<?php echo htmlspecialchars($facility['id'], ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars($facility['facility_name'], ENT_QUOTES, 'UTF-8'); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>
        <button type="submit">選択</button>
    </form>
</body>
</html>

