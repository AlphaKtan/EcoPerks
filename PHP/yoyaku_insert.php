<?php
    require '../Model/dbModel.php';

    // DB接続
    $pdo = dbConnect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    

    // 予約の削除
    $stmt = $pdo->prepare("INSERT INTO yoyaku() VALUES()");
    $stmt->bindParam(':id', $_POST['reservation_id'], PDO::PARAM_INT);
    $stmt->execute();

    // 予約フォームにリダイレクト
    header("Location: ../index.html");
    exit();

?>