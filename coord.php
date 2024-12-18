<?php
    require 'Model/dbModel.php';
    // DB接続
    $pdo = dbConnect();
    $tmp = "";

    $sql = "SELECT map.coord AS coord, survey_responses.gomi AS gomi, COUNT(survey_responses.gomi) AS gomi_count
            FROM map
            INNER JOIN survey_responses
            ON map.area_id = survey_responses.areaid
            GROUP BY map.coord, survey_responses.gomi
            ORDER BY coord ASC, gomi_count DESC, survey_responses.gomi DESC;";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($row as $rows) {
        if($tmp != $rows['coord']) {
            $area = $rows['coord'];
            $level = $rows['gomi'];

            // 連想配列として$res[]に追加
            $res[] = [
                'area' => $area,
                'level' => $level
            ];
        }
        $tmp = $rows['coord'];
    }

    echo json_encode($res);
?>