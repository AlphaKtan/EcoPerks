<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['area'])) {
    $area = $_POST['area'];
    require_once('db_local.php'); // データベース接続

    try {
        // データベースに接続
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbUsername, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // travel_dataからfacility_nameを取得
        $locationsql = "SELECT id, area_id, facility_name FROM travel_data WHERE area_id = :area_id";
        $locationstmt = $pdo->prepare($locationsql);
        $locationstmt->bindParam(':area_id', $area, PDO::PARAM_INT);
        $locationstmt->execute();
        $locationRow = $locationstmt->fetchAll(PDO::FETCH_ASSOC);

        $status = 1;

        if ($locationRow) {
            $json_fileName = "../json/time_change.json";

            // JSONファイルを配列形式で読み込む
            if (file_exists($json_fileName)) {
                $existingData = file_get_contents($json_fileName);
                $jsonArray = json_decode($existingData, true);
                
                // ファイルが空または無効な場合は空の配列をセット
                if (!$jsonArray) {
                    $jsonArray = [];
                }
            } else {
                $jsonArray = [];
            }

            foreach ($locationRow as $k) {
                // 各facility_nameごとに関連するtime_changeのデータを取得
                $sql = "SELECT status, start_time, end_time, facility_name FROM time_change WHERE facility_name = :facility_name AND areaid = :area_id AND status = :status";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':facility_name', $k['facility_name'], PDO::PARAM_STR);
                $stmt->bindParam(':area_id', $area, PDO::PARAM_INT);
                $stmt->bindParam(':status', $status, PDO::PARAM_INT);
                $stmt->execute();
                $areaRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // 施設名を表示
                // echo '<br>施設名: ' . htmlspecialchars($k['facility_name']) . '<br>';
                
                // 開始時間と終了時間を表示
                if ($areaRows) {
                    foreach ($areaRows as $row) {
                        // echo '開始時間: ' . htmlspecialchars($row['start_time']) . ' ～ ' . htmlspecialchars($row['end_time'])  . ' 状態 ' . htmlspecialchars($row['status']) . '<br>';

                        // $k と $row を1つの配列にして追加
                        $combinedData = [
                            'facility_info' => $k,  // $kのデータ
                            'time_info' => $row     // $rowのデータ
                        ];

                        // 取得したデータを配列に追加
                        $jsonArray[] = $combinedData;
                    }
                } else {
                    echo '該当する時間のデータがありません。<br>';
                }
            }

            // 配列全体をJSON形式でファイルに書き込む
            echo json_encode($jsonArray, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        } else {
            echo '該当するエリア情報がありません。';
        }

    } catch (PDOException $e) {
        echo "<p>データベースエラー: " . $e->getMessage() . "</p>";
    } catch (Exception $e) {
        echo "<p>エラー: " . $e->getMessage() . "</p>";
    }
}

?>
