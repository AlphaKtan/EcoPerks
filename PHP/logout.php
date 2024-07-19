<?php
// セッションを開始
session_start();

// ログインしているユーザー名を取得
if (isset($_SESSION['username'])) {
    // データベース接続情報
    $servername = "mysql305.phy.lolipop.lan";
    $username = "LAA1516370";
    $password = "ecoperks2024";
    $dbname = "LAA1516370-ecoperks";

    // データベース接続
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("データベースに接続できないちゃんと確認して: " . $conn->connect_error);
    }

    // ユーザー名を取得
    $stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
    $stmt->bind_param("s", $_SESSION['username']);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($username);
        $stmt->fetch();

        // 現在の日時を取得
        $logoutTime = date("Y-m-d H:i:s");

        // `user_sessions`テーブルを更新
        $stmt = $conn->prepare("UPDATE user_sessions SET logout_time = ?, is_logged_in = FALSE WHERE username = ? AND is_logged_in = TRUE");
        $stmt->bind_param("ss", $logoutTime, $username);
        $stmt->execute();

        // セッションを終了
        session_unset();
        session_destroy();
    }

    $stmt->close();
    $conn->close();
}

// ログインページにリダイレクト
header("Location: ../login.html");
exit;


