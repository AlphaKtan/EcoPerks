<?php
require '../Model/dbModel.php';
// DB接続
$pdo = dbConnect();
$tmp = "";

$sql = "SELECT area_id, coord
        FROM map";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($row);
?>