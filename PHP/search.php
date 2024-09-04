<?php
// データベース接続情報
$servername = "mysql305.phy.lolipop.lan";
$dbUsername = "LAA1516370";
$password = "ecoperks2024";
$dbname = "LAA1516370-ecoperks";

try {
    // データベースに接続
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbUsername, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // URLからエリアIDを取得
    $area_id = $_GET['area_id'];

    // SQLクエリを準備して実行
    $sql = "SELECT facility_name, address FROM travel_data WHERE area_id = :area_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':area_id', $area_id, PDO::PARAM_INT);
    $stmt->execute();

    // 結果を取得
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 結果を表示
    if (count($results) > 0) {
        echo "<h1>エリアID: $area_id の施設一覧</h1>";
        echo 'マップに戻る: <a href="https://i2322117.chips.jp/perfectMap.html">こちらをクリック</a>';
        echo "<ul>";
        foreach ($results as $row) {
            echo "<li>" . htmlspecialchars($row['facility_name']) . " - " . htmlspecialchars($row['address']) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "該当する施設はありません。";
    }
} catch (PDOException $e) {
    echo "データベースエラー: " . $e->getMessage();
}


