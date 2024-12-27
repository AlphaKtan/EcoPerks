<?php 

    require '../Model/dbModel.php';
    
    // DB接続
    $pdo = dbConnect();

    if ($_POST["username"]) {
        $username = $_POST["username"];
    }

    if ($_POST["area_id"]) {
        $area_id = $_POST["area_id"];
    }

    if ($_POST["location"]) {
        $facilityName = $_POST["location"];
            $facilitySql = "SELECT facility_name FROM travel_data WHERE id = :facility";
            $facilityStmt = $pdo->prepare($facilitySql);
            $facilityStmt->bindParam(':facility', $facilityName, PDO::PARAM_STR);
            $facilityStmt->execute();
            $facilityRow = $facilityStmt->fetch(PDO::FETCH_ASSOC);
    }

    if (isset($_POST['status'])) {
        $status = $_POST['status'];
    } else {
        $status = null; // 適切なデフォルト値を設定
    }
    $sql = "SELECT COUNT(area_id) AS count, reservation_date, area_id, start_time, end_time, status
            FROM yoyaku
            WHERE
                username = :username AND
                area_id = :area_id AND
                location = :location AND
                reservation_date = CURDATE() AND
                end_time - INTERVAL 40 MINUTE >= NOW() AND
                end_time + INTERVAL 15 MINUTE <= NOW()";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username, PDO::PARAM_INT);
    $stmt->bindParam(':area_id', $area_id, PDO::PARAM_INT);
    $stmt->bindParam(':location', $facilityRow['facility_name'], PDO::PARAM_STR);

    // $executed = $stmt->execute();
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $results[] = $row;

    echo json_encode($results);

?>