<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <script src="../JS/update_time.js" defer></script>
    <title>施設データ</title>
    
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        body.light-mode {
            background-color: white;
            color: black;
        }

        body.dark-mode {
            background-color: #333;
            color: white;
        }

        body.dark-mode th, body.dark-mode td {
            border-color: white;
        }

        body.dark-mode th {
            background-color: #444;
        }

        .mode-toggle {
            margin: 10px;
            padding: 5px 10px;
            cursor: pointer;
        }
    </style>
</head>
<body class="light-mode">
    <h1>施設名称および住所</h1>
    <h1 id="time">現在の時刻:</h1>
    
    <button class="mode-toggle" onclick="toggleDarkMode()">ダークモード切り替え</button>

    <table>
        <thead>
            <tr>
                <th>エリア ID</th>
                <th>施設名称</th>
                <th>住所</th>
            </tr>
        </thead>
        <tbody id="travelData">
            <!-- データがここに表示されます -->
            <?php
            // データベース接続情報
            require_once('../Model/dbModel.php');
            $pdo = dbConnect();

            try {
                // データベースに接続
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // travel_dataテーブルからエリアID順にデータを取得
                $sql = "SELECT * FROM travel_data ORDER BY area_id ASC";
                $stmt = $pdo->query($sql);
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // データをテーブルに表示
                foreach ($data as $row) {
                    echo "<tr>
                            <td>{$row['area_id']}</td>
                            <td>{$row['facility_name']}</td>
                            <td>{$row['address']}</td>
                          </tr>";
                }

            } catch (PDOException $e) {
                echo "データベースエラー: " . $e->getMessage();
            }
            ?>
        </tbody>
    </table>

    <script>
        // データを取得して表示する関数
        function loadTravelData() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', window.location.href, true); // 自身のファイルからデータを再取得
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var parser = new DOMParser();
                    var doc = parser.parseFromString(xhr.responseText, 'text/html');
                    var newTableBody = doc.getElementById('travelData').innerHTML;
                    document.getElementById('travelData').innerHTML = newTableBody;
                }
            };
            xhr.send();
        }

        // ページが読み込まれたときにデータを読み込み、5秒ごとに更新
        window.onload = function() {
            setInterval(loadTravelData, 500); 
        };

        // ダークモードを切り替える関数
        function toggleDarkMode() {
            var body = document.body;
            body.classList.toggle('dark-mode');
            body.classList.toggle('light-mode');
        }
    </script>
</body>
</html>


