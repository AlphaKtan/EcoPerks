<?php
// データベース接続情報
session_start();
    require_once('db_local.php'); // データベース接続
    require_once('../Model/dbmodel.php');
    try {
        $pdo = dbConnect();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    
        if (isset($_POST['upload'])) {//送信ボタンが押された場合
            echo $_SESSION['user_id'];
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
                    $message = '画像をアップロードしました';
                    $stmt->execute();
                } else {
                    $message = '画像ファイルではありません';
                }
            }
        }
?>

<h1>画像アップロード</h1>
<!--送信ボタンが押された場合-->
<?php if (isset($_POST['upload'])): ?>
    <p><?php echo $message; ?></p>
    <p><a href="image.php">画像表示へ</a></p>
<?php else: ?>
    <form method="post" enctype="multipart/form-data">
        <p>アップロード画像</p>
        <input type="file" name="image">
        <button><input type="submit" name="upload" value="送信"></button>
    </form>
<?php endif;?>

<script src="../JS/pass.js"></script>