<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QRコードの更新</title>
    <script>
        var countdownTime = 10; // カウントダウンの秒数

        // QRコードをqrtime.phpから取得して更新
        function updateQrCodes() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'qrtime.php', true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    document.getElementById('qr-codes').innerHTML = xhr.responseText;
                    countdownTime = 10; // QRコード更新後にカウントダウンをリセット
                }
            };
            xhr.send();
        }

        // カウントダウンを開始
        function startCountdown() {
            setInterval(function() {
                if (countdownTime > 0) {
                    document.getElementById('countdown').textContent = "QRコード更新まで: " + countdownTime + "秒";
                    countdownTime--;
                } else {
                    updateQrCodes(); // カウントが0になったらQRコードを更新
                    countdownTime = 10; // カウントダウンをリセット
                }
            }, 1000); // 1秒ごとにカウントダウン
        }

        // ページが読み込まれたらQRコードを取得し、カウントダウンを開始
        window.onload = function() {
            updateQrCodes();
            startCountdown();
        };
    </script>
</head>
<body>
    <h3>ゴミ拾い開始・終了用QRコード</h3>
    <div id="qr-codes">
        <!-- ここにQRコードが表示されます -->
    </div>
    <div id="countdown">
        <!-- ここにカウントダウンが表示されます -->
    </div>
</body>
</html>