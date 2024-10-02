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
            foreach ($locationRow as $k) {
                // 各facility_nameごとに関連するtime_changeのデータを取得
                $sql = "SELECT status, start_time, end_time, facility_name FROM time_change WHERE facility_name = :facility_name AND areaid = :area_id AND status = :status";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':facility_name', $k['facility_name'], PDO::PARAM_STR);
                $stmt->bindParam(':area_id', $area, PDO::PARAM_INT);
                $stmt->bindParam(':status', $status, PDO::PARAM_INT);
                $stmt->execute();
                $areaRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // 開始時間と終了時間を表示
                if ($areaRows) {
                    foreach ($areaRows as $row) {
                        // $k と $row を1つの配列にして追加
                        $combinedData = [
                            'facility_info' => $k,  // $kのデータ
                            'time_info' => $row     // $rowのデータ
                        ];

                        // 取得したデータを配列に追加
                        $jsonArray[] = $combinedData;
                        $noDataFound = false; // データが見つかったのでフラグをfalseに
                    }
                } else {
                    $noDataFound = true; // データが見つからなかったのでフラグをtrueに
                }
            }
            // 取得したデータがあればJSONで出力
            if (!$noDataFound) {
                echo json_encode($jsonArray, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            } else {
                echo '該当する時間のデータがありません。<br>';
            }

        } else {
            // travel_dataからすべてのfacility_nameを取得
            $locationsql = "SELECT id, area_id, facility_name FROM travel_data";
            $locationstmt = $pdo->prepare($locationsql);
            $locationstmt->execute();
            $locationRow = $locationstmt->fetchAll(PDO::FETCH_ASSOC);

            // echo '<pre>';
            // var_dump( $locationRow );
            // echo '</pre>';

            foreach ($locationRow as $k) {
                // 各facility_nameごとに関連するtime_changeのデータを取得
                $sql = "SELECT status, start_time, end_time, facility_name FROM time_change WHERE facility_name = :facility_name AND status = :status";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':facility_name', $k['facility_name'], PDO::PARAM_STR);
                $stmt->bindParam(':status', $status, PDO::PARAM_INT);
                $stmt->execute();
                $areaRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // 開始時間と終了時間を表示
                if ($areaRows) {
                    foreach ($areaRows as $row) {
                        // $k と $row を1つの配列にして追加
                        $combinedData = [
                            'facility_info' => $k,  // $kのデータ
                            'time_info' => $row     // $rowのデータ
                        ];

                        // 取得したデータを配列に追加
                        $jsonArray[] = $combinedData;
                        $noDataFound = false; // データが見つかったのでフラグをfalseに
                    }
                } else {
                    $noDataFound = true; // データが見つからなかったのでフラグをtrueに
                }
            }
            // 取得したデータがあればJSONで出力
            if (!$noDataFound) {
                echo json_encode($jsonArray, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            } else {
                echo '該当する時間のデータがありません。<br>';
            }

        }
                

    } catch (PDOException $e) {
        echo "<p>データベースエラー: " . $e->getMessage() . "</p>";
    } catch (Exception $e) {
        echo "<p>エラー: " . $e->getMessage() . "</p>";
    }
}

function displayArea() {
    
}

?>
