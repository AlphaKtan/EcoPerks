<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
    require '../Model/dbModel.php';
    
    // DB接続
    $pdo = dbConnect();
    if ($_POST["date"]) {
        $date = $_POST["date"];
    } 

    if ($_POST["area"]) {
        $areaId = $_POST["area"];
    } 

    if ($_POST["facility"]) {
        $facilityName = $_POST["facility"];
            $facilitySql = "SELECT facility_name FROM travel_data WHERE id = :facility";
            $facilityStmt = $pdo->prepare($facilitySql);
            $facilityStmt->bindParam(':facility', $facilityName, PDO::PARAM_INT);
            $facilityStmt->execute();
            $facilityRow = $facilityStmt->fetch(PDO::FETCH_ASSOC);
    }

    if ($_POST["price"]) {
        $price = $_POST["price"];
    } 

    if ($_POST["presets"]) {
    // POST情報取得
        $i = 0;
        $presetNumber = $_POST["presets"];

        foreach ($presetNumber as $presetID) {
            $names[] = $presetID;
        }
        $inClause = substr(str_repeat(',?', count($names)), 1);

        $sql = sprintf("SELECT start_time, end_time FROM preset WHERE id IN (%s)", $inClause);
        $stmt = $pdo->prepare($sql);
        $stmt->execute($names);
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // $results[] = $row;
        if ($row) {
            // INSERT前に重複チェック
            $insertSql = "INSERT INTO time_change (start_time, end_time, facility_name, areaid, price, status)
                          SELECT :start_time, :end_time, :facility_name, :area_id, :price, :status
                          WHERE NOT EXISTS (
                              SELECT 1 FROM time_change 
                              WHERE start_time = :start_time 
                              AND end_time = :end_time
                              AND facility_name = :facility_name
                              AND areaid = :area_id
                              AND price = :price
                          )";
        
            $insertStmt = $pdo->prepare($insertSql);
            $duplicates = false;
            $duplicateDetails = [];

            foreach ($row as $rows) {
                $startTime = $rows['start_time'];
                $start_time = "$date $startTime ";

                $endTime = $rows['end_time'];
                $end_time = "$date $endTime ";

                $status = '1';

                $insertStmt->bindParam(':start_time', $start_time, PDO::PARAM_STR);
                $insertStmt->bindParam(':end_time', $end_time, PDO::PARAM_STR);
                $insertStmt->bindParam(':facility_name', $facilityRow['facility_name'], PDO::PARAM_STR);
                $insertStmt->bindParam(':area_id', $areaId, PDO::PARAM_INT);
                $insertStmt->bindParam(':status', $status, PDO::PARAM_INT);
                $insertStmt->bindParam(':price', $price, PDO::PARAM_INT);

                $insertStmt->execute();

                // もしデータが挿入されていなかった場合（重複していた場合）
                if ($insertStmt->rowCount() == 0) {
                    $duplicates = true;
                    // 時間のみにした
                    $endTime = substr($end_time, 10);
                    // 重複シフトの詳細を追加
                    $duplicateDetails[] = "$start_time ～ $endTime " . $facilityRow['facility_name'];
                }
            }

            if ($duplicates) {
                // 重複シフトの詳細をメッセージに追加
                $results[] = "以下のシフトが重複しています<br>" . implode("<br>", $duplicateDetails);
            } else {
                $results[] = "正常に完了";
            }
        } else {
            $results[] = "プリセットデータが見つかりませんでした";
        }

        echo json_encode($results);
    }
?>


