<?php
session_start();
// require_once('db_connection.php');
require_once('db_connection.php'); // データベース接続ファイル

if (!isset($_SESSION['username'])) {
    header('Location: ../login.html');
    exit;
}

// ログイン中のユーザー名を取得
$username = $_SESSION['username'];

// QRコードからの情報を取得
if (isset($_GET['location_id']) && isset($_GET['action'])) {
    $area_id = $_GET['location_id']; // QRコードに含まれる地点ID（area_id）
    $action = $_GET['action'];
    $current_time = date("Y-m-d H:i:s");

    try {
        // ゴミ拾い終了の場合
        if ($action === 'end') {
            $sql = "UPDATE cleaning_records SET end_time = :end_time WHERE username = :username AND area_id = :area_id AND end_time IS NULL";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':area_id', $area_id);
            $stmt->bindParam(':end_time', $current_time);
            $stmt->execute();

            echo "ゴミ拾いが地点 $area_id で終了しました！";
            header("Location: ");
        } else {
            echo "無効なアクションです。";
        }
    } catch (PDOException $e) {
        echo "エラーが発生しました: " . $e->getMessage();
    }
} else {
    echo "QRコードの情報が不完全です。";
}



