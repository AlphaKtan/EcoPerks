<?php
// データベース接続情報
$servername = "mysql305.phy.lolipop.lan";
$dbUsername = "LAA1516370";
$password = "ecoperks2024";
$dbname = "LAA1516370-ecoperks";

try {
    // データベース接続
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbUsername, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // アクションを判別（開始 or 終了）
    if (isset($_POST['action']) && $_POST['action'] === 'start') {
        // ゴミ拾い開始
        $username = "testuser"; // 仮のユーザー名、ログインシステムから取得することを想定
        $start_time = date("Y-m-d H:i:s");

        // 開始データの挿入
        $sql = "INSERT INTO cleaning_records (username, start_time) VALUES (:username, :start_time)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':start_time', $start_time);
        $stmt->execute();

        echo "ゴミ拾いが開始されました！";

    } elseif (isset($_POST['action']) && $_POST['action'] === 'end') {
        // ゴミ拾い終了
        $username = "testuser"; // 仮のユーザー名
        $end_time = date("Y-m-d H:i:s");

        // 終了データの更新
        $sql = "UPDATE cleaning_records SET end_time = :end_time WHERE username = :username AND end_time IS NULL";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':end_time', $end_time);
        $stmt->execute();

        echo "ゴミ拾いが終了しました！";
    } else {
        echo "アクションが指定されていません。";
    }

} catch (PDOException $e) {
    echo "データベースエラー: " . $e->getMessage();
}




