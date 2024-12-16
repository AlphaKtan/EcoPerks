<?php 

    require '../Model/dbModel.php';
    
    // DB接続
    $pdo = dbConnect();

    if ($_POST["username"]) {
        $username = $_POST["username"];
    } else {
        $username = 1;
    }

    if ($_POST["area_id"]) {
        $area_id = $_POST["area_id"];
    }

    if ($_POST["location"]) {
        $location = $_POST["location"];
    }

    if ($_POST["facility"]) {
        $facilityName = $_POST["facility"];
            $facilitySql = "SELECT facility_name FROM travel_data WHERE id = :facility";
            $facilityStmt = $pdo->prepare($facilitySql);
            $facilityStmt->bindParam(':facility', $facilityName, PDO::PARAM_STR);
            $facilityStmt->execute();
            $facilityRow = $facilityStmt->fetch(PDO::FETCH_ASSOC);
    }
        
    // yoyakuのstatusをアップデート
    $sql = "UPDATE yoyaku
            SET status = 1
            WHERE
                username = :username AND
                area_id = :area_id AND
                location = :location AND
                reservation_date = CURDATE() AND
                start_time - INTERVAL 15 MINUTE <= NOW() AND
                start_time + INTERVAL 30 MINUTE > NOW()";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username, PDO::PARAM_INT);
    $stmt->bindParam(':area_id', $area_id, PDO::PARAM_INT);
    $stmt->bindParam(':location', $facilityRow['facility_name'], PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $results[] = "正常に完了";
    echo json_encode($results);

?>


