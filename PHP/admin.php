<?php
//ログイン管理システムの試験作成
$servername = "mysql305.phy.lolipop.lan";
$username = "LAA1516370";
$password = "ecoperks2024";
$dbname = "LAA1516370-ecoperks";



$conn = new mysqli($servername, $username, $password, $dbname);

// 接続確認
if ($conn->connect_error) {
    die("接続失敗: " . $conn->connect_error);
}

// セッション情報の取得
$sql = "SELECT us.id, u.username, us.login_time, us.logout_time, us.is_logged_in
        FROM user_sessions us
        JOIN users u ON us.user_id = u.id
        ORDER BY us.login_time DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>セッション管理</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>ユーザーセッション管理</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>ユーザー名</th>
                <th>ログイン時刻</th>
                <th>ログアウト時刻</th>
                <th>ログイン状態</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row["id"] . "</td>
                            <td>" . $row["username"] . "</td>
                            <td>" . $row["login_time"] . "</td>
                            <td>" . ($row["logout_time"] ? $row["logout_time"] : "N/A") . "</td>
                            <td>" . ($row["is_logged_in"] ? "ログイン中" : "ログアウト") . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>セッション記録がありません</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>

<?php
$conn->close();
?>

