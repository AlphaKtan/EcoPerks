<?php

    require '../Model/dbModel.php';

    // DB接続
    $db = dbConnect();

    // POST情報取得
    $area_id = $_POST["area"];
    
    // SQLセッティング
    $stmt = $db->prepare('SELECT * FROM travel_data WHERE area_id = :area_id;');
    $stmt->bindParam("area_id",$area_id,PDO::PARAM_INT);
    
    // SQL文を実行
    $res = $stmt->execute();

    // データが取得できなかった場合は空を返却
    if(!$res){ return "";} 

    // データが取得できた場合は取得結果を返却
    echo json_encode($stmt->fetchAll());
    
    exit;

?>