<?php
session_start();

// セッションをクリアする関数
function clearSession() {
    $_SESSION = array(); // セッション変数を空の配列にする
    session_destroy(); // セッションを完全に破棄する
}

// リクエストがGETかつjson=trueパラメータが付いている場合に実行
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['json']) && $_GET['json'] === 'true') {
    // 設定
    $charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $charsetLength = strlen($charset);

    // インデックスと長さの初期化
    if (!isset($_SESSION['index'])) {
        $_SESSION['index'] = -1; // 最初の文字列が 'a' から始まるために -1 に設定
    }
    if (!isset($_SESSION['length'])) {
        $_SESSION['length'] = 1;
    }

    // 文字列を順番に生成
    function generateSequentialString($index, $length, $charset, $charsetLength) {
        $sequentialString = '';
        $base = $charsetLength;

        while ($length > 0) {
            $sequentialString .= $charset[$index % $base];
            $index = intdiv($index, $base);
            $length--;
        }

        return $sequentialString;
    }

    // 次の文字列のためのインデックスを更新
    $input = generateSequentialString($_SESSION['index'] + 1, $_SESSION['length'], $charset, $charsetLength); // インデックスを修正して +1 にする
    $hash = hash('sha256', $input);

    $_SESSION['index']++;
    if ($_SESSION['index'] >= pow($charsetLength, $_SESSION['length'])) {
        $_SESSION['index'] = 0;
        $_SESSION['length']++;
    }

    // JSON形式で結果を返す
    header('Content-Type: application/json');
    echo json_encode(['input' => $input, 'hash' => $hash]);
    exit;
}

// フォームがPOSTで送信された場合の処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['targetHash'])) {
    clearSession(); // セッションをクリアする
    exit; // リクエスト終了
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>SHA-256 Collision Search</title>
    <style>
        body { font-family: Arial, sans-serif; }
        #output { white-space: pre; font-size: 1.5em; } /* 文字の大きさを1.2emに設定 */
        h1 { font-size: 1.5em; } /* 見出しの大きさを1.5emに設定 */
        label { font-size: 1.7em; } /* ラベルの大きさを1.1emに設定 */
        input, button { font-size: 1em; } /* 入力欄とボタンの大きさを1emに設定 */
    </style>
</head>
<body>
    <h1>SHA-256 Collision Search</h1>
    <form id="hashForm">
        <label for="targetHash">ターゲットハッシュ:</label>
        <input type="text" id="targetHash" name="targetHash" required>
        <button type="submit">検索開始</button>
    </form>
    <div id="output">Start by entering a target hash and clicking "検索開始".</div>

    <script>
        let intervalId;

        async function fetchHash() {
            try {
                const response = await fetch(window.location.href + '?json=true');
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const data = await response.json();
                return data;
            } catch (error) {
                console.error('Fetch Error:', error);
                throw error;
            }
        }

        async function startSearch(targetHash) {
            let attempts = 0;
            const startTime = Date.now();

            intervalId = setInterval(async () => {
                try {
                    const data = await fetchHash();
                    attempts++;

                    const elapsedTime = (Date.now() - startTime) / 1000;
                    const output = document.getElementById('output');

                    if (data.hash === targetHash) {
                        output.textContent = `Collision found!\nInput: ${data.input}\nHash: ${data.hash}\nAttempts: ${attempts}\nElapsed time: ${elapsedTime} seconds\n`;
                        clearInterval(intervalId);
                    } else {
                        output.textContent = `Attempts: ${attempts}\nCurrent input: ${data.input}\nCurrent hash: ${data.hash}\nElapsed time: ${elapsedTime} seconds\n`;
                    }
                } catch (error) {
                    console.error('Interval Error:', error);
                    clearInterval(intervalId);
                }
            }, 1); // ms
        }

        document.getElementById('hashForm').addEventListener('submit', function(event) {
            event.preventDefault(); // フォームのデフォルトの送信動作を防ぐ
            const targetHash = document.getElementById('targetHash').value.trim();
            document.getElementById('output').textContent = 'Searching...';
            if (intervalId) clearInterval(intervalId); // 既存の検索をクリア
            startSearch(targetHash);

            // フォームを送信してセッションをクリアする
            fetch(window.location.href, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'targetHash=' + encodeURIComponent(targetHash),
            }).catch(error => {
                console.error('Clear Session Error:', error);
            });
        });
    </script>
</body>
</html>

