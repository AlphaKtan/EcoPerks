<?php
    
    require '../Model/dbModel.php';
    
    // DB接続
    $db = dbConnect();

    // POST情報取得
    $area_id = $_POST["area"];
    $facility_id = $_POST["facility"];
    $date = $_POST["date"];

    // SQL成形
    $sql="SELECT tc.id,tc.areaid, tc.facility_name, tc.start_time, tc.end_time FROM time_change tc ";
    $sql.="INNER JOIN travel_data td ON tc.facility_name = td.facility_name WHERE 1 = 1 ";

    // エリアIDがあれば検索条件にエリアIDを追加
    if($area_id != ""){ $sql.="AND tc.areaid = :areaId ";}

    // 施設IDがあれば検索条件に施設IDを追加
    if($facility_id != ""){ $sql.="AND td.id = :facilityId ";}

    // 施設IDがあれば検索条件に施設IDを追加
    if($date != ""){ $sql.="AND tc.start_time BETWEEN :start_time AND :end_time ";}

    // 並び替え
    $sql.=" ORDER BY td.id,tc.start_time";

    // SQLセッティング
    $stmt = $db->prepare($sql);

    // SQLバインド
    if($area_id != ""){ $stmt->bindParam("areaId",$area_id,PDO::PARAM_INT);}

    if($facility_id != ""){ $stmt->bindParam("facilityId",$facility_id,PDO::PARAM_INT);}
    
    if($date != ""){ 
        $start_date = $date." 00:00:00";
        $end_date = $date." 23:59:59";
        $stmt->bindParam("start_time",$start_date);
        $stmt->bindParam("end_time",$end_date);
    }
    
    // SQL文を実行
    $res = $stmt->execute();

    // データが取得できなかった場合は空を返却
    if(!$res){ return "";} 

    // データが取得できた場合は取得結果を返却
    echo json_encode($stmt->fetchAll());

    exit;

?>