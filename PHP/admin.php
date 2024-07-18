<?php

if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: auth.php');
    exit;
}

// セッションが開始されていない場合のみセッションを開始
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set('Asia/Tokyo');

$servername = "mysql305.phy.lolipop.lan";
$username = "LAA1516370";
$password = "ecoperks2024";
$dbname = "LAA1516370-ecoperks";

// データベース接続
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("データベースに接続できないちゃんと確認して: " . $conn->connect_error);
}

// ログイン状況の取得
$sql = "SELECT u.username, us.login_time, us.logout_time, us.is_logged_in 
        FROM users u
        LEFT JOIN user_sessions us ON u.id = us.user_id 
        ORDER BY us.login_time DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン状況</title>
</head>
<body>
    <h1>ログイン状況</h1>
    <table border="1">
        <tr>
            <th>ユーザー名</th>
            <th>ログイン時間</th>
            <th>ログアウト時間</th>
            <th>ログイン状態</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            $currentUser = '';
            while($row = $result->fetch_assoc()) {
                if ($row['username'] !== $currentUser) {
                    $currentUser = $row['username'];
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['login_time'] ?? 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($row['logout_time'] ?? 'N/A') . "</td>";
                    if ($row['login_time'] === null) {
                        echo "<td>未ログイン</td>";
                    } else {
                        echo "<td>" . ($row['is_logged_in'] ? 'ログイン中' : 'ログアウト') . "</td>";
                    }
                    echo "</tr>";
                }
            }
        } else {
            echo "<tr><td colspan='4'>ログイン情報がありません</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
$conn->close();
