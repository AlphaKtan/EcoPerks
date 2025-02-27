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
    
    switch ($status) {
        case 0:
            // 参加の時yoyakuのstatusをアップデート
            $sql = "UPDATE yoyaku
            SET status = 1
            WHERE
                username = :username AND
                area_id = :area_id AND
                location = :location AND
                reservation_date = CURDATE() AND
                start_time - INTERVAL 50 MINUTE <= NOW() AND
                start_time + INTERVAL 30 MINUTE > NOW()";

                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':username', $username, PDO::PARAM_INT);
                $stmt->bindParam(':area_id', $area_id, PDO::PARAM_INT);
                $stmt->bindParam(':location', $facilityRow['facility_name'], PDO::PARAM_STR);

                // 実行し、影響を受けた行数を確認
                $executed = $stmt->execute();
                echo json_encode('正常に完了1');
            break;

        case 1:
            // 参加終了の時yoyakuのstatusをアップデート
            $sql = "UPDATE yoyaku
            SET status = 2
            WHERE
                username = :username AND
                status = 1 AND
                area_id = :area_id AND
                location = :location AND
                reservation_date = CURDATE() AND
                end_time - INTERVAL 59 MINUTE <= NOW() AND
                end_time + INTERVAL 15 MINUTE > NOW()";

                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':username', $username, PDO::PARAM_INT);
                $stmt->bindParam(':area_id', $area_id, PDO::PARAM_INT);
                $stmt->bindParam(':location', $facilityRow['facility_name'], PDO::PARAM_STR);

                // 実行し、影響を受けた行数を確認
                $executed = $stmt->execute();
                echo json_encode('正常に完了2');
            break;
    }


?>

