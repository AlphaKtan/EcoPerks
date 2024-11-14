<?php 
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
            $insertSql = "INSERT INTO preset WHERE i";
            $insertStmt = $pdo->prepare($insertSql);
            $insertStmt->execute();
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        echo json_encode($row);
    }
?>


