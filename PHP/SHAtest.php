<?php
set_time_limit(0); // 最大実行時間を無制限に設定

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $targetHash = $_POST["targetHash"];

    // ハッシュ値の検証（64文字の16進数）
    if (!preg_match('/^[a-f0-9]{64}$/i', $targetHash)) {
        echo "無効なSHA-256ハッシュ値です。";
        exit;
    }

    // 衝突テストを行う
    $collisionFound = false;
    $attempts = 0;

    // ヘッダー出力（ブラウザで即時に表示するために重要）
    header('Content-Type: text/html; charset=utf-8');
    echo "<pre>"; // 改行や整形用

    while (!$collisionFound) {
        // ランダムな文字列を生成
        $randomString = bin2hex(random_bytes(8));  // ランダムな16進数文字列
        $hash = hash('sha256', $randomString);
        $attempts++;

        // 衝突が見つかった場合
        if ($hash === $targetHash) {
            echo "衝突が見つかりました！\n";
            echo "衝突した文字列: " . $randomString . "\n";
            echo "ターゲットハッシュ: " . $targetHash . "\n";
            echo "試行回数: " . $attempts . "\n";
            $collisionFound = true;
            break;
        }

        // 1000回ごとに進行状況を表示
        if ($attempts % 1000 == 0) {
            echo "進行中: " . $attempts . "回目の試行...\n";
            echo "途中の文字列: " . $randomString . "\n"; // 途中の文字列を表示
            flush(); // 出力バッファを強制的に送信
        }
    }

    echo "</pre>";
}
