<?php
session_start();
require_once('../Model/dbModel.php');
$pdo = dbConnect();

// 終了処理用の施設IDセッション取得
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['facility_id'])) {
    $_SESSION['facility_id'] = htmlspecialchars($_POST['facility_id'], ENT_QUOTES, 'UTF-8');
}

// IPアドレスの取得
function getClientIp() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}
$ip_address = getClientIp();

// アクセス時間の取得
$access_time = date('Y-m-d H:i:s');

// ログイン中のユーザー名の取得
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';

// 終了処理時のデータベースへの情報保存
try {
    $sql = "INSERT INTO access_logs (username, ip_address, access_time) 
            VALUES (:username, :ip_address, :access_time)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':ip_address', $ip_address);
    $stmt->bindParam(':access_time', $access_time);
    $stmt->execute();
} catch (PDOException $e) {
    echo "エラー: " . $e->getMessage();
}
?>

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
    background-color: #FFF6E9;
    color: #333;
}

h3 {
    text-align: center;
    font-size: 2rem;
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
    font-size: 1.2rem;
    margin: 20px 0;
    padding: 10px 20px;
    color: #fff;
    background-color: #43AEA9;
    border-radius: 8px;
    display: inline-block;
    width: fit-content;
    animation: pulse 1.5s infinite;
}

.countdown-text {
    font-weight: bold;
}

.qr-container {
    background-color: #fff;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    display: inline-block;
    transition: transform 0.2s, box-shadow 0.2s;
}

.qr-container:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 25px rgba(0, 0, 0, 0.15);
}

.qr-container img {
    width: 80%;
    max-width: 500px;
    height: auto;
    margin: 0 auto;
    display: block;
}

.footer {
    text-align: center;
    margin: 40px 0;
    font-size: 0.9rem;
    color: #7f8c8d;
}

/* Responsive Design */
@media (max-width: 768px) {
    h3 {
        font-size: 1.5rem;
    }

    #countdown {
        font-size: 1rem;
        width: auto;
    }

    .qr-container img {
        width: 90%;
    }
}

@media (max-width: 480px) {
    h3 {
        font-size: 1.2rem;
    }

    #countdown {
        font-size: 0.9rem;
    }
}

/* Animations */
@keyframes pulse {
    0% {
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.4);
    }
    50% {
        box-shadow: 0 0 20px rgba(0, 123, 255, 0.6);
    }
    100% {
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.4);
    }
}
    </style>

    <script>
        var countdownTime = 60; // カウントダウンの秒数

        // QRコードをqrtime2.phpから取得して更新
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
    <h3>Cleaning Finish QR code</h3>
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

