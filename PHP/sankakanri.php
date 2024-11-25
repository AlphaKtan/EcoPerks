<?php
session_start();
require_once('../Model/dbModel.php');
$pdo = dbConnect();

try {
    // テーブルよりデータを取得する（終了時刻がnullのもののみ取得する場合）
    $sql = "SELECT username, area_id, start_time, end_time FROM cleaning_records";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // 取得したデータを配列として取得
    $cleaningRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 終了時刻がNULLのレコードのみカウント
    $activeRecords = array_filter($cleaningRecords, function($record) {
        return is_null($record['end_time']);
    });
    $totalParticipants = count($activeRecords);

} catch (PDOException $e) {
    echo "データベースエラー: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>参加人数の管理</title>
    <style>
        body {
            background-color: #fff;
            color: #000;
            font-family: Arial, sans-serif;
            transition: background-color 0.3s, color 0.3s;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }

        /* ダークモードのスタイル */
        body.dark-mode {
            background-color: #121212;
            color: #e0e0e0;
        }
        body.dark-mode th {
            background-color: #333;
        }
        body.dark-mode table, body.dark-mode th, body.dark-mode td {
            border: 1px solid #555;
        }
        .toggle-btn {
            margin-bottom: 20px;
            padding: 10px 20px;
            cursor: pointer;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
        }
        .toggle-btn:hover {
            background-color: #0056b3;
        } 
    </style>
</head>
<body>
    <h1>参加人数管理</h1>
    <button class="toggle-btn" onclick="toggleDarkMode()">ダークモード切り替え</button>
    <p>総参加人数: <strong><?php echo $totalParticipants; ?></strong></p>

    <table>
        <thead>
            <tr>
                <th>ユーザー名</th>
                <th>地点ID</th>
                <th>開始時間</th>
                <th>終了時間</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($activeRecords): ?>
                <?php foreach ($activeRecords as $record): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($record['username'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($record['area_id'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($record['start_time'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo is_null($record['end_time']) ? '進行中' : ''; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">進行中の参加記録がありません。</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

     <script>
        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
            // ダークモードが有効かどうかをローカルストレージに保存
            if (document.body.classList.contains('dark-mode')) {
                localStorage.setItem('darkMode', 'enabled');
            } else {
                localStorage.setItem('darkMode', 'disabled');
            }
        }

        // ページ読み込み時にダークモードを適用
        window.onload = function() {
            if (localStorage.getItem('darkMode') === 'enabled') {
                document.body.classList.add('dark-mode');
            }
        }
    </script> 
</body>
</html>




