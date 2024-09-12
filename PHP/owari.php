<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QRコードの更新</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        h3 {
            text-align: center;
            font-size: 1.8em;
            margin: 20px 0;
            color: #2c3e50;
        }

        #qr-codes {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px 0;
        }

        #countdown {
            text-align: center;
            font-size: 1.2em;
            margin: 20px 0;
            padding: 10px;
            color: #fff;
            background-color: #3498db;
            border-radius: 10px;
            display: inline-block;
            width: 300px;
            margin: 0 auto;
        }

        .countdown-text {
            font-weight: bold;
        }

        .qr-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            display: inline-block;
        }

        .qr-container img {
            width: 200px;
            height: 200px;
            margin: 0 auto;
            display: block;
        }

        .footer {
            text-align: center;
            margin: 40px 0;
            font-size: 0.9em;
            color: #7f8c8d;
        }

        @media (max-width: 768px) {
            h3 {
                font-size: 1.5em;
            }

            #countdown {
                font-size: 1em;
                width: auto;
            }

            .qr-container img {
                width: 150px;
                height: 150px;
            }
        }
    </style>

    <script>
        var countdownTime = 60; // カウントダウンの秒数

        // QRコードをqrtime.phpから取得して更新
        function updateQrCodes() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'qrtime2.php', true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    document.getElementById('qr-codes').innerHTML = xhr.responseText;
                    countdownTime = 60; // QRコード更新後にカウントダウンをリセット
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
                    countdownTime = 60; // カウントダウンをリセット
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
    <h3>ゴミ拾い終わり</h3>
    <div id="qr-codes" class="qr-container">
        <!-- ここにQRコードが表示されます -->
    </div>
    <div id="countdown" class="countdown-text">
        <!-- ここにカウントダウンが表示されます -->
    </div>
    <div class="footer">
        &copy; 2024 ゴミ拾いシステム
    </div>
</body>
</html>


