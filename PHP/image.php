<?php
// データベース接続情報

session_start();
// if (!isset($_SESSION['user_id'])) {
//     $_SESSION['login_message'] = "ログインしてください。"; // メッセージをセッションに保存
//     header('Location: message.php');
//     exit;
// }

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
}

require_once('../Model/dbmodel.php');

try {
    $pdo = dbConnect();
} catch (PDOException $e) {
    echo $e->getMessage();
}

if (isset($_SESSION['user_id'])) {
    $sql = "SELECT imgpath FROM users WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $user_id);
    $stmt->execute();
    $image = $stmt->fetch();
}
        
?>

<h1>画像表示</h1>
<img src="../images/<?php echo $image['imgpath']; ?>" width="300" height="300">
<a href="upload.php">画像アップロード</a>

<script>
    let inputText = ''; // 入力されたテキストを保持する変数

    // body全体にキー入力を検出するリスナーを設定
    document.body.addEventListener('keydown', function(event) {
        // Enterキーが押されたかどうかを確認
        if (event.key === 'Enter') {
            event.preventDefault(); // Enterキーのデフォルト動作を防止
            console.log(inputText);
            // 入力されたテキストが "[start]" ならリダイレクト
            if (inputText === 'start') {
                window.location.href = './start_user.php'; // リダイレクト先のURL
            }

            // 入力をリセット
            inputText = '';
        } else if (event.key.length === 1) {
            // アルファベットや数字などの1文字のキーが押された場合のみ追加
            inputText += event.key;
            
        }
    });
</script>