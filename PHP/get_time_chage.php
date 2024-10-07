<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['area'])) {
    $area = $_POST['area'];
    try {
        require_once('../Model/dbModel.php');
        // データベースに接続
        $pdo = dbConnect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // travel_dataからfacility_nameを取得
        $locationRow = getFacilityNames($pdo, $area);

        $status = 1;
        if ($locationRow) {
            foreach ($locationRow as $k) {
                // 各facility_nameごとに関連するtime_changeのデータを取得
                $areaRows = timeDisplay($pdo, $k['facility_name'], $status, $area);

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
            outputJson($noDataFound, $jsonArray);

        } else {
            // travel_dataからすべてのfacility_nameを取得
            $locationRow = getFacilityNames($pdo);

            foreach ($locationRow as $k) {
                // 各facility_nameごとに関連するtime_changeのデータを取得
                $areaRows = timeDisplay($pdo, $k['facility_name'], $status);

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
            outputJson($noDataFound, $jsonArray);
        }
                

    } catch (PDOException $e) {
        echo "<p>データベースエラー: " . $e->getMessage() . "</p>";
    } catch (Exception $e) {
        echo "<p>エラー: " . $e->getMessage() . "</p>";
    }
}

?>
