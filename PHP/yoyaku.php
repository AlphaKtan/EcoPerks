<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/indexStyle.css">
    <link rel="stylesheet" href="../CSS/yoyakuStyle.css">
    <title>予約フォーム</title>
</head>
<body>

    <header>
        <div class="flexBox">
            <div class="menu">
            </div>
            <div class="logo">
                <img src="../img/logo.jpg" alt="" class="logo2">
            </div>
            <div class="icon"></div>
        </div>
    </header>

    <?php
    // データベース接続情報
    require_once('db_local.php'); // データベース接続

    try {
        // データベースに接続
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbUsername, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // フォームから送信されたデータを取得
            $user_id = 1; // 仮のユーザーID（本来はログインシステムから取得）

            if (isset($_GET['location'])) {
                // URLからエリアIDを取得
                $location = $_GET['location'];
            } else {
                throw new Exception("locationが指定されていません。");
            }

            // SQLクエリを準備して実行
            $locationSql = "SELECT id, facility_name, address FROM travel_data WHERE id = :location";
            $newStmt = $pdo->prepare($locationSql);
            $newStmt->bindParam(':location', $location, PDO::PARAM_INT);
            $newStmt->execute();

            // 結果を取得
            $row = $newStmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $facility_name = $row['facility_name'];
            } else {
                throw new Exception("指定された施設が見つかりません。");
            }

            // フォームから送信されたデータを取得
            $reservation_date = $_POST['reservation_date'];
            $start_time = $_POST['start_time'];
            $end_time = $_POST['end_time'];

            // 予約の重複チェック
            $sql = "SELECT * FROM yoyaku WHERE reservation_date = :reservation_date AND 
                    ((start_time <= :start_time AND end_time > :start_time) OR 
                     (start_time < :end_time AND end_time >= :end_time))";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':reservation_date', $reservation_date);
            $stmt->bindParam(':start_time', $start_time);
            $stmt->bindParam(':end_time', $end_time);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $pop ="<p>この時間帯は既に予約されています。別の時間を選択してください。</p>";
            } else {
                // SQLクエリを準備して実行
                $sql = "INSERT INTO yoyaku (username, reservation_date, start_time, end_time, location) 
                        VALUES (:username, :reservation_date, :start_time, :end_time, :location)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':username', $user_id, PDO::PARAM_INT);
                $stmt->bindParam(':reservation_date', $reservation_date, PDO::PARAM_STR);
                $stmt->bindParam(':start_time', $start_time, PDO::PARAM_STR);
                $stmt->bindParam(':end_time', $end_time, PDO::PARAM_STR);
                $stmt->bindParam(':location', $facility_name, PDO::PARAM_STR);
                $stmt->execute();

                $pop = "<p>予約が正常に完了しました！</p>";
            }
        }
    } catch (PDOException $e) {
        echo "<p>データベースエラー: " . $e->getMessage() . "</p>";
    } catch (Exception $e) {
        echo "<p>エラー: " . $e->getMessage() . "</p>";
    }
    ?>

    <form class="yoyaku_form" action="" method="post">
        <?php 
            if (isset($pop)) {
                echo "<p>$pop</p>";
            } 
        ?>
        <h1>予約フォーム</h1>
        <label for="reservation_date">予約日:</label>
        <input type="date" id="reservation_date" name="reservation_date" required>
    
        <label for="start_time">開始時間:</label>
        <input type="time" id="start_time" name="start_time" required>
    
        <label for="end_time">終了時間:</label>
        <input type="time" id="end_time" name="end_time" required>
    
        <input type="submit" value="予約する">
    </form>
</body>
</html>
