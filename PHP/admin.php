<?php
// セッションが開始されていない場合のみセッションを開始
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 認証されていない場合はログインページにリダイレクト
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: auth.php');
    exit;
}

// データベース接続
require_once('../Model/dbModel.php');
$conn = dbConn();

if ($conn->connect_error) {
    die("データベースに接続できないちゃんと確認して: " . $conn->connect_error);
}

// 検索キーワードの取得
$searchUsername = isset($_POST['searchUsername']) ? $_POST['searchUsername'] : '';

// ユーザーごとの最新のセッションを取得するクエリ
$sql = "SELECT u.username, us.login_time, us.logout_time, us.is_logged_in
        FROM users u
        LEFT JOIN user_sessions us ON u.username = us.username
        WHERE us.id IN (
            SELECT MAX(id)
            FROM user_sessions
            GROUP BY username
        )
        AND u.username LIKE ? 
        ORDER BY u.username ASC";

$stmt = $conn->prepare($sql);
$likeSearchUsername = "%$searchUsername%";
$stmt->bind_param("s", $likeSearchUsername);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../CSS/admin.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン状況</title>
    <script src="../JS/update_time.js" defer></script>
    <script src="../JS/update_data.js" defer></script>
</head>
<body>
    <div style="text-align: right; margin: 10px;">
        <label for="darkModeToggle">ダークモード</label>
        <input type="checkbox" id="darkModeToggle">
    </div>

    <h1>ログイン状況</h1>
    <h1 id="time">現在の時刻:</h1>

    <!-- 検索フォーム -->
    <form method="post">
        <label for="searchUsername">ユーザー名で検索:</label>
        <input type="text" id="searchUsername" name="searchUsername" class="large-input" value="<?php echo htmlspecialchars($searchUsername, ENT_QUOTES); ?>">
        <input type="submit" class="large-button" value="検索">
    </form>
    
    <!-- テーブルコンテナ -->
    <table id="data-table">
        <thead>
           <tr>
              <th>ユーザー名</th>
               <th>ログイン時間</th>
              <th>ログアウト時間</th>
              <th>ログイン状態</th>
           </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($row['login_time'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($row['logout_time'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= $row['is_logged_in'] ? 'オンライン' : 'オフライン' ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    
    <form action="adminlogout.php" method="post">
        <input type="submit" class="large-button" value="ログアウト">
    </form>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const toggle = document.getElementById('darkModeToggle');
            const body = document.body;

            // ユーザーの設定を取得
            const isDarkMode = localStorage.getItem('dark-mode') === 'true';

            // 初期状態を設定
            if (isDarkMode) {
                body.classList.add('dark-mode');
                toggle.checked = true;
            }

            // トグルスイッチのイベントリスナー
            toggle.addEventListener('change', function () {
                if (this.checked) {
                    body.classList.add('dark-mode');
                    localStorage.setItem('dark-mode', 'true');
                } else {
                    body.classList.remove('dark-mode');
                    localStorage.setItem('dark-mode', 'false');
                }
            });
        });
    </script>
</body>
</html>



