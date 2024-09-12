<?php
    session_start(); // セッションを開始
    $location = $_SESSION['location'];
    // データベース接続情報
    $servername = "localhost";
    $dbUsername = "root";
    $password = "";
    $dbname = "ecoperks";

    try {
        // データベースに接続
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbUsername, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // フォームから送信されたデータを取得
            $user_id = 1; // 仮のユーザーID（本来はログインシステムから取得）
            $reservation_date = $_POST['reservation_date'];

            // エリア情報の取得
            $locationSql = "SELECT id, facility_name, address FROM travel_data WHERE id = :location";
            $newStmt = $pdo->prepare($locationSql);
            $newStmt->bindParam(':location', $location, PDO::PARAM_INT);
            $newStmt->execute();

            $row = $newStmt->fetch(PDO::FETCH_ASSOC);
            // デバッグ用の出力
            echo "<pre>";
            print_r($row);
            echo "</pre>";

            if ($row) {
                $facility_name = $row['facility_name'];
            } else {
                throw new Exception("指定された施設が見つかりません。");
            }

            $status = 1;

            // 時間帯の取得
            $timeSql = "SELECT DATE_FORMAT(start_time, '%H') AS hour_only, DATE_FORMAT(end_time, '%H') AS hour_only_end FROM time_change
                        WHERE DATE_FORMAT(start_time, '%Y-%m-%d') = :reservation_date AND facility_name = :facility_name AND status = :status";
            $timeStmt = $pdo->prepare($timeSql);
            $timeStmt->bindParam(':reservation_date', $reservation_date, PDO::PARAM_STR);
            $timeStmt->bindParam(':facility_name', $facility_name, PDO::PARAM_STR);
            $timeStmt->bindParam(':status', $status, PDO::PARAM_INT);

            $timeStmt->execute();

            $rowtimes = $timeStmt->fetchAll(PDO::FETCH_ASSOC);

            // デバッグ用の出力
            echo "<pre>";
            print_r($rowtimes);
            echo "</pre>";

            if (count($rowtimes) > 0) {
                $count = 1;
            } else {
                throw new Exception("指定された時間が見つかりません。");
            }

            // 予約処理
            if ($count > 1) {
                // SQLクエリを準備して実行
                $sql = "INSERT INTO yoyaku (username, reservation_date, start_time, end_time, location) 
                        VALUES (:username, :reservation_date, :start_time, :end_time, :location)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':username', $user_id, PDO::PARAM_INT);
                $stmt->bindParam(':reservation_date', $reservation_date, PDO::PARAM_STR);
                $stmt->bindParam(':start_time', $start_time, PDO::PARAM_STR);
                $stmt->bindParam(':end_time', $end_time, PDO::PARAM_STR);
                $stmt->bindParam(':location', $facility_name, PDO::PARAM_STR);

                if (empty($start_time) || empty($end_time)) {
                    throw new Exception("開始時間または終了時間が設定されていません。");
                }

                $stmt->execute();

                if (isset($rowtimes) && count($rowtimes) > 0) {
                    foreach ($rowtimes as $rowtime) {
                        $start_time = $rowtime['hour_only'];
                        $end_time = $rowtime['hour_only_end'];
                        echo <<<HTML
                        <div class='timeSelect'> $start_time 時 ～ $end_time 時</div>
                        HTML;
                    }
                } else {
                    echo "<p>指定された時間が見つかりません。</p>";
                }

                $pop = "<p>予約が正常に完了しました！</p>";
            }
        }
    } catch (PDOException $e) {
        echo "<p>データベースエラー: " . $e->getMessage() . "</p>";
    } catch (Exception $e) {
        echo "<p>エラー: " . $e->getMessage() . "</p>";
    }
?>