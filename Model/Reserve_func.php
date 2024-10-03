<?php
    function ReserveAreaID($pdo, $selected_area){
        // 選択されたエリアIDに基づいてデータベースから施設情報を取得
        $yoyakusql = "SELECT username, area_id, reservation_date, start_time, end_time, location FROM yoyaku WHERE area_id = :area_id";
        $stmt = $pdo->prepare($yoyakusql);
        $stmt->bindParam(':area_id', $selected_area, PDO::PARAM_INT);
        $stmt->execute();

        // 結果を取得
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
?>