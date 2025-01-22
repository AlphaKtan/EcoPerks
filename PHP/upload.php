<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/formStyle.css">
    <title>アカウント登録</title>
</head>
<body>
    <style>
        .iconImg{
            width: 300px;
            height: 300px;
        }

        
@media screen and (max-width: 768px) {
    .iconImg {
        width: 200px;
        height: 200px;
    }
}

    </style>
<?php
// データベース接続情報
session_start();
    require_once('../Model/dbmodel.php');
    try {
        $pdo = dbConnect();
        $directory = '<a href="./Mypage_user.php">マップ</a> > <a href="./Mypage_user.php">マイページ</a> > 名前変更';
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    // echo isset($_FILES['image']) ? 'yes' : 'no';

    // echo isset($_POST['username']) ? 'yes' : 'no';
    // echo !empty($_POST['username']) ? 'yes' : 'no';

    $message = "";

        if (isset($_FILES['image'])) {//送信ボタンが押された場合
            $image = uniqid(mt_rand(), true);//ファイル名をユニーク化
            $image .= '.' . substr(strrchr($_FILES['image']['name'], '.'), 1);//アップロードされたファイルの拡張子を取得
            $file = "../images/$image";
            $sql = "UPDATE users SET imgpath = :image WHERE id = :user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':image', $image, PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            if (!empty($_FILES['image']['name'])) {//ファイルが選択されていれば$imageにファイル名を代入
                move_uploaded_file($_FILES['image']['tmp_name'], '../images/' . $image);//imagesディレクトリにファイル保存
                if (exif_imagetype($file)) {//画像ファイルかのチェック
                    $message = '<p>画像をアップロードしました</p>';
                    $stmt->execute();
                } else {
                    $message = '<p>画像ファイルではありません</p>';
                }
            }
        }

        if (!empty($_POST['username'])) {//送信ボタンが押された場合
            $username = $_POST['username'];
            $sql_username = "UPDATE users SET username = :username WHERE id = :user_id";
            $stmt_username = $pdo->prepare($sql_username);
            $stmt_username->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt_username->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt_username->execute();
        }
?>

<?php include 'header.php';?>

<section class="login_form">
    <h1>画像アップロード</h1>
    <!--送信ボタンが押された場合-->
    <?php if (isset($_POST['upload'])): ?>
        <img src="../images/<?php echo $file; ?>" class="iconImg">
        <?php 
        if (isset($username)) {
            echo "<h3>$username</h>";
        }
        ?>
        <p><?php echo $message; ?></p>


        <button class="menu_button" type="button">
            <a href="Mypage_user.php">マイページへ</a>
        </botton>
        <button class="menu_button" type="button">
            <a href="upload.php">再アップロード</a>
        </botton>
    <?php else: ?>
        <form method="post" enctype="multipart/form-data">

        <div class="form-group">
            <label>アップロード画像</label>
            <input type="file" name="image">
        </div>
            
        <div class="form-group">
            <label>名前変更</label>
            <input type="text" name="username">
        </div>    
            <button type="submit" name="upload" value="送信">送信ボタン</button>
        
        </form>
    <?php endif;?>
</section>
</body>
</html>

