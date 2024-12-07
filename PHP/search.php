<?php
session_start();

try {
    require '../Model/dbModel.php';

    // DB接続
    $pdo = dbConnect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //PHP例外フロー

    // URLからエリアIDを取得
    $area_id = 10;
    $_SESSION['area_id'] = $area_id;

    // SQLクエリを準備して実行
    $sql = "SELECT id, facility_name, address FROM travel_data WHERE area_id = :area_id";
    $stmt = $pdo->prepare($sql);
    
    $stmt->bindParam(':area_id', $area_id, PDO::PARAM_INT);
    $stmt->execute();

    // 結果を取得
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <!DOCTYPE html>
    <html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../CSS/indexStyle.css">
        <link rel="stylesheet" href="../CSS/searchStyle.css">        
        <title>施設一覧</title>
    <style>
    label {
        background: #ededed;
        display: flex; /* ラジオボタンとテキストを横並びにする */
        align-items: center; /* 垂直方向に中央揃え */
        margin: 5px;
        padding: 10px;
        border-radius: 5px;
    }

    input[type="radio"] {
        margin-right: 10px; /* ラジオボタンとテキストの間にスペースを追加 */
    }

    </style>
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
        <div class="container">
            <?php
                // 結果を表示
                if (count($results) > 0) {
                    echo <<<HTML
                    <form action="yoyaku.php" method="post">
                    <h1>エリア: $area_id の施設一覧</h1>
                    <button class="link_button" onclick="history.back();">戻る</button>
                    <ul>
                    HTML;
                    
                    foreach ($results as $row) {
                        echo <<<HTML
                        <label for="radio_{$row['id']}">
                            <input type="radio" name="location"  id="radio_{$row['id']}" value="{$row['id']}">
                            {$row['facility_name']}
                        </label>
                        HTML;
                    }
                    echo '<input type="submit" value="送信">';
                    echo "</form>";
                } else {
                    echo "<p>該当する施設はありません。</p>";
                    echo '<button class="link_button" onclick="history.back();">戻る</button>';
                }
            ?>
        </div>
    </body>
    </html>
    <?php
        } catch (PDOException $e) {
            echo "<p>データベースエラー: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    ?>


    <!-- // 結果を表示
//     if (count($results) > 0) {
//         echo "<h1>エリア: $area_id の施設一覧</h1>";
//         echo 'マップに戻る: <a href="https://i2322117.chips.jp/perfectMap.html">こちらをクリック</a>';
//         // echo '予約: <a href="https://i2322117.chips.jp/yoyaku.html">こちらをクリック</a>';
//         echo "<ul>";
//         foreach ($results as $row) {
//             echo "<li>" . htmlspecialchars($row['facility_name']) . " - " . htmlspecialchars($row['address']). " 予約: <a href='https://i2322117.chips.jp/yoyaku.html'>こちらをクリック</a>"."</li>" ;

//         }
//         echo "</ul>";
//     } else {
//         echo "該当する施設はありません。";
//         echo 'マップに戻る: <a href="https://i2322117.chips.jp/perfectMap.html">こちらをクリック</a>';
//     }
// } catch (PDOException $e) {
//     echo "データベースエラー: " . $e->getMessage();
// }
 -->
