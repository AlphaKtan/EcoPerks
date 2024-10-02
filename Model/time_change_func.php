<?php 
function getFacility_name($area) {
    // travel_dataからfacility_nameを取得
    $locationsql = "SELECT id, area_id, facility_name FROM travel_data WHERE area_id = :area_id";
    $locationstmt = $pdo->prepare($locationsql);
    $locationstmt->bindParam(':area_id', $area, PDO::PARAM_INT);
    $locationstmt->execute();
    $locationRow = $locationstmt->fetchAll(PDO::FETCH_ASSOC);
}

?>