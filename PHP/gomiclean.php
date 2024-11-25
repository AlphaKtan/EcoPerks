<?php
session_start();

require_once('../Model/dbModel.php');
$pdo = dbConnect();

if (!isset($_SESSION['username'])) {
    $_SESSION['login_message'] = "ログインしてください。";
    header('Location: message.php');
    exit;
}

$username = $_SESSION['username'];
$area_id = htmlspecialchars($_GET['area_id'] ?? '', ENT_QUOTES, 'UTF-8');
$action = htmlspecialchars($_GET['action'] ?? '', ENT_QUOTES, 'UTF-8');
$expiry_time = htmlspecialchars($_GET['expiry_time'] ?? '', ENT_QUOTES, 'UTF-8');

if (!$expiry_time) {
    echo "<h3>無効な expiry_time です。</h3>";
    exit;
}

$current_datetime = date('Y-m-d H:i:s');

// QRコードを取得するためのクエリ
$sql = "SELECT * FROM qr_codes 
        WHERE area_id = :area_id 
        AND username = ''  
        AND used = 0
        ORDER BY generated_time DESC 
        LIMIT 1";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':area_id', $area_id);
$stmt->execute();

$qrCode = $stmt->fetch();

if (!$qrCode) {
    echo "<h3>無効なQRコードです。</h3>";
    echo "<p>エリアID: {$area_id}</p>";
    echo "<p>使用フラグ: 0</p>";
    echo "<p>現在の時刻: {$current_datetime}</p>";
    exit;
}

// QRコードの有効期限が切れているかを確認
if ($qrCode['expiry_time'] <= $current_datetime) {
    echo "<h3>このQRコードは期限切れです。有効期限は " . htmlspecialchars($qrCode['expiry_time'], ENT_QUOTES, 'UTF-8') . " でした。</h3>";

    // 期限切れの場合、used を 1 に更新する
    $updateSql = "UPDATE qr_codes SET used = 1 WHERE id = :id";
    $updateStmt = $pdo->prepare($updateSql);
    $updateStmt->bindParam(':id', $qrCode['id']);
    $updateStmt->execute();

    exit;
}

if ($action === 'start') {
    // ゴミ拾い開始のデータをログに保存
    $sql = "INSERT INTO cleaning_records (username, area_id, start_time) 
            VALUES (:username, :area_id, :start_time)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':area_id', $area_id);
    $stmt->bindParam(':start_time', $current_datetime);
    $stmt->execute();

    // QRコードを無効化
    $updateSql = "UPDATE qr_codes SET used = 1 WHERE id = :id";
    $updateStmt = $pdo->prepare($updateSql);
    $updateStmt->bindParam(':id', $qrCode['id']);
    $updateStmt->execute();

    echo "<h3>ゴミ拾いが地点 {$area_id} で開始されました！</h3>";
} else {
    echo "<h3>無効なアクションです。</h3>";
}

