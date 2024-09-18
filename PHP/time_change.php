<?php
    require_once('db_local.php'); // データベース接続

try {
        // データベースに接続
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbUsername, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $sql = "SELECT id, start_time, end_time, facility_name, areaid, status FROM time_change";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (isset($row)) {
            $start_time = $row['start_time'];
            $end_time = $row['end_time'];
            $facility_name = $row['facility_name'];
            $areaid = $row['areaid'];
            $status = $row['status'];

            foreach ($row as $rowArea) {
                $areaid = $rowArea['areaid'];
                echo <<<HTML
                <select name="area">
                    <option value="{$areaid}">エリア$areaid</option>
                </select>
                HTML;
            }

        } else {
            echo '時間が挿入されてません';
        }



        } catch (PDOException $e) {
            echo "<p>データベースエラー: " . $e->getMessage() . "</p>";
        } catch (Exception $e) {
            echo "<p>エラー: " . $e->getMessage() . "</p>";
        }
    ?>