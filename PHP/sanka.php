<?php
require_once('vendor/autoload.php');
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;



// サーバーURLを設定（開始用と終了用で異なる）
$baseUrl = 'http://i2322117.chips.jp/php/gomiclean.php';

// ゴミ拾い開始用QRコードのURL
$startUrl = $baseUrl . '?location_id=10&action=start';

// ゴミ拾い終了用QRコードのURL
$endUrl = $baseUrl . '?location_id=10&action=end';

// QRコード生成関数
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

    // Base64にエンコードしてQRコードを表示
    return '<img src="data:image/png;base64,' . base64_encode($result->getString()) . '" alt="QR Code">';
}

// ゴミ拾い開始用QRコード
echo "<h3>ゴミ拾い開始用QRコード</h3>";
echo generateQrCode($startUrl);

// ゴミ拾い終了用QRコード
echo "<h3>ゴミ拾い終了用QRコード</h3>";
echo generateQrCode($endUrl);


