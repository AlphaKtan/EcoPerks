<?php
require_once( 'vendor/autoload.php');
//ここは絶対に変更してはいけない
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




// URL設定
$url = 'http://i2322117.chips.jp/login.html';//自分のに置き換え

// QrCodeに関する設定
$qrCode = QrCode::create($url)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(ErrorCorrectionLevel::Low)
            ->setSize(150)
            ->setMargin(25)
            ->setRoundBlockSizeMode(RoundBlockSizeMode::Margin)
            ->setForegroundColor(new Color(0, 0, 0));


$writer = new PngWriter();
$result = $writer->write($qrCode);

// Base64??????
$qrCodeBase64 = base64_encode($result->getString());

// QRコードのimg
$qrCodeImg = '<img src="data:image/gif;base64,'.$qrCodeBase64.'" alt="QR Code" style="width:1200px;">';


echo $qrCodeImg;


