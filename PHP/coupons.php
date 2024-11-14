<?php
session_start();

//require_once('db_connection.php');
require_once('db_local.php');
$_SESSION['username'] = 'やー';
if (!isset($_SESSION['username'])) {
    $_SESSION['login_message'] = "ログインしてください。";
    header('Location: message.php');
    exit;
}

$username = $_SESSION['username'];

// クーポンが発行されているか確認し、未定義の場合は初期化
// if (!isset($_SESSION['coupon_issued'])) {
//     $_SESSION['coupon_issued'] = false;
// }

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
        $couponStmt->bindValue(':discount', 50);
        $couponStmt->bindParam(':expiry_date', $expiry_date);
        $couponStmt->execute();
        // クーポンコードと有効期限をセッション変数に保存
        $_SESSION['coupon_code'] = $couponCode;
        $_SESSION['expiry_date'] = $expiry_date;
        echo "<div class='coupon'>";
        echo "<h2>50円引きのクーポンが発行されました！</h2>";
        echo "<p class='discount'>¥50 OFF</p>";
        echo "クーポンコード: {$couponCode}<br>";
        echo "有効期限: {$expiry_date}<br>";
        echo "</div>";

        // クーポン発行済みフラグを設定
        $_SESSION['coupon_issued'] = true;
    } catch (Exception $e) {
        echo "<h3>クーポンの発行に失敗しました。</h3>";
        echo "<p>エラー: " . $e->getMessage() . "</p>";
    }
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
    <div class="coupon-message">
        <h3>50円引きのクーポンが発行されました！</h3>
    </div>

    <div class="coupon">
        <p class="discount">¥50 OFF</p>
        <p class="coupon-code">クーポンコード: <?= isset($_SESSION['coupon_code']) ? $_SESSION['coupon_code'] : 'N/A'; ?></p>
        <p class="expiry-date">有効期限: <?= isset($_SESSION['expiry_date']) ? $_SESSION['expiry_date'] : 'N/A'; ?></p>
        <div class="coupon-right">
            <button id="use-coupon-btn" onclick="useCoupon()">使用する</button>
        </div>
    </div>
</div>

<script>
function useCoupon() {
    const couponRight = document.querySelector('.coupon-right');
    const coupon = document.querySelector('.coupon');
    
    // 右半分をスライドアウトさせて消す
    couponRight.classList.add('coupon-slide-out');
    
    // アニメーション終了後に「使用済み」表示に切り替え
    couponRight.addEventListener('animationend', () => {
        coupon.classList.add('used');
        couponRight.remove();  // 右側部分を削除
    });
}
</script>

</body>
</html>

