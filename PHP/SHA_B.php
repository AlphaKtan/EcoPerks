<?php
if ($argc > 1) {
    $targetHash = $argv[1];

    // 衝突テストを行う処理
    $collisionFound = false;
    $attempts = 0;

    while (!$collisionFound) {
        $randomString = bin2hex(random_bytes(8));
        $hash = hash('sha256', $randomString);
        $attempts++;

        if ($hash === $targetHash) {
            echo "衝突が見つかりました！\n";
            echo "衝突した文字列: " . $randomString . "\n";
            echo "ターゲットハッシュ: " . $targetHash . "\n";
            echo "試行回数: " . $attempts . "\n";
            $collisionFound = true;
            break;
        }

        // 1000回ごとに進行状況を表示（実際のWeb表示ではなく、ログに出力など）
        if ($attempts % 1000 == 0) {
            // データベースにログを保存するなど、実際のプロジェクトに合わせた進行表示
        }
    }
}
