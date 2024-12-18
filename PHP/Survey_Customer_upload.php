<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/formStyle.css">
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

<?php 
session_start();
require_once('../Model/dbmodel.php');

try {
    $pdo = dbConnect();
} catch (PDOException $e) {
    echo $e->getMessage();
    exit;
}

if (isset($_POST['submit'])) {
    $gomi = htmlspecialchars($_POST['gomi'] ?? '', ENT_QUOTES, 'UTF-8');
    $areaid = htmlspecialchars($_POST['areaid'] ?? '', ENT_QUOTES, 'UTF-8');
    $body = htmlspecialchars($_POST['body'] ?? '', ENT_QUOTES, 'UTF-8');

    // 画像のアップロード処理
    $image_path = "";
    if (!empty($_FILES['image']['name'])) {
        $image_name = uniqid(mt_rand(), true) . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_path = "../images/" . $image_name;
        $image_uploaded = move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
        if (!$image_uploaded) {
            $image_path = "";
        }
    }

    $_SESSION['survey_data'] = [
        'gomi' => $gomi,
        'body' => $body,
        'areaid' => $areaid,
        'image_path' => $image_path
    ];
}

if (isset($_POST['confirm']) && isset($_SESSION['survey_data'])) {
    $data = $_SESSION['survey_data'];
    try {
        $sql = "INSERT INTO survey_responses (gomi, body, areaid, image_path) VALUES (:gomi, :body, :areaid, :image_path)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':gomi', $data['gomi'], PDO::PARAM_INT);
        $stmt->bindParam(':body', $data['body'], PDO::PARAM_STR);
        $stmt->bindParam(':areaid', $data['areaid'], PDO::PARAM_STR);
        $stmt->bindParam(':image_path', $data['image_path'], PDO::PARAM_STR);
        $stmt->execute();

        echo "<h3>アンケートが正常に送信されました。</h3>";
        echo '<a href="Survey_Customer_output.php">アンケート閲覧</a>';
        unset($_SESSION['survey_data']);
        exit;
    } catch (PDOException $e) {
        echo "<p>エラー: " . $e->getMessage() . "</p>";
    }
}

$data = $_SESSION['survey_data'] ?? [];
$gomi = $data['gomi'] ?? '';
$areaid = $data['areaid'] ?? '';
$body = $data['body'] ?? '';
$image_path = $data['image_path'] ?? '';

?>

<section class="login_form">
    <form method="POST" action="Survey_Customer_upload.php">
        <h1>フォーム確認ページ</h1>
        <div class="form-group">
            <strong>ゴミの量：</strong>
            <?php 
                $gomi_values = ['4' => '多い', '3' => 'まぁまぁ多い', '2' => 'まぁまぁ少ない', '1' => '少ない'];
                echo $gomi_values[$gomi] ?? '不明'; 
            ?>
        </div>
        <div class="form-group">
            <p><?php echo "<h3>エリアナンバー：$areaid</h3>" ;?></p>
        </div>
        <div class="form-group">
            <strong>お問い合わせ内容：</strong><br>
            <?php echo nl2br($body); ?>
        </div>

        <?php if (!empty($image_path)): ?>
            <div class="form-group">
                <strong>アップロードされた画像：</strong><br>
                <img src="<?php echo $image_path; ?>" alt="Uploaded Image" style="max-width: 300px;">
            </div>
        <?php endif; ?>

        <button type="submit" name="confirm">送信</button>
    </form>
</section>
</body>
</html>
