<?php
// データベース接続情報
$servername = "localhost";  // データベースサーバーのホスト名
$dbUsername = "root";  // データベースのユーザー名
$password = "";  // データベースのパスワード
$dbname = "ecoperks";  // データベース名

try {
    // データベースに接続
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbUsername, $password);
    // エラーモードを例外に設定
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // 接続失敗時にエラーメッセージを表示
    echo "データベース接続エラー: " . $e->getMessage();
    exit;  // 処理を終了
}


