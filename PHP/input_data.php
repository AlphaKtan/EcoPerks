<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>旅行データ入力フォーム</title>
</head>
<body>
    <h1>旅行データ入力フォーム</h1>
    <form action="submit_data.php" method="post">
        <label for="area_id">エリアID:</label><br>
        <input type="number" id="area_id" name="area_id" required><br><br>

        <label for="facility_name">施設名:</label><br>
        <input type="text" id="facility_name" name="facility_name" required><br><br>

        <label for="address">住所:</label><br>
        <input type="text" id="address" name="address" required><br><br>

        <input type="submit" value="送信">
    </form>
</body>
</html>


