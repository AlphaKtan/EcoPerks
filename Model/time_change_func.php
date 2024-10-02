<?php
// travel_dataからfacility_nameを取得
function getFacilityNames($pdo, $area = null) {
    if ($area !== null) {
        // area_idが指定されている場合
        $locationsql = "SELECT id, area_id, facility_name FROM travel_data WHERE area_id = :area_id";
        $locationstmt = $pdo->prepare($locationsql);
        $locationstmt->bindParam(':area_id', $area, PDO::PARAM_INT);
    } else {
        // area_idが指定されていない場合（全件取得）
        $locationsql = "SELECT id, area_id, facility_name FROM travel_data";
        $locationstmt = $pdo->prepare($locationsql);
    }
    $locationstmt->execute();
    return $locationstmt->fetchAll(PDO::FETCH_ASSOC);
}

// 各facility_nameごとに関連するtime_changeのデータを取得
function timeDisplay($pdo, $facility_name, $status, $area = null) {
    if ($area !== null) {
        // area_idが指定されている場合
        $sql = "SELECT status, start_time, end_time, facility_name FROM time_change WHERE facility_name = :facility_name AND areaid = :area_id AND status = :status";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':facility_name', $facility_name, PDO::PARAM_STR);
        $stmt->bindParam(':area_id', $area, PDO::PARAM_INT);
        $stmt->bindParam(':status', $status, PDO::PARAM_INT);
    } else {
        // area_idが指定されていない場合（全件取得）
        $sql = "SELECT status, start_time, end_time, facility_name FROM time_change WHERE facility_name = :facility_name AND status = :status";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':facility_name', $facility_name, PDO::PARAM_STR);
        $stmt->bindParam(':status', $status, PDO::PARAM_INT);
    }
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function  outputJson($noDataFound, $jsonArray) {
    if (!$noDataFound) {
        echo json_encode($jsonArray, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    } else {
        echo '該当する時間のデータがありません。<br>';
    }
}

?>