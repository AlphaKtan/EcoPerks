<?php
session_start();

require_once('../Model/dbModel.php');
$pdo = dbConnect();
//$_SESSION['username'] = 'やー';
if (!isset($_SESSION['username'])) {
    $_SESSION['login_message'] = "ログインしてください。";
    header('Location: message.php');
    exit;
}

$username = $_SESSION['username'];

// クーポンが発行されているか確認し、未定義の場合は初期化
// if (!isset($_SESSION['coupon_issued'])) {
//     $_SESSION['coupon_issued'] = false;
// 



// ----------------------------------------------------------------------------------------------------------------------

// ここにクーポン発行資格の判定

// ----------------------------------------------------------------------------------------------------------------------


// クーポンコードを生成する関数
function generateSequentialCouponCode($pdo) {
    try {
        $lastCouponSql = "SELECT coupon_code FROM coupons ORDER BY coupon_id DESC LIMIT 1";
        $lastCouponStmt = $pdo->query($lastCouponSql);
        $lastCoupon = $lastCouponStmt->fetchColumn();

        $prefix = "AA";
        $startNumber = 1;
        $numberLength = 8;
        $maxNumber = str_pad('', $numberLength, '9');

        if ($lastCoupon) {
            $alphabetPart = substr($lastCoupon, -2);
            $numberPart = (int)substr($lastCoupon, 2, $numberLength);

            if ($numberPart >= (int)$maxNumber) {
                $alphabetPart = incrementAlphabet($alphabetPart);
                $newNumber = str_pad($startNumber, $numberLength, '0', STR_PAD_LEFT);
            } else {
                $newNumber = str_pad($numberPart + 1, $numberLength, '0', STR_PAD_LEFT);
            }
        } else {
            $alphabetPart = $prefix;
            $newNumber = str_pad($startNumber, $numberLength, '0', STR_PAD_LEFT);
        }

        return $prefix . $newNumber . $alphabetPart;
    } catch (Exception $e) {
        echo "エラー: クーポンコードの生成に失敗しました。" . $e->getMessage();
        return null;
    }
}

function incrementAlphabet($alphabet) {
    $firstChar = $alphabet[0];
    $secondChar = $alphabet[1];

    if ($secondChar === 'Z') {
        if ($firstChar === 'Z') {
            throw new Exception("クーポンコードのアルファベット部分が最大値に達しました。");
        }
        $firstChar++;
        $secondChar = 'A';
    } else {
        $secondChar++;
    }

    return $firstChar . $secondChar;
}

// クーポン発行処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $couponCode = generateSequentialCouponCode($pdo);
        
        if ($couponCode === null) {
            throw new Exception("クーポンコードの生成に失敗しました。");
        }

        $expiry_date = date('Y-m-d H:i:s', strtotime('+35 days'));
        
        $couponSql = "INSERT INTO coupons (username, coupon_code, discount, expiry_date) 
                      VALUES (:username, :coupon_code, :discount, :expiry_date)";
        $couponStmt = $pdo->prepare($couponSql);
        $couponStmt->bindParam(':username', $username);
        $couponStmt->bindParam(':coupon_code', $couponCode);
        $couponStmt->bindValue(':discount', 50); //予約にて割引価格参照---------------------------------
        $couponStmt->bindParam(':expiry_date', $expiry_date);
        $couponStmt->execute();


        // ----------------------------------------------------------------------------------------------------------------------
        
        // ここに予約の更新関係の記述(ステータス２)

        // ----------------------------------------------------------------------------------------------------------------------

        // クーポンコードと有効期限をセッション変数に保存
        $_SESSION['coupon_code'] = $couponCode;
        $_SESSION['expiry_date'] = $expiry_date;
        // echo "<div class='coupon'>";
        // echo "<h2>50円引きのクーポンが発行されました！</h2>";
        // echo "<p class='discount'>¥50 OFF</p>";
        // echo "クーポンコード: {$couponCode}<br>";
        // echo "有効期限: {$expiry_date}<br>";
        // echo "</div>";

        // クーポン発行済みフラグを設定
        $_SESSION['coupon_issued'] = true;
    } catch (Exception $e) {
        echo "<h3>クーポンの発行に失敗しました。</h3>";
        echo "<p>エラー: " . $e->getMessage() . "</p>";
    }
}
// クーポン使用処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['use_coupon'])) {
    $_SESSION['coupon_used'] = true; // 使用済みフラグ
    header('Location: coupon_used.php'); // 使用済みページへリダイレクト
    exit;
}// クーポン使用処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['use_coupon'])) {
    $_SESSION['coupon_used'] = true; // 使用済みフラグ
    header('Location: coupon_used.php'); // 使用済みページへリダイレクト
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../CSS/indexStyle.css">
    <link rel="stylesheet" href="../CSS/coupons.css">
    <title>クーポン発行</title>
</head>
<body>

<div class="coupon-container">
    <?php if (!isset($_SESSION['coupon_code'])): ?>
        <form method="POST">
            <button type="submit" name="generate_coupon">クーポンを発行する</button>
        </form>
    <?php else: ?>
        <div class="coupon">
            <p class="discount">¥50 OFF</p>
            <p class="coupon-code">クーポンコード: <?= htmlspecialchars($_SESSION['coupon_code'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p class="expiry-date">有効期限: <?= htmlspecialchars($_SESSION['expiry_date'], ENT_QUOTES, 'UTF-8'); ?></p>
            <?php if (!isset($_SESSION['coupon_used'])): ?>
                <form method="POST">
                    <button type="submit" name="use_coupon">使用する</button>
                </form>
            <?php else: ?>
                <p class="used-message">このクーポンは使用済みです。</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>