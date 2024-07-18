<?php
session_start();

// 正しいパスワード（適宜変更してください）
$correctPassword = 'eco2024';

// フォームから送信されたパスワードを取得
$providedPassword = isset($_POST['password']) ? $_POST['password'] : '';

// デバッグ用
// echo "Provided Password: $providedPassword <br>";
// echo "Correct Password: $correctPassword <br>";

// パスワードが正しいか比較（厳密な比較演算子を使用）
if ($providedPassword === $correctPassword) {
    // 認証成功時の処理

    // セッションに認証情報を保存
    $_SESSION['authenticated'] = true;

    // リダイレクト先を指定してリダイレクト
    header('Location: admin.php');
    exit;
} else {
    // 認証失敗時の処理

    // デバッグ用
    // echo "Authentication failed. Redirecting2... <br>";

    // 認証失敗を示すためにauth.phpにリダイレクト
    header('Location: auth.php?error=1');
    exit;
}




