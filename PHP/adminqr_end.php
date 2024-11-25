<?php
session_start(); // セッションを開始

// POSTリクエストが送信された場合にエリアIDをセッションに保存
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['area_id'])) {
    $_SESSION['area_id'] = htmlspecialchars($_POST['area_id'], ENT_QUOTES, 'UTF-8');
    header('Location: owari.php'); // QRコード生成ページにリダイレクト
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者エリア終わり選択ページ</title>
</head>
<body>
    <h2>エリアを選択してください</h2>
    <form action="owari.php" method="post"> <!-- 現在のページにPOSTリクエストを送信 -->
        <label for="area_id">エリアID:</label>
        <select name="area_id" id="area_id">
            <?php for ($i = 1; $i <= 25; $i++): ?>
                <option value="<?php echo $i; ?>">エリア <?php echo $i; ?></option>
            <?php endfor; ?>
        </select>
        <br><br>
        <button type="submit">選択</button>
    </form>
</body>
</html>