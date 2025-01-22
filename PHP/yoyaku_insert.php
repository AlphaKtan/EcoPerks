<?php
session_start();
    require '../Model/dbModel.php';

    // DB接続
    $pdo = dbConnect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if ($_POST['facility']) {
        $data = json_decode($_POST['facility'], true);
        $start_time = $data['start_time'];
        $end_time = $data['end_time'];
        $price = $data['price'];
    }

    $status = '0';

    if ($_SESSION['location']) {
        $location = $_SESSION['location'];
        $facilitySql = "SELECT facility_name, area_id FROM travel_data WHERE id = :facility";
        $facilityStmt = $pdo->prepare($facilitySql);
        $facilityStmt->bindParam(':facility', $location, PDO::PARAM_STR);
        $facilityStmt->execute();
        $facilityRow = $facilityStmt->fetch(PDO::FETCH_ASSOC);
    }

    // 予約を登録
    $stmt = $pdo->prepare("INSERT INTO yoyaku(username, reservation_date, area_id, start_time, end_time, status, location, price) 
    VALUES(:username, :date, :area_id, :start_time, :end_time, :status, :location, :price)");
    $stmt->bindParam(':username', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->bindParam(':date', $_POST['date'], PDO::PARAM_STR);
    $stmt->bindParam(':area_id', $facilityRow['area_id'], PDO::PARAM_INT);
    $stmt->bindParam(':start_time', $start_time, PDO::PARAM_STR);
    $stmt->bindParam(':end_time', $end_time, PDO::PARAM_STR);
    $stmt->bindParam(':status', $status, PDO::PARAM_STR);
    $stmt->bindParam(':location', $facilityRow['facility_name'], PDO::PARAM_STR);
    $stmt->bindParam(':price', $price, PDO::PARAM_INT);
    $stmt->execute();

    echo <<<HTML
    
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <div><p>予約が完了しました！！</p></div>
        <a href="../index.php">ホームに戻る</a>
    HTML;
    exit();

?>
