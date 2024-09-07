<?php
// データベース接続情報
$servername = "localhost";
$dbUsername = "root";
$password = "";
$dbname = "ecoperks";

try {
    // データベースに接続
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbUsername, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // フォームから送信されたデータを取得
    $user_id = 1; // 仮のユーザーID（本来はログインシステムから取得）
    // URLからエリアIDを取得
    $location = $_GET['location'];
    $reservation_date = $_POST['reservation_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    // 予約の重複チェック
    $sql = "SELECT * FROM yoyaku WHERE reservation_date = :reservation_date AND 
            ((start_time <= :start_time AND end_time > :start_time) OR 
             (start_time < :end_time AND end_time >= :end_time))";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':location', $location);
    $stmt->bindParam(':reservation_date', $reservation_date);
    $stmt->bindParam(':start_time', $start_time);
    $stmt->bindParam(':end_time', $end_time);
    $stmt->execute();

    // 結果を取得
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($stmt->rowCount() > 0) {
        echo "この時間帯は既に予約されています。別の時間を選択してください。";
    } else {
        // SQLクエリを準備して実行
        $sql = "INSERT INTO yoyaku (user_id, reservation_date, start_time, end_time, location) 
                VALUES (:user_id, :reservation_date, :start_time, :end_time, :location)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':reservation_date', $reservation_date, PDO::PARAM_STR);
        $stmt->bindParam(':start_time', $start_time, PDO::PARAM_STR);
        $stmt->bindParam(':end_time', $end_time, PDO::PARAM_STR);
        $stmt->execute();

        echo "予約が正常に完了しました！";
    }
} catch (PDOException $e) {
    echo "データベースエラー: " . $e->getMessage();
}
