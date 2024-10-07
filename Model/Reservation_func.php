<?php
//予約を登録
function reservationEntry($pdo, $user_id, $reservation_date, $area_id, $radio_start_time, $radio_end_time, $facility_name) {
    $sql = "INSERT INTO yoyaku (username, reservation_date, area_id, start_time, end_time, location) 
    VALUES (:username, :reservation_date, :area_id,:start_time, :end_time, :facility_name)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':reservation_date', $reservation_date, PDO::PARAM_STR);
    $stmt->bindParam(':area_id', $area_id, PDO::PARAM_INT);
    $stmt->bindParam(':start_time', $radio_start_time, PDO::PARAM_STR);
    $stmt->bindParam(':end_time', $radio_end_time, PDO::PARAM_STR);
    $stmt->bindParam(':facility_name', $facility_name, PDO::PARAM_STR);
    return $stmt->execute();
}

// エリア情報の取得
function getArea($pdo, $location) {
    $locationSql = "SELECT id,area_id, facility_name, address FROM travel_data WHERE id = :location";
    $newStmt = $pdo->prepare($locationSql);
    $newStmt->bindParam(':location', $location, PDO::PARAM_INT);
    $newStmt->execute();
    return $newStmt->fetch(PDO::FETCH_ASSOC);
}

// 時間帯の取得
function getTimeAll($pdo, $reservation_date, $facility_name, $status) {
    $timeSql = "SELECT DATE_FORMAT(start_time, '%H') AS hour_only, DATE_FORMAT(end_time, '%H') AS hour_only_end FROM time_change
    WHERE DATE_FORMAT(start_time, '%Y-%m-%d') = :reservation_date AND facility_name = :facility_name AND status = :status";
    $timeStmt = $pdo->prepare($timeSql);
    $timeStmt->bindParam(':reservation_date', $reservation_date, PDO::PARAM_STR);
    $timeStmt->bindParam(':facility_name', $facility_name, PDO::PARAM_STR);
    $timeStmt->bindParam(':status', $status, PDO::PARAM_INT);
    $timeStmt->execute();
    return $timeStmt->fetchAll(PDO::FETCH_ASSOC);
}
?>