<?php
    session_start(); // セッションを開始
    require '../Model/dbModel.php';

    
    
    // DB接続
    $pdo = dbConnect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservation_date'])) {

        $reservation_date = $_SESSION['reservation_date'];
        $sql = "";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
    
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $_SESSION['reservation_id'] = $_POST["reservation_id"];
        echo <<<HTML
        <form class="yoyaku_form" action="" method="post">
            <h1>予約フォーム</h1>
            <label for="reservation_date">予約日:</label>
            <input type="date" id="reservation_date" name="reservation_date" required>
            <input type="submit" value="時間を確認">
        </form>
        HTML;
    }
?>