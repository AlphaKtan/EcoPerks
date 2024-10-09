<!-- 終了用QR -->
<?php
require_once('vendor/autoload.php');
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
$baseUrl = 'http://i2322117.chips.jp/php/gomiclean_end.php';
$timestamp = time();

//終了用QRコードのURLを生成

$endUrl = $baseUrl . '?location_id=10&action=end&time=' . $timestamp;

function generateQrCode($url) {
    $qrCode = QrCode::create($url)
        ->setEncoding(new Encoding('UTF-8'))
        ->setErrorCorrectionLevel(ErrorCorrectionLevel::Low)
        ->setSize(150)
        ->setMargin(25)
        ->setRoundBlockSizeMode(RoundBlockSizeMode::Margin)
        ->setForegroundColor(new Color(0, 0, 0));

    $writer = new PngWriter();
    $result = $writer->write($qrCode);
    
    // Base64にエンコードして返す
    return '<img src="data:image/png;base64,' . base64_encode($result->getString()) . '" alt="QR Code">';
}

// QRコードのHTMLを出力
echo "<div><strong>ゴミ拾い終了用QRコード</strong></div>";
echo generateQrCode($endUrl);



