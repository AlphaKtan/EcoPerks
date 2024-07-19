<?php
// セッションが開始されていない場合のみセッションを開始
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// データベース接続
$servername = "mysql305.phy.lolipop.lan";
$dbUsername = "LAA1516370";
$password = "ecoperks2024";
$dbname = "LAA1516370-ecoperks";

$conn = new mysqli($servername, $dbUsername, $password, $dbname);
if ($conn->connect_error) {
    die("データベースに接続できないちゃんと確認して: " . $conn->connect_error);
}

// 検索キーワードの取得
$searchUsername = isset($_POST['searchUsername']) ? $_POST['searchUsername'] : '';

// ユーザーの全情報を取得するクエリ
$sql = "SELECT u.username, 
               us.login_time, 
               us.logout_time, 
               us.is_logged_in
        FROM users u
        LEFT JOIN user_sessions us ON u.username = us.username
        AND us.id = (
            SELECT MAX(id)
            FROM user_sessions
            WHERE username = u.username
        )
        WHERE u.username LIKE ?
        ORDER BY u.username ASC";

$stmt = $conn->prepare($sql);
$likeSearchUsername = "%$searchUsername%";
$stmt->bind_param("s", $likeSearchUsername);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // 状態の判定
        if (empty($row['username']) || $row['login_time'] === null) {
            $status = '未ログイン'; // ユーザー名が空またはセッション情報がない場合
            $loginTime = '記録無し';
            $logoutTime = '記録無し';
        } elseif ($row['is_logged_in']) {
            $status = 'ログイン中';
            $loginTime = $row['login_time'] ? date('Y-m-d<br>H:i:s', strtotime($row['login_time'])) : '記録無し';
            $logoutTime = $row['logout_time'] ? date('Y-m-d<br>H:i:s', strtotime($row['logout_time'])) : '記録無し';
        } else {
            $status = 'ログアウト';
            $loginTime = $row['login_time'] ? date('Y-m-d<br>H:i:s', strtotime($row['login_time'])) : '記録無し';
            $logoutTime = $row['logout_time'] ? date('Y-m-d<br>H:i:s', strtotime($row['logout_time'])) : '記録無し';
        }
        
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['username']) . "</td>";
        echo "<td class='login-time'><div class='time-wrapper'>$loginTime</div></td>";
        echo "<td class='logout-time'><div class='time-wrapper'>$logoutTime</div></td>";
        echo "<td>$status</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4'>ログイン情報がありません</td></tr>";
}

$stmt->close();
$conn->close();
?>


