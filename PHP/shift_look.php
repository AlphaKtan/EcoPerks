<?php 

    require '../Model/dbModel.php';
    
    // DB接続
    $pdo = dbConnect();

    if ($_POST["reservation_date"]) {
        $reservation_date = $_POST['reservation_date'];
    }

    if ($_POST["facility"]) {
        $facilityName = $_POST["facility"];
            $facilitySql = "SELECT facility_name FROM travel_data WHERE id = :facility";
            $facilityStmt = $pdo->prepare($facilitySql);
            $facilityStmt->bindParam(':facility', $facilityName, PDO::PARAM_INT);
            $facilityStmt->execute();
            $facilityRow = $facilityStmt->fetch(PDO::FETCH_ASSOC);
    }
        
    // シフトが入っている日を出す
    $sql = "SELECT start_time, end_time, facility_name, areaid FROM test_time_change WHERE status = '1' AND facility_name = :facilityName AND status = '1' ORDER BY start_time ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':facilityName', $facilityRow['facility_name'], PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $results[] = $row;

    echo json_encode($results);

?>