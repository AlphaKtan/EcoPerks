<?php
session_start();
require_once('../Model/dbModel.php');
// echo "a";
$pdo = dbConnect();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['login_message'] = "ログインしてください。";
    header('Location: message.php');
    exit;
}
$area_id = isset($_POST['area_id']) ? (int)$_POST['area_id'] : null;
$location = isset($_POST['location_id']) ? trim($_POST['location_id']) : null;

$username = $_SESSION['user_id'];

/* ------------------------------------------------------------
   1) 発行資格の判定 (ステータスチェックなし)
   ------------------------------------------------------------ */
function userHasQualifiedReservation($pdo, $username) {
     $sql = "SELECT COUNT(*) 
             FROM yoyaku 
             WHERE username = :username";
     $stmt = $pdo->prepare($sql);
     $stmt->bindValue(':username', $username, PDO::PARAM_STR);
     $stmt->execute();
     return $stmt->fetchColumn() > 0;
 }

/* ------------------------------------------------------------
   2) クーポンコード生成
   ------------------------------------------------------------ */
function generateSequentialCouponCode($pdo) {
    $lastCouponSql = "SELECT coupon_code FROM coupons ORDER BY coupon_id DESC LIMIT 1";
    $lastCouponStmt = $pdo->query($lastCouponSql);
    $lastCoupon = $lastCouponStmt->fetchColumn();
    $prefix = "AA";
    $startNumber = 1;
    $numberLength = 8;
    $maxNumber = str_repeat('9', $numberLength);

    if ($lastCoupon) {
        $alphabetPart = substr($lastCoupon, -2);
        $numberPart   = (int)substr($lastCoupon, 2, $numberLength);
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
}

function incrementAlphabet($alphabet) {
    $firstChar  = $alphabet[0];
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

/* ------------------------------------------------------------
   3) クーポン発行処理 
   
   ------------------------------------------------------------ */

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_coupon'])) {
    try {
        
        if (!userHasQualifiedReservation($pdo, $username)) {
             echo "<h3>まだクーポン発行条件を満たしていません。</h3>";
        } else 
        {
            //価格を取得し、割引額として使用 
            // $priceSql = "SELECT price FROM yoyaku
            //              WHERE username = :username 
            //             --  AND area_id = :area_id 
            //             --  AND location = :location 
            //             --  AND reservation_date = CURDATE()
            //             --  AND end_time - INTERVAL 30 MINUTE <= NOW() 
            //             --  AND end_time + INTERVAL 15 MINUTE > NOW()
            //              LIMIT 1";
            // $priceStmt = $pdo->prepare($priceSql);
            // $priceStmt->bindValue(':username', $username);
            // // $priceStmt->bindValue(':area_id', $area_id, PDO::PARAM_INT); 
            // // $priceStmt->bindValue(':location', $location, PDO::PARAM_STR); 
            // $priceStmt->execute();
            // $discountPrice = $priceStmt->fetchColumn();

            $priceSql = "SELECT price FROM yoyaku WHERE username = :username LIMIT 1";
        $priceStmt = $pdo->prepare($priceSql);
        $priceStmt->bindValue(':username', $username);
        $priceStmt->execute();
        $discountPrice = $priceStmt->fetchColumn();




            // if (!$discountPrice) {
            //     echo "該当の価格データが見つかりませんでした。";
            //     exit;
            // }
            
            
            // クーポンコード生成
            $couponCode = generateSequentialCouponCode($pdo);
            $expiry_date = date('Y-m-d H:i:s', strtotime('+35 days'));

            // クーポン登録
            $couponSql = "INSERT INTO coupons (username, coupon_code, discount, expiry_date) 
                          VALUES (:username, :coupon_code, :discount, :expiry_date)";
            $couponStmt = $pdo->prepare($couponSql);
            $couponStmt->bindParam(':username', $username);
            $couponStmt->bindParam(':coupon_code', $couponCode);
            $couponStmt->bindValue(':discount', $discountPrice, PDO::PARAM_INT);
            $couponStmt->bindParam(':expiry_date', $expiry_date);
            $couponStmt->execute();

            $_SESSION['discount_price'] = $discountPrice;
            $_SESSION['coupon_code'] = $couponCode;
            $_SESSION['expiry_date'] = $expiry_date;
            $_SESSION['coupon_issued'] = true;



            // echo "<h3>クーポンが発行されました！</h3>";
        }
    } catch (Exception $e) {
        echo "<h3>クーポンの発行に失敗しました。</h3>";
        echo "<p>エラー: " . $e->getMessage() . "</p>";
    }
}
?>

<?php
// クーポン使用処理のロジック
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['use_coupon'])) {
    if (isset($_SESSION['coupon_issued']) && !isset($_SESSION['coupon_used'])) {
        $_SESSION['coupon_used'] = true;
        echo "<p style='color: green;'>クーポンを正常に使用しました。</p>";


        
    } elseif (isset($_SESSION['coupon_used'])) {
        echo "<p style='color: red;'>このクーポンはすでに使用済みです。</p>";
    } else {
        echo "<p style='color: red;'>クーポンが見つかりません。</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../CSS/indexStyle.css">
    <link rel="stylesheet" href="../CSS/coupons.css">
    <title>クーポン発行と使用</title>
</head>
<body>
<div class="coupon-container">
    <?php if (!isset($_SESSION['coupon_code'])): ?>
        <form method="POST">
            <button type="submit" name="generate_coupon">クーポンを発行する</button>
        </form>
    <?php else: ?>
        <div class="coupon">
            <p class="discount">割引額: ¥<?= htmlspecialchars($_SESSION['discount_price'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p class="coupon-code">クーポンコード: <?= htmlspecialchars($_SESSION['coupon_code'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p class="expiry-date">有効期限: <?= htmlspecialchars($_SESSION['expiry_date'], ENT_QUOTES, 'UTF-8'); ?></p>
        </div>

        <!-- クーポン使用ボタン -->
        <?php if (!isset($_SESSION['coupon_used'])): ?>
            <form method="POST">
                <button type="submit" name="use_coupon">使用する</button>
            </form>
        <?php else: ?>
            <p class="used-message">このクーポンは使用済みです。</p>
        <?php endif; ?>
    <?php endif; ?>
</div>
</body>
</html>