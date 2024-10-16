<?php
    // 上記のコードに追加する形で、削除処理を実装
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
        $delete_id = $_POST['delete_id'];
        try {
            require_once('dbModel.php');
            $pdo = dbConnect();
            // 削除クエリの準備
            $deleteSql = "DELETE FROM yoyaku WHERE id = :delete_id";
            $deleteStmt = $pdo->prepare($deleteSql);
            $deleteStmt->bindParam(':delete_id', $delete_id, PDO::PARAM_INT);
            $deleteStmt->execute();

            // 削除後のリダイレクトや確認メッセージ
            echo "<p>予約が削除されました。</p>";
            // リロードしてリストを更新する
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } catch (PDOException $e) {
            echo "<p>削除エラー: " . $e->getMessage() . "</p>";
        }
    }
?>