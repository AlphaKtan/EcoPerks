<?php
session_start();
require_once('db_connection.php');

// ユーザー名の確認
if (!isset($_SESSION['username'])) {
    header('Location: ../login.html');
    //echo "ログインが必要です。";
    exit;
}

$username = $_SESSION['username'];
$area_id = $_GET['location_id'];
$action = $_GET['action'];

// 現在の時間
$current_time = date("Y-m-d H:i:s");

// QRコードの有効期限を確認
$sql = "SELECT * FROM qr_codes WHERE area_id = :area_id AND username = :username ORDER BY generated_time DESC LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':area_id', $area_id);
$stmt->bindParam(':username', $username);
$stmt->execute();

$qrCode = $stmt->fetch();

if ($qrCode && strtotime($qrCode['expiry_time']) > strtotime($current_time)) {
    if ($action === 'start') {
        echo "ゴミ拾いが地点 {$area_id} で開始されました！";
        // ゴミ拾い開始処理を追加
    } else {
        echo "無効なアクションです。";
    }
} else {
    echo "このQRコードは無効または期限切れです。";
}
