<?php
session_start();
// require_once('db_connection.php');
require_once('db_local.php'); // データベース接続ファイル

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
        // ゴミ拾い開始の場合
        if ($action === 'start') {
            $sql = "INSERT INTO cleaning_records (username, area_id, start_time) VALUES (:username, :area_id, :start_time)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':area_id', $area_id);
            $stmt->bindParam(':start_time', $current_time);
            $stmt->execute();

            echo "ゴミ拾いが地点 $area_id で開始されました！";
        } else {
            echo "無効なアクションです。";
        }
    } catch (PDOException $e) {
        echo "エラーが発生しました: " . $e->getMessage();
    }
} else {
    echo "QRコードの情報が不完全です。";
}


