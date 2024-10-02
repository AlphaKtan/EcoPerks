<?php
function getFacility_name($pdo,$area) {
    // travel_dataからfacility_nameを取得
    $locationsql = "SELECT id, area_id, facility_name FROM travel_data WHERE area_id = :area_id";
    $locationstmt = $pdo->prepare($locationsql);
    $locationstmt->bindParam(':area_id', $area, PDO::PARAM_INT);
    $locationstmt->execute();
    return $locationstmt->fetchAll(PDO::FETCH_ASSOC);
}

function timeDisplay($pdo, ) {
    // 各facility_nameごとに関連するtime_changeのデータを取得
    $sql = "SELECT status, start_time, end_time, facility_name FROM time_change WHERE facility_name = :facility_name AND areaid = :area_id AND status = :status";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':facility_name', $k['facility_name'], PDO::PARAM_STR);
    $stmt->bindParam(':area_id', $area, PDO::PARAM_INT);
    $stmt->bindParam(':status', $status, PDO::PARAM_INT);
    $stmt->execute();
    $areaRows = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>