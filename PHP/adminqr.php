<?php
session_start();
require_once('../Model/dbModel.php');

$pdo = dbConnect();

// travel_data テーブルから施設データを取得
$stmt = $pdo->query("SELECT id, area_id, facility_name FROM travel_data");
$facilities = $stmt->fetchAll(PDO::FETCH_ASSOC);

// POSTリクエストが送信された場合に施設名をセッションに保存
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['facility_id'])) {
    $facility_id = htmlspecialchars($_POST['facility_id'], ENT_QUOTES, 'UTF-8');
    foreach ($facilities as $facility) {
        if ($facility['id'] == $facility_id) {
            $_SESSION['facility_name'] = $facility['facility_name'];
            $_SESSION['area_id'] = $facility['area_id'];
            header('Location: sanka.php'); // QRコード生成ページにリダイレクト
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/destyle.css@1.0.15/destyle.css"/>
    <link rel="stylesheet" href="../CSS/hannbaka.css">
    <link rel="stylesheet" href="../CSS/adminqr.css">
    <title>管理者様施設選択ページ</title>
</head>    
<body>

    <header>
        <div class="flexBox">
            <div class="menu">
                <div class="openbtn"><span></span><span></span><span></span></div>
                <nav id="g-nav">
                    <div id="g-nav-list"><!--ナビの数が増えた場合縦スクロールするためのdiv※不要なら削除-->
                        <ul>
                            <li><a href="#">Top</a></li>
                            <li><a href="../login_page.php">ログイン</a></li> 
                            <li><a href="regist.php">アカウント作成</a></li> 
                            <li><a href="Mypage_user.php">Mypage</a></li> 
                        </ul>
                    </div>
                </nav>
            </div>
            <div class="logo">
                <img src="../img/logo.jpg" alt="" class="logo2">
            </div>
            <div class="icon">
                <form action="logout.php" method="post" class = "logout_form">
                    <button type="submit" class="logout1">ログアウト</button>
                </form>
            </div>
        </div>
    </header>    
    <h2 class="search">エリア検索</h2>
    <div class="A1">
        <p class="area">施設名選択</p>
        <br>
            <form action="" method="post"> <!-- 現在のページにPOSTリクエストを送信 -->
                <select name="facility_id" id="facility_id">
                <option value="" selected disabled>=== 選択してください ===</option>
                    <?php foreach ($facilities as $facility): ?>
                        <option value="<?php echo htmlspecialchars($facility['id'], ENT_QUOTES, 'UTF-8'); ?>">
                            <?php echo htmlspecialchars($facility['facility_name'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>    
                </select>
                <br>
            <p>※QR発行後、参加者が読み取り次第、ゴミ拾いが開始します。</p>
                <br>
            <div class="button-container">
             <button type="submit" class="select-btn">選択する</button>
            </div>
        
        </div>
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    </form>
    <script src="../JS/hannbaka.js"></script>
</body>
</html>

