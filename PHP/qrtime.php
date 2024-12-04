<?php
session_start();
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
require_once('vendor/autoload.php');
require_once('../Model/dbModel.php');
$pdo = dbConnect();
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Color\Color;

// サーバーURLを設定
$baseUrl = 'localhost/ecoperks/php/gomiclean.php';
$timestamp = time();

// セッションからエリアIDと施設名を取得
$area_id = $_SESSION['area_id'] ?? null;
$facility_name = $_SESSION['facility_name'] ?? null;

if (!$area_id || !$facility_name) {
    echo "<h3>エリアまたは施設が選択されていません。</h3>";
    exit;
}

// 開始QRコードのURLを生成
$expiry_time = date('Y-m-d H:i:s', $timestamp + 60); // 60秒後のDATETIME形式に変換
$startUrl = $baseUrl . '?area_id=' . $area_id . '&facility_name=' . urlencode($facility_name) . '&action=start&expiry_time=' . urlencode($expiry_time);

function generateQrCode($url, $area_id, $expiry_time, $facility_name, $pdo) {
    // QRコードを生成
    $qrCode = QrCode::create($url)
        ->setEncoding(new Encoding('UTF-8'))
        ->setErrorCorrectionLevel(ErrorCorrectionLevel::Low)
        ->setSize(150)
        ->setMargin(25)
        ->setRoundBlockSizeMode(RoundBlockSizeMode::Margin)
        ->setForegroundColor(new Color(0, 0, 0));

    $writer = new PngWriter();
    $result = $writer->write($qrCode);

    // QRコードをデータベースに保存（施設名も追加）
    $sql = "INSERT INTO qr_codes (area_id, facility_name, expiry_time, used, generated_time) 
            VALUES (:area_id, :facility_name, :expiry_time, 0, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':area_id', $area_id);
    $stmt->bindParam(':facility_name', $facility_name);
    $stmt->bindParam(':expiry_time', $expiry_time);
    $stmt->execute();

    // Base64にエンコードして返す
    return '<img src="data:image/png;base64,' . base64_encode($result->getString()) . '" alt="QR Code">';
}

// QRコードのHTMLを出力
echo "<div><strong>ゴミ拾い開始用QRコード</strong></div>";
echo generateQrCode($startUrl, $area_id, $expiry_time, $facility_name, $pdo);



