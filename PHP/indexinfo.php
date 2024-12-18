<?php 

    require '../Model/dbModel.php';
    
    // DB接続
    $pdo = dbConnect();

    
    if(isset($_POST["area_id"])) {
        $area_id = $_POST["area_id"];
    }
    
    $sql = "SELECT area_id, facility_name, romaji
            FROM travel_data
            WHERE area_id  = :area_id";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':area_id', $area_id, PDO::PARAM_INT);

    $stmt->execute();

    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($row);

?>