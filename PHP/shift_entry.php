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
            $insertSql = "INSERT INTO test_time_change (start_time, end_time, facility_name, areaid, status)
            VALUE (:start_time, :end_time, :facility_name, :area_id, :status)";
        
            $insertStmt = $pdo->prepare($insertSql);

            foreach ($row as $rows) {
                $startTime = $rows['start_time'];
                $start_time = "$date $startTime ";

                $endTime = $rows['end_time'];
                $end_time = "$date $endTime ";

                $facilityName = 'テスト';
                $areaId = 1;
                $status = '0';

                $insertStmt->bindParam(':start_time', $start_time, PDO::PARAM_STR);
                $insertStmt->bindParam(':end_time', $end_time, PDO::PARAM_STR);
                $insertStmt->bindParam(':facility_name', $facilityName, PDO::PARAM_STR);
                $insertStmt->bindParam(':area_id', $areaId, PDO::PARAM_INT);
                $insertStmt->bindParam(':status', $status, PDO::PARAM_INT);

                $insertStmt->execute();
            }
            $results[] = "正常に完了";
        } else {
            $results[] = "プリセットデータが見つかりませんでした";
        }

        echo json_encode($results);
    }
?>


