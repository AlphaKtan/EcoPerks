<?php
    session_start(); // セッションを開始
    require '../Model/dbModel.php';

    // DB接続
    $pdo = dbConnect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $_SESSION['reservation_id'] = $_POST['reservation_id'];

        // 予約の削除
        $stmt = $pdo->prepare("DELETE FROM yoyaku WHERE id = :id");
        $stmt->bindParam(':id', $_POST['reservation_id'], PDO::PARAM_INT);
        $stmt->execute();

        // 予約フォームにリダイレクト
        header("Location: ../index.php");
        exit();
    }
?>