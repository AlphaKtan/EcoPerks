<?php
session_start();

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
require_once('db_connection.php');

// ユーザー名の確認
if (!isset($_SESSION['username'])) {
    $_SESSION['login_message'] = "ログインしてください。"; // メッセージをセッションに保存
    header('Location: message.php');
    exit;
}

$username = $_SESSION['username'];
$location_id = 10; // ハードコーディング
$action = htmlspecialchars($_GET['action'] ?? '', ENT_QUOTES, 'UTF-8'); // actionを取得
$expiry_time = htmlspecialchars($_GET['expiry_time'] ?? '', ENT_QUOTES, 'UTF-8'); // expiry_timeを取得

if (!$expiry_time) {
    echo "無効な expiry_time です。";
    exit;
}

// 現在の時間
$current_datetime = date('Y-m-d H:i:s'); // 現在時刻をDATETIME形式に変換

// 現在の時刻をデバッグ用に表示
// echo "現在の日時: " . $current_datetime . "<br>"; // デバッグ用

// QRコードの有効期限を確認
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
// error_log(var_export($qrCode, true), 3, "./debug.txt");
// error_log(var_export($current_datetime, true), 3, "./debug.txt");


// 取得したQRコードの内容を確認
// echo '<pre>';
// print_r($qrCode); // 取得したデータを確認
// echo "現在の日時: " . $current_datetime . "<br>"; // 現在の日時
// echo "クエリ条件: area_id: $location_id, expiry_time > $current_datetime, used = 0<br>";
// echo '</pre>';

// QRコードが見つからなかった場合、または期限切れの場合に used を1に更新
// QRコードが見つからなかった場合、または期限切れの場合に used を1に更新
if (!$qrCode) {
    // 期限切れの場合、used を 1 に更新する
    $updateSql = "UPDATE qr_codes SET used = 1 WHERE area_id = :area_id AND expiry_time <= :current_time";
    $updateStmt = $pdo->prepare($updateSql);
    $updateStmt->bindParam(':area_id', $location_id);
    $updateStmt->bindParam(':current_time', $current_datetime);
    $updateStmt->execute();
    
    echo "このQRコードは無効または期限切れです。";
    
    exit; // 処理を終了

}
// QRコードが見つかった場合
if ($qrCode) {
    
    if ($action === 'start') {
        // ゴミ拾い開始のデータをログに保存
        $sql = "INSERT INTO cleaning_records (username, area_id, start_time) 
                VALUES (:username, :area_id, :start_time)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':area_id', $location_id); // location_idを使用
        $stmt->bindParam(':start_time', $current_datetime);
        $stmt->execute();

        // QRコードを無効化
        $updateSql = "UPDATE qr_codes SET used = 1 WHERE id = :id";
        $updateStmt = $pdo->prepare($updateSql);
        $updateStmt->bindParam(':id', $qrCode['id']);
        $updateStmt->execute();

        echo "ゴミ拾いが地点 {$location_id} で開始されました！"; // location_idを使用
    } else {
        echo "無効なアクションです。";
    }
}

