<?php
// データベース接続情報
require_once('db_connection.php'); // データベース接続

try {
    // データベースに接続
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbUsername, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // フォームから送信されたデータを取得
    $area_id = $_POST['area_id'];
    $facility_name = $_POST['facility_name'];
    $address = $_POST['address'];

    // SQLクエリを準備
    $sql = "INSERT INTO travel_data (area_id, facility_name, address) VALUES (:area_id, :facility_name, :address)";
    $stmt = $pdo->prepare($sql);

    // データをバインドしてSQLクエリを実行
    $stmt->bindParam(':area_id', $area_id, PDO::PARAM_INT);
    $stmt->bindParam(':facility_name', $facility_name, PDO::PARAM_STR);
    $stmt->bindParam(':address', $address, PDO::PARAM_STR);
    $stmt->execute();

    // 成功メッセージとリンクを表示
    echo "データが正常に挿入されました！<br>";
    echo '挿入を続ける場合はこちら: <a href="https://i2322117.chips.jp/php/input_data.php">こちらをクリック</a>';
} catch (PDOException $e) {
    // エラーメッセージを表示
    echo "データベースエラー: " . $e->getMessage();
}


