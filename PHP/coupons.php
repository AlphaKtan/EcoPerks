<?php
session_start();

require_once('db_connection.php'); // データベース接続ファイル

// ユーザーがログインしているか確認
if (!isset($_SESSION['username'])) {
    $_SESSION['login_message'] = "ログインしてください。";
    header('Location: message.php');
    exit;
}

$username = $_SESSION['username'];

// クーポンコードを生成する関数
function generateSequentialCouponCode($pdo) {
    // 最後のクーポンコードを取得
    $lastCouponSql = "SELECT coupon_code FROM coupons ORDER BY coupon_id DESC LIMIT 1";
    $lastCouponStmt = $pdo->query($lastCouponSql);
    $lastCoupon = $lastCouponStmt->fetchColumn();

    // 初回のクーポンコード
    $prefix = "AA";
    $startNumber = 1;
    $numberLength = 8;
    $maxNumber = str_pad('', $numberLength, '9'); // 8桁の最大値（99999999）

    if ($lastCoupon) {
        // クーポンのアルファベットと番号部分を分離
        $alphabetPart = substr($lastCoupon, -2);
        $numberPart = (int)substr($lastCoupon, 2, $numberLength);

        // 番号が最大値かどうかをチェック
        if ($numberPart >= (int)$maxNumber) {
            // アルファベット部分をインクリメント
            $alphabetPart = incrementAlphabet($alphabetPart);
            $newNumber = str_pad($startNumber, $numberLength, '0', STR_PAD_LEFT); // 番号を1にリセット
        } else {
            // 番号をインクリメント
            $newNumber = str_pad($numberPart + 1, $numberLength, '0', STR_PAD_LEFT);
        }
    } else {
        // 初回のクーポンコード
        $alphabetPart = $prefix;
        $newNumber = str_pad($startNumber, $numberLength, '0', STR_PAD_LEFT);
    }

    return $prefix . $newNumber . $alphabetPart;
}

// アルファベット部分をインクリメントする関数
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

// クーポンの発行 (50円引き)
try {
    $couponCode = generateSequentialCouponCode($pdo); // 順番通りのクーポンコード生成
    $expiry_date = date('Y-m-d H:i:s', strtotime('+35 days')); // クーポン有効期限を7日後に設定
    $couponSql = "INSERT INTO coupons (username, coupon_code, discount, expiry_date) 
                  VALUES (:username, :coupon_code, :discount, :expiry_date)";
    $couponStmt = $pdo->prepare($couponSql);
    $couponStmt->bindParam(':username', $username);
    $couponStmt->bindParam(':coupon_code', $couponCode);
    $couponStmt->bindValue(':discount', 50); // 50円引きクーポン
    $couponStmt->bindParam(':expiry_date', $expiry_date);
    $couponStmt->execute();

    echo "<h3>50円引きのクーポンが発行されました！</h3>";
    echo "<p>クーポンコード: {$couponCode}</p>";
    echo "<p>有効期限: {$expiry_date}</p>";
} catch (Exception $e) {
    echo "<h3>クーポンの発行に失敗しました。</h3>";
    echo "<p>エラー: " . $e->getMessage() . "</p>";
}


