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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン状況</title>
    <script src="../JS/update_time.js" defer></script>
    <script src="../JS/update_data.js" defer></script> <!-- JSファイルを追加 -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            overflow-x: hidden; /* 全体の横スクロールを防止 */
        }

        .large-button {
            font-size: 18px; /* PC版のボタンサイズ */
            padding: 12px 24px;
            cursor: pointer;
            display: block;
            margin: 10px 0;
        }

        .large-input {
            font-size: 18px; /* PC版の入力サイズ */
            padding: 12px;
            width: 100%;
            max-width: 300px;
            box-sizing: border-box;
        }

        .table-container {
            margin: 20px 0;
            overflow-x: auto; /* 横スクロールを許可 */
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
            font-size: 16px; /* PC版のフォントサイズ */
        }

        th {
            background-color: #f2f2f2;
        }

        /* スマホ対応 */
        @media (max-width: 600px) {
            .large-button {
                font-size: 14px; /* スマホ版のボタンサイズ */
                padding: 8px 16px;
            }

            .large-input {
                font-size: 14px; /* スマホ版の入力サイズ */
                padding: 8px;
                max-width: 100%;
            }

            table {
                font-size: 12px; /* スマホ版のフォントサイズ */
                width: 100%; /* テーブル幅を100%に */
            }

            th, td {
                padding: 8px;
                font-size: 12px; /* スマホ版のテーブルフォントサイズ */
            }

            /* スマホ版で時刻を折り返す設定 */
            td.login-time, td.logout-time {
                white-space: normal; /* テキストの折り返しを許可 */
                word-break: break-word; /* 単語単位での折り返しを許可 */
            }

            /* 日付と時刻を分けて表示 */
            .time-wrapper {
                display: flex;
                flex-direction: column; /* 縦方向に並べる */
                line-height: 1.2; /* 行間を狭める */
            }
        }
    </style>
</head>
<body>
    <h1>ログイン状況</h1>
    <h1 id="time">現在の時刻:</h1>

    <!-- 検索フォーム -->
    <form method="post">
        <label for="searchUsername">ユーザー名で検索:</label>
        <input type="text" id="searchUsername" name="searchUsername" class="large-input" value="<?php echo htmlspecialchars($searchUsername, ENT_QUOTES); ?>">
        <input type="submit" class="large-button" value="検索">
    </form>
    
    <!-- テーブルコンテナ -->
    <div class="table-container">
        <table id="data-table">
            <tr>
                <th>ユーザー名</th>
                <th>ログイン時間</th>
                <th>ログアウト時間</th>
                <th>ログイン状態</th>
            </tr>
            <!-- データはここに動的に挿入 -->
        </table>
    </div>
    
    <form action="adminlogout.php" method="post">
        <input type="submit" class="large-button" value="ログアウト">
    </form>
</body>
</html>





