<?php
// セッションを開始
session_start();

// セッションを破棄してログアウトする
session_destroy();

// ログインページにリダイレクトする
header('Location: auth.php');
exit;
