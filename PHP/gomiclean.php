<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('db_connection.php');

// ユーザー名の確認
if (!isset($_SESSION['username'])) {
    $_SESSION['login_message'] = "ログインしてください。"; // メッセージをセッションに保存
    header('Location: message.php');
    exit;
}

$username = $_SESSION['username'];
$area_id = filter_input(INPUT_GET, 'location_id', FILTER_VALIDATE_INT);
$action = htmlspecialchars($_GET['action'], ENT_QUOTES, 'UTF-8');

// 無効なリクエストのチェック
if ($area_id === false || $action === null) {
    echo "無効なリクエストです。";
    exit;
}

// 現在の時間
$current_time = date("Y-m-d H:i:s");

// QRコードの有効期限を確認
$sql = "SELECT * FROM qr_codes 
        WHERE area_id = :area_id 
        AND username = :username 
        AND expiry_time > :current_time 
        AND used = 0
        ORDER BY generated_time DESC 
        LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':area_id', $area_id);
$stmt->bindParam(':username', $username);
$stmt->bindParam(':current_time', $current_time);
$stmt->execute();

$qrCode = $stmt->fetch();

if ($qrCode) {
    if ($action === 'start') {
        // ゴミ拾い開始のデータをログに保存
        $sql = "INSERT INTO trash_collection_logs (username, area_id, start_time) 
                VALUES (:username, :area_id, :start_time)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':area_id', $area_id);
        $stmt->bindParam(':start_time', $current_time);
        $stmt->execute();

        // QRコードを無効化
        $updateSql = "UPDATE qr_codes SET used = 1 WHERE id = :id";
        $updateStmt = $pdo->prepare($updateSql);
        $updateStmt->bindParam(':id', $qrCode['id']);
        $updateStmt->execute();

        echo "ゴミ拾いが地点 {$area_id} で開始されました！";
    } else {
        echo "無効なアクションです。";
    }
} else {
    echo "このQRコードは無効または期限切れです。";
}


