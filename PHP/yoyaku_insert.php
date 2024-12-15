<?php
session_start();
    require '../Model/dbModel.php';

    // DB接続
    $pdo = dbConnect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if ($_POST['start_time']) {
        $start_time = $_POST['start_time'] . ':00';
    }

    if ($_POST['end_time']) {
        $end_time =$_POST['end_time'] . ':00';
        echo $end_time;
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

    // 予約の削除
    $stmt = $pdo->prepare("INSERT INTO yoyaku(username, reservation_date, area_id, start_time, end_time, status, location) 
    VALUES(:username, :date, :area_id, :start_time, :end_time, :status, :location)");
    $stmt->bindParam(':username', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->bindParam(':date', $_POST['date'], PDO::PARAM_STR);
    $stmt->bindParam(':area_id', $facilityRow['area_id'], PDO::PARAM_INT);
    $stmt->bindParam(':start_time', $start_time, PDO::PARAM_STR);
    $stmt->bindParam(':end_time', $end_time, PDO::PARAM_STR);
    $stmt->bindParam(':status', $status, PDO::PARAM_STR);
    $stmt->bindParam(':location', $facilityRow['facility_name'], PDO::PARAM_STR);
    $stmt->execute();

    header('Location: ../index.html');
    exit();

?>