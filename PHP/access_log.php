<?php
session_start();
require_once('../Model/dbModel.php');
$pdo = dbConnect();

// 検索クエリ処理
$searchUsername = isset($_GET['search_username']) ? trim($_GET['search_username']) : '';
$searchTime = isset($_GET['search_time']) ? trim($_GET['search_time']) : '';

try {
    $sql = "SELECT * FROM access_logs WHERE 1=1"; // 条件追加用のプレースホルダー

    if ($searchUsername !== '') {
        $sql .= " AND username LIKE :username";
    }
    if ($searchTime !== '') {
        $sql .= " AND DATE(access_time) = :access_time"; // 日付で一致
    }

    $sql .= " ORDER BY access_time DESC";
    $stmt = $pdo->prepare($sql);

    if ($searchUsername !== '') {
        $stmt->bindValue(':username', "%$searchUsername%");
    }
    if ($searchTime !== '') {
        $stmt->bindValue(':access_time', $searchTime);
    }

    $stmt->execute();
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "エラー: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アクセスログ管理</title>
    <style>
        /* 共通スタイル */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            transition: background-color 0.3s, color 0.3s;
        }
        h3 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s, color 0.3s;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #4CAF50;
            color: white;
        }
        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        table tr:hover {
            background-color: #f1f1f1;
        }
        .search-container {
            margin: 10px 0;
            text-align: center;
        }
        .search-container input[type="text"] {
            width: 200px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .search-container button {
            padding: 8px 12px;
            border: none;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            border-radius: 4px;
            margin-left: 5px;
        }
        .search-container button:hover {
            background-color: #45a049;
        }
        .dark-mode {
            background-color: #121212;
            color: #ffffff;
        }
        .dark-mode table {
            background-color: #444;
        }
        .dark-mode table th {
            background-color: #72f400;   /*表の上*/
            color: black;
        }
        .dark-mode table tr:nth-child(even) {
            background-color: #555;
        }
        .dark-mode table tr:hover {
            background-color: #666;
        }
        .dark-mode-toggle {
            text-align: center;
            margin-bottom: 10px;
        }
        .dark-mode-toggle button {
            padding: 10px 20px;
            border: none;
            background-color: #333;
            color: white;
            cursor: pointer;
            border-radius: 4px;
        }
        .dark-mode-toggle button:hover {
            background-color: #555;
        }
    </style>
    <script>
        // ダークモード切り替えスクリプト
        function toggleDarkMode() {
            const body = document.body;
            const currentMode = localStorage.getItem('darkMode') || 'light';

            if (currentMode === 'light') {
                body.classList.add('dark-mode');
                localStorage.setItem('darkMode', 'dark');
            } else {
                body.classList.remove('dark-mode');
                localStorage.setItem('darkMode', 'light');
            }
        }

        // ページ読み込み時にモードを適用
        window.onload = function () {
            if (localStorage.getItem('darkMode') === 'dark') {
                document.body.classList.add('dark-mode');
            }
        }
    </script>
</head>
<body>
    <div class="dark-mode-toggle">
        <button onclick="toggleDarkMode()">ダークモード切り替え</button>
    </div>

    <h3>アクセスログ一覧</h3>
    
    <!-- 検索フォーム -->
    <div class="search-container">
        <form method="GET">
            <input type="text" name="search_username" placeholder="ユーザー名で検索" value="<?= htmlspecialchars($searchUsername) ?>">
            <input type="text" name="search_time" placeholder="時間で検索 (例: 2024-11-19)" value="<?= htmlspecialchars($searchTime) ?>">
            <button type="submit">検索</button>
        </form>
    </div>

    <table>
        <tr>
            <th>ユーザー名</th>
            <th>IPアドレス</th>
            <th>アクセス時間</th>
        </tr>
        <?php if (!empty($logs)): ?>
            <?php foreach ($logs as $log): ?>
            <tr>
                <td><?= htmlspecialchars($log['username']) ?></td>
                <td><?= htmlspecialchars($log['ip_address']) ?></td>
                <td><?= htmlspecialchars($log['access_time']) ?></td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4" style="text-align: center;">該当するデータがありません。</td>
            </tr>
        <?php endif; ?>
    </table>
</body>
</html>

