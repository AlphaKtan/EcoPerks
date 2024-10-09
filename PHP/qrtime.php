<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('vendor/autoload.php');
require_once('db_connection.php');
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
$baseUrl = 'http://i2322117.chips.jp/php/gomiclean.php';
$timestamp = time();
// 開始QRコードのURLを生成
$expiry_time = date('Y-m-d H:i:s', $timestamp + 60); // 60秒後のDATETIME形式に変換
$startUrl = $baseUrl . '?location_id=10&action=start&expiry_time=' . urlencode($expiry_time);

// 開始QRコードのURLを生成
$location_id = 10; // location_idをここで定義
$startUrl = $baseUrl . '?location_id=' . $location_id . '&action=start&expiry_time=' . $expiry_time;

function generateQrCode($url, $location_id, $expiry_time, $pdo) {
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

    
    // QRコードをデータベースに保存
    $sql = "INSERT INTO qr_codes (area_id, expiry_time, used, generated_time) 
    VALUES (:area_id, :expiry_time, 0, NOW())"; 
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':area_id', $location_id);
    $stmt->bindParam(':expiry_time', $expiry_time); 
    $stmt->execute();

    // Base64にエンコードして返す
    return '<img src="data:image/png;base64,' . base64_encode($result->getString()) . '" alt="QR Code">';
}

// QRコードのHTMLを出力
echo "<div><strong>ゴミ拾い開始用QRコード</strong></div>";
echo generateQrCode($startUrl, $location_id, $expiry_time, $pdo);


