<?php
// test_output.php
    require './Model/dbModel.php';

    // DB接続
    $pdo = dbConnect();

    if (isset($_POST['keyword'])) {
        $keyword = $_POST['keyword'];
    } else {
        $keyword = "なにもない";
    }

    $sql = "SELECT id, facility_name, area_id, romaji, kana
            FROM `demo_travel_data`
            WHERE `facility_name` COLLATE utf8mb4_unicode_ci LIKE :keyword
            OR `romaji` COLLATE utf8mb4_unicode_ci LIKE :keyword
            OR `kana` COLLATE utf8mb4_unicode_ci LIKE :keyword
            ORDER BY area_id ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':keyword', '%' . $keyword . '%', PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll();
    if (isset($results)) {
        echo json_encode($results);
    } else {
        $no = "何もない";
        echo json_encode("$no");
    }
    
?>
