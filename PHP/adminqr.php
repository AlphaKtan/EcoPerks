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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/destyle.css@1.0.15/destyle.css"/>
    <link rel="stylesheet" href="../CSS/hannbaka.css">
    <link rel="stylesheet" href="../CSS/adminqr.css">
    <link rel="stylesheet" href="../CSS/sidebar.css"> 
<head>
    <meta charset="UTF-8">
    <title>管理者様施設選択ページ</title>
</head> 
   
<body>
    <header>
        <div class="flexBox">
            <div class="menu">
            </div>
            <div class="logo">
                <img src="../img/logo_yoko.svg" alt="" class="logo2">
            </div>
            <div class="icon">
                <form action="logout.php" method="post" class = "logout_form">
                </form>
            </div>
        </div>
    </header>
<div class="left-menu">
        <div>
            <ul class="menu-list">
                <p class="text-box">アカウント情報</p>
                <li class="menu-item"><a href="admin.php" class="a_link"><img src="../img/hito.png" class="logo"><span class="menu-item-text">ログイン</span></a></li>
                <p class="text-box">QRコード</p>
                <li class="menu-item"><a href="adminqr.php" class="a_link"><img src="../img/qr.png" class="logo"><span class="menu-item-text">QRコード生成ページ</span></a></li>
                <li class="menu-item"><a href="adminqr_end.php" class="a_link"><img src="../img/qr.png" class="logo"><span class="menu-item-text">QRコード終了管理</span></a></li>
                <p class="text-box">その他/管理・表示</p>
                <li class="menu-item"><a href="data.php" class="a_link"><img src="../img/DB.png" class="logo"></span><span class="menu-item-text-chat">データ管理ページ</span></a></li>
                <li class="menu-item"><a href="sankakanri.php" class="a_link"><img src="../img/tuika.png" class="logo"><span class="menu-item-text">参加者管理ページ</span></a></li>
                <li class="menu-item"><a href="shift.php" class="a_link"><img src="../img/shift.png" class="logo"><span class="menu-item-text">シフトページ</span></a></li>
                <li class="menu-item"><a href="time_change.php" class="a_link"><img src="../img/shiftsakujo.png" class="logo"><span class="menu-item-text">シフト削除</span></a></li>
                <li class="menu-item"><a href="access_log.php" class="a_link"><img src="../img/log2.png" class="logo"><span class="menu-item-text">アクセスログ表示ページ</span></a></li>
            </ul>
            <ul class="menu-list-bottom">
            </ul>
        </div>
    </div>
<div class="right-content">
</ul>       
    <h2 class="search">開始画面</h2>
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

