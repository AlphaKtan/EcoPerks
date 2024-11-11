<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>SHA-256 衝突テスト（非同期）</title>
</head>
<body>
    <h2>SHA-256 衝突テスト（非同期）</h2>
    <form id="hashForm">
        <label for="targetHash">ターゲットハッシュ値を入力してください:</label>
        <input type="text" id="targetHash" name="targetHash" required>
        <button type="submit">テスト開始</button>
    </form>

    <div id="status"></div>

    <script>
        document.getElementById('hashForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const targetHash = document.getElementById('targetHash').value;

            // 非同期リクエストを送信
            fetch('SHAtest.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'targetHash=' + encodeURIComponent(targetHash)
            })
            .then(response => response.text())
            .then(data => {
                document.getElementById('status').innerHTML = data;
            })
            .catch(error => {
                document.getElementById('status').innerHTML = 'エラーが発生しました: ' + error;
            });
        });
    </script>
</body>
</html>