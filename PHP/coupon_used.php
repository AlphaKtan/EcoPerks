<?php
session_start();

// クーポンが発行されていない場合は戻す
if (!isset($_SESSION['coupon_code'])) {
    header('Location: index.php'); // クーポン発行画面にリダイレクト
    exit;
}

// 使用済みのフラグを設定
$_SESSION['coupon_used'] = true;

$couponCode = $_SESSION['coupon_code'];
$expiryDate = $_SESSION['expiry_date'];

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../CSS/indexStyle.css">
    <link rel="stylesheet" href="../CSS/coupons.css">
    <title>クーポン使用済み</title>
</head>
<body>

<div class="coupon-container">
    <div class="coupon-message">
        <h3>クーポンを使用しました！</h3>
    </div>

    <div class="coupon used">
        <p class="discount">¥50 OFF</p>
        <p class="coupon-code">クーポンコード: <?= htmlspecialchars($couponCode, ENT_QUOTES, 'UTF-8'); ?></p>
        <p class="expiry-date">有効期限: <?= htmlspecialchars($expiryDate, ENT_QUOTES, 'UTF-8'); ?></p>
        <p class="used-message">このクーポンは使用済みです。</p>
    </div>
</div>

</body>
</html>
