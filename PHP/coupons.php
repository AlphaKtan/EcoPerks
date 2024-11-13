<?php
session_start();

require_once('db_connection.php');

if (!isset($_SESSION['username'])) {
    $_SESSION['login_message'] = "ログインしてください。";
    header('Location: message.php');
    exit;
}

$username = $_SESSION['username'];

// クーポンが発行されているか確認し、未定義の場合は初期化
if (!isset($_SESSION['coupon_issued'])) {
    $_SESSION['coupon_issued'] = false;
}

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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['coupon_issued'] === false) {
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

        echo "<h3>50円引きのクーポンが発行されました！</h3>";
        echo "<p>クーポンコード: {$couponCode}</p>";
        echo "<p>有効期限: {$expiry_date}</p>";

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
    <title>クーポン発行</title>
</head>
<body>
    <?php if ($_SESSION['coupon_issued'] === false): ?>
        <!-- クーポン発行ボタンを表示 -->
        <form method="post">
            <button type="submit">クーポンを発行する</button>
        </form>
    <?php else: ?>
        <!-- 発行済みメッセージを表示 -->
        <p>クーポンは既に発行されています。</p>
    <?php endif; ?>
</body>
</html>

