<?php
session_start();

require_once('db_connection.php'); // データベース接続ファイル

// ユーザー名の確認
if (!isset($_SESSION['username'])) {
    $_SESSION['login_message'] = "ログインしてください。"; // メッセージをセッションに保存
    header('Location: message.php');
    exit;
}

$username = $_SESSION['username'];
$area_id = htmlspecialchars($_GET['area_id'] ?? '', ENT_QUOTES, 'UTF-8'); // area_idを取得
$action = htmlspecialchars($_GET['action'] ?? '', ENT_QUOTES, 'UTF-8'); // actionを取得
$expiry_time = htmlspecialchars($_GET['expiry_time'] ?? '', ENT_QUOTES, 'UTF-8'); // expiry_timeを取得

if (!$expiry_time) {
    echo "<h3>無効な expiry_time です。</h3>";
    exit;
}

// 現在の時間
$current_datetime = date('Y-m-d H:i:s'); // 現在時刻をDATETIME形式に変換

// QRコードの有効期限を確認
$sql = "SELECT * FROM qr_codes 
        WHERE area_id = :area_id 
        AND username = ''  
        AND expiry_time > :current_time
        AND used = 0
        ORDER BY generated_time DESC 
        LIMIT 1";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':area_id', $location_id); // location_idを使用
$stmt->bindParam(':current_time', $current_datetime); // 現在時刻を使用
$stmt->execute();

$qrCode = $stmt->fetch(); // QRコードを取得

// QRコードが見つからなかった場合
if (!$qrCode) {
    // 期限切れの場合、used を 1 に更新する
    $updateSql = "UPDATE qr_codes SET used = 1 WHERE area_id = :area_id AND expiry_time <= :current_time";
    $updateStmt = $pdo->prepare($updateSql);
    $updateStmt->bindParam(':area_id', $location_id);
    $updateStmt->bindParam(':current_time', $current_datetime);
    $updateStmt->execute();
    
    echo "<h3>このQRコードは無効または期限切れです。</h3>";
    exit;
}

// QRコードが見つかった場合の終了処理
if ($qrCode && $action === 'end') {
    // ゴミ拾い終了のデータをログに保存
    $sql = "UPDATE cleaning_records 
            SET end_time = :end_time 
            WHERE username = :username 
            AND area_id = :area_id 
            AND end_time IS NULL";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':area_id', $location_id);
    $stmt->bindParam(':end_time', $current_datetime);
    $stmt->execute();

    // QRコードを無効化
    $updateSql = "UPDATE qr_codes SET used = 1 WHERE id = :id";
    $updateStmt = $pdo->prepare($updateSql);
    $updateStmt->bindParam(':id', $qrCode['id']);
    $updateStmt->execute();

    echo "<h3>ゴミ拾いが地点 {$location_id} で終了しました！</h3>";
} else {
    echo "<h3>無効なアクションです。</h3>";
}


