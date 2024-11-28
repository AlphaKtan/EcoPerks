<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者向け総合ページ</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        h1 {
            font-size: 36px; /* タイトルの文字サイズ */
            margin-bottom: 30px;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin: 15px 0;
        }
        a {
            text-decoration: none;
            color: #007bff;
            font-size: 24px; /* リンクの文字サイズ */
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>管理者向け総合ページ</h1>
    <ul>
        <li><a href="admin.php">管理者ページ</a></li>
        <li><a href="adminqr.php">QRコード生成ページ</a></li>
        <li><a href="adminqr_end.php">QRコード終了管理</a></li>
        <li><a href="data.php">データ管理ページ</a></li>
        <li><a href="access_log.php">アクセスログ表示ページ</a></li>
        <li><a href="sankakanri.php">参加者管理ページ</a></li>
        <li><a href="shift.php">シフトページ</a></li>
        <li><a href="time_change.php">シフト削除</a></li>
    </ul>
</body>
</html>

