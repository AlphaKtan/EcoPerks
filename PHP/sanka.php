<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QRコードの更新</title>
    <script>
        function updateQrCodes() {
            // QRコードをqrtime.phpから取得
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'qrtime.php', true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    // QRコードの内容をdivに更新
                    document.getElementById('qr-codes').innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }

        // ページが読み込まれたらQRコードを60秒ごとに更新
        window.onload = function() {
            updateQrCodes();
            setInterval(updateQrCodes, 3000); // ミリ秒
        };
    </script>
</head>
<body>
    <h3>ゴミ拾い開始・終了用QRコード</h3>
    <div id="qr-codes">
        <!-- ここにQRコードが表示されます -->
    </div>
</body>
</html>



