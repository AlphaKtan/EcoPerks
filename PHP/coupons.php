<?php
session_start();

// ---------------------------------------------
// DB接続 (dbModel.php から dbConnect() を読み込み)
// ---------------------------------------------
require_once('../Model/dbModel.php');
$pdo = dbConnect();

// ---------------------------------------------
// ログイン判定
// ---------------------------------------------
if (!isset($_SESSION['user_id'])) {
    $_SESSION['login_message'] = "ログインしてください。";
    header('Location: message.php');
    exit;
}

// ログインしているユーザーのIDまたは名前を取得
$username = $_SESSION['user_id'];

/* ------------------------------------------------------------
   1) 発行資格の判定
   - 例：予約テーブル `yoyaku` の中で
         `username` がログイン中ユーザー かつ status=2 のレコードが1件以上ある
         → 発行資格あり
   ------------------------------------------------------------ */
function userHasQualifiedReservation($pdo, $username) {
    $sql = "SELECT COUNT(*) 
            FROM yoyaku 
            WHERE username = :username 
              AND status = 2"; // status=2 を「完了」など好きに定義
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    
    // 1件以上あれば発行資格あり(true)とする
    return $count > 0;
}

/* ------------------------------------------------------------
   2) クーポンコードを生成する関数
   - coupons テーブルのうち最新の行を取得し、
     AA00000001AA 形式のクーポン番号を連番で作成
   ------------------------------------------------------------ */
function generateSequentialCouponCode($pdo) {
    $lastCouponSql = "SELECT coupon_code FROM coupons ORDER BY coupon_id DESC LIMIT 1";
    $lastCouponStmt = $pdo->query($lastCouponSql);
    $lastCoupon = $lastCouponStmt->fetchColumn(); // 最新の coupon_code (例: AA00000001AA)

    $prefix = "AA";
    $startNumber = 1;
    $numberLength = 8;                      // 数字パートは8桁
    $maxNumber = str_repeat('9', $numberLength); // 99999999

    if ($lastCoupon) {
        // 例: AA00000001AA と想定
        $alphabetPart = substr($lastCoupon, -2);               // 末尾2文字 (例: 'AA')
        $numberPart   = (int)substr($lastCoupon, 2, $numberLength); // 真ん中の8桁(例: '00000001')を整数化

        // 99999999を超えていたらアルファベットをインクリメントし、数字を00000001に戻す
        if ($numberPart >= (int)$maxNumber) {
            $alphabetPart = incrementAlphabet($alphabetPart);
            $newNumber = str_pad($startNumber, $numberLength, '0', STR_PAD_LEFT);
        } else {
            // まだ99999999未満なので+1
            $newNumber = str_pad($numberPart + 1, $numberLength, '0', STR_PAD_LEFT);
        }
    } else {
        // まだ1件もクーポンが発行されていない場合
        $alphabetPart = $prefix;
        $newNumber = str_pad($startNumber, $numberLength, '0', STR_PAD_LEFT);
    }

    // 新しいクーポンコードを作成 (例: AA00000002AA)
    $newCouponCode = $prefix . $newNumber . $alphabetPart;
    return $newCouponCode;
}

/* ------------------------------------------------------------
   3) アルファベット2文字をインクリメントする補助関数
   - AA, AB, ... AZ, BA, BB, ... ZZ と進めたいイメージ
   - 今回は末尾2文字で管理するので好きな仕組みに変更可能
   ------------------------------------------------------------ */
function incrementAlphabet($alphabet) {
    $firstChar  = $alphabet[0];
    $secondChar = $alphabet[1];

    if ($secondChar === 'Z') {
        if ($firstChar === 'Z') {
            // 'ZZ' までいったら、もうインクリメント不可と判断
            throw new Exception("クーポンコードのアルファベット部分が最大値に達しました。");
        }
        // 例: 'AZ' → 'BA'
        $firstChar++;
        $secondChar = 'A';
    } else {
        // 例: 'AB' → 'AC'
        $secondChar++;
    }

    return $firstChar . $secondChar;
}

// ---------------------------------------------------------
// 4) クーポン発行処理 (POST: generate_coupon)
// ---------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_coupon'])) {
    try {
        // 1) 発行資格チェック
        if (!userHasQualifiedReservation($pdo, $username)) {
            echo "<h3>まだクーポン発行条件を満たしていません。</h3>";
        } else {
            // 2) クーポンコード生成
            $couponCode = generateSequentialCouponCode($pdo);
            if ($couponCode === null) {
                throw new Exception("クーポンコードの生成に失敗しました。");
            }

            // 3) time_changeテーブルからpriceを取得
            $timeChangeSql = "SELECT price 
                              FROM time_change
                              WHERE areaid = :areaid 
                                AND status = :status
                              LIMIT 1";
            $timeChangeStmt = $pdo->prepare($timeChangeSql);
            $timeChangeStmt->bindValue(':areaid', 1, PDO::PARAM_INT); 
            $timeChangeStmt->bindValue(':status', 'active', PDO::PARAM_STR); 
            $timeChangeStmt->execute();
            $originalPrice = $timeChangeStmt->fetchColumn(); // 例: 150 (もし該当なければ falseやnull)

            // もしレコードが見つからなかった場合の対処
            if (!$originalPrice) {
                // 適切にエラー処理
                echo "該当のtime_changeレコードが見つかりません。";
                exit;
            }

            // 4) 割引額の計算(例: 50円引き)
            $discountPrice = $originalPrice - 50; 
            // 例: 150 - 50 = 100円 → これを最終料金とするか
            //   あるいはクーポンの割引額として保管するのかは自由

            // 5) クーポンの有効期限
            $expiry_date = date('Y-m-d H:i:s', strtotime('+35 days'));

            // 6) couponsテーブルにINSERT
            // たとえば「割引額」カラムに `$discountPrice` を入れるとしたら？
            $couponSql = "
                INSERT INTO coupons (
                    username, 
                    coupon_code, 
                    discount,     -- ここに割引額を入れる
                    expiry_date
                ) VALUES (
                    :username, 
                    :coupon_code, 
                    :expiry_date
                )
            ";
            $couponStmt = $pdo->prepare($couponSql);
            $couponStmt->bindParam(':username', $username);
            $couponStmt->bindParam(':coupon_code', $couponCode);
            $couponStmt->bindValue(':discount', $discountPrice, PDO::PARAM_INT);
            $couponStmt->bindParam(':expiry_date', $expiry_date);
            $couponStmt->execute();

            // 7) 予約テーブルのステータス更新
            $updateYoyakuSql = "UPDATE yoyaku 
                                SET status = 3 
                                WHERE username = :username AND status = 2";
            $updateYoyakuStmt = $pdo->prepare($updateYoyakuSql);
            $updateYoyakuStmt->bindValue(':username', $username, PDO::PARAM_STR);
            $updateYoyakuStmt->execute();

            // 8) セッションにクーポン情報を保存
            $_SESSION['coupon_code'] = $couponCode;
            $_SESSION['expiry_date'] = $expiry_date;
            $_SESSION['coupon_issued'] = true;
        }
    } catch (Exception $e) {
        echo "<h3>クーポンの発行に失敗しました。</h3>";
        echo "<p>エラー: " . $e->getMessage() . "</p>";
    }
}
// ---------------------------------------------------------
// 5) クーポン使用処理 (POST: use_coupon)
// ---------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['use_coupon'])) {
    // 「使用済み」にするフラグをセッションで管理する例
    $_SESSION['coupon_used'] = true; 
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

    <!-- まだクーポンを発行していない場合は「発行ボタン」を表示 -->
    <?php if (!isset($_SESSION['coupon_code'])): ?>
        <form method="POST">
            <!-- name="generate_coupon" を持つボタンを押すとクーポン生成が走る -->
            <button type="submit" name="generate_coupon">クーポンを発行する</button>
        </form>

    <!-- すでにクーポンが発行済みの場合はクーポン情報を表示 -->
    <?php else: ?>
        <div class="coupon">
            <p class="discount">¥50 OFF</p>
            <p class="coupon-code">
                クーポンコード: <?= htmlspecialchars($_SESSION['coupon_code'], ENT_QUOTES, 'UTF-8'); ?>
            </p>
            <p class="expiry-date">
                有効期限: <?= htmlspecialchars($_SESSION['expiry_date'], ENT_QUOTES, 'UTF-8'); ?>
            </p>

            <!-- まだクーポンを使用していない場合は「使用する」ボタンを表示 -->
            <?php if (!isset($_SESSION['coupon_used'])): ?>
                <form method="POST">
                    <!-- name="use_coupon" ボタンを押すとクーポン使用処理へ -->
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

