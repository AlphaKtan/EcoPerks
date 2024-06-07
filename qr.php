<?php
require_once('vendor/autoload.php');

// 必須のライブラリをインポート
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

// URL設定（2FAスキップのためのパラメータを含む）
$url = 'http://i2322117.chips.jp/login.html?skip2fa=true'; // 自分のURLに置き換え

// QrCodeに関する設定
$qrCode = QrCode::create($url)
    ->setEncoding(new Encoding('UTF-8'))
    ->setErrorCorrectionLevel(new ErrorCorrectionLevel(ErrorCorrectionLevel::LOW))
    ->setSize(150)
    ->setMargin(25)
    ->setRoundBlockSizeMode(new RoundBlockSizeMode(RoundBlockSizeMode::MARGIN))
    ->setForegroundColor(new Color(0, 0, 0));

// PngWriterでQrCodeを作成
$writer = new PngWriter();
$result = $writer->write($qrCode);

// Base64にエンコード
$qrCodeBase64 = base64_encode($result->getString());

// QRコードをimgタグとして表示
$qrCodeImg = '<img src="data:image/png;base64,' . $qrCodeBase64 . '" alt="QR Code" style="width:150px;">';

// QRコードを出力
echo $qrCodeImg;
?>
