<?php
// データベース接続情報
require_once('db_local.php'); // データベース接続
require_once('../Model/dbmodel.php');
$id = rand(1, 5);
try {
    $pdo = dbConnect();
} catch (PDOException $e) {
    echo $e->getMessage();
}
    $sql = "SELECT * FROM images WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    $image = $stmt->fetch();
?>

<h1>画像表示</h1>
<img src="images/<?php echo $image['name']; ?>" width="300" height="300">
<a href="upload.php">画像アップロード</a>