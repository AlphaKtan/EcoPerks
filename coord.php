<?php
    require 'Model/dbModel.php';
    // DB接続
    $pdo = dbConnect();

    $sql = "SELECT map.coord,survey_responses.gomi
            FROM map 
            INNER JOIN survey_responses
            ON map.area_id = survey_responses.areaid; ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($row);
?>