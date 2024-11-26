<?php 
if (isset($_POST['submit'])) {
    // POSTされたデータをエスケープ処理
    $gomi = htmlspecialchars($_POST['gomi'], ENT_QUOTES, 'UTF-8');
    $body = htmlspecialchars($_POST['body'], ENT_QUOTES, 'UTF-8');
    
    // アップロードされた画像を処理
    $image_path = "";
    if (!empty($_FILES['image']['name'])) {
        $image_name = uniqid(mt_rand(), true) . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_path = "../images/" . $image_name;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            $image_uploaded = true;
        } else {
            $image_uploaded = false;
        }
    }

    // 確認ページを表示
    if (isset($_POST['confirm'])) {
        // データベースへの接続
        try {
            
            //ここにコードを書く

            // 成功メッセージ
            echo "<p>アンケートが正常に送信されました。</p>";
        } catch (PDOException $e) {
            echo "<p>エラー: " . $e->getMessage() . "</p>";
        }
    }
} else {
    echo "<p>送信が確認されませんでした。もう一度試してください。</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/indexStyle.css">
    <link rel="stylesheet" href="../CSS/yoyakukakuninStyle.css">
    <title>アンケートフォーム</title>
</head>
<body>

<header>
    <div class="flexBox">
        <div class="menu"></div>
        <div class="logo">
            <img src="../img/logo.jpg" alt="" class="logo2">
        </div>
        <div class="icon"></div>
    </div>
</header>

<div class="container">
    <form method="POST" action="Survey_Customer_output.php">
        <h1>フォーム確認ページ</h1>
        <div>
            <strong>ゴミの量：</strong> 
            <?php 
                $gomi_values = ['1' => '多い', '2' => 'まぁまぁ', '3' => '少ない'];
                echo $gomi_values[$gomi]; 
            ?>
        </div>
        <div><strong>お問い合わせ内容：</strong><br><?php echo nl2br($body); ?></div>

        <?php if (!empty($image_path) && $image_uploaded): ?>
            <div>
                <strong>アップロードされた画像：</strong><br>
                <img src="<?php echo $image_path; ?>" alt="Uploaded Image" style="max-width: 300px;">
            </div>
        <?php elseif (!empty($image_path)): ?>
            <p>画像のアップロードに失敗しました。</p>
        <?php endif; ?>
        
        <!-- 送信ボタン -->
        <button type="submit" name="confirm">送信</button>
        <!-- 送信するデータをフォームで保持 -->
        <input type="hidden" name="gomi" value="<?php echo $gomi; ?>">
        <input type="hidden" name="body" value="<?php echo $body; ?>">
        <input type="hidden" name="image" value="<?php echo $image_path; ?>">
    </form>
</div>

</body>
</html>
