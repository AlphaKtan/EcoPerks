<?php 
session_start();

    require '../Model/dbModel.php';
    
    // DB接続
    $pdo = dbConnect();

    if ($_POST["facility"]) {
        $facilityName = $_POST["facility"];
            $facilitySql = "SELECT facility_name FROM travel_data WHERE id = :facility";
            $facilityStmt = $pdo->prepare($facilitySql);
            $facilityStmt->bindParam(':facility', $facilityName, PDO::PARAM_INT);
            $facilityStmt->execute();
            $facilityRow = $facilityStmt->fetch(PDO::FETCH_ASSOC);
    }
    
 
    if ($_POST["ym"]) {
        $ym = $_POST["ym"];
    }
    if($_POST["day_count"]) {
        if ($_POST["ym"]) {
            $firstDay =$ym . '-'. '01';
            $lastDay = $ym . '-'. $_POST["day_count"];
        }
    }

    // シフトが入っている日を出す
    $sql = "SELECT DISTINCT DATE_FORMAT(start_time, '%Y-%m-%d') AS shift_date 
            FROM time_change 
            WHERE DATE_FORMAT(start_time, '%Y-%m-%d') BETWEEN :first_day AND :last_day
            AND facility_name = :facilityName
            AND status = '1'
            AND DATE_FORMAT(start_time, '%Y-%m-%d') >= CURDATE()
            ORDER BY shift_date ASC";


    $stmt = $pdo->prepare($sql);
    
    $stmt->bindParam(':first_day', $firstDay, PDO::PARAM_STR);
    $stmt->bindParam(':last_day', $lastDay, PDO::PARAM_STR);
    $stmt->bindParam(':facilityName', $facilityRow['facility_name'], PDO::PARAM_STR);

    $stmt->execute();
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($row);
?>