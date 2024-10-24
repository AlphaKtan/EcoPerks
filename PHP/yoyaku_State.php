<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/indexStyle.css">
    <link rel="stylesheet" href="../CSS/yoyakukakuninStyle.css">
    <title>予約確認フォーム</title>
</head>
<body>

    <!-- <header>
        <div class="flexBox">
            <div class="menu">
            </div>
            <div class="logo">
                <img src="../img/logo.jpg" alt="" class="logo2">
            </div>
            <div class="icon"></div>
        </div>
    </header> -->
    <?php
    // データベース接続情報
    require_once('db_connection.php');
    //require_once('db_local.php'); // データベース接続

    $location = '';
    $reservation_date = "";
    $start_time = "";
    $end_time = "";
    $id = "";

    $area_id = "";
    $location_id  = "";
    $facility_name = "";

    try {
        // データベースに接続
        $yoyaku_pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbUsername, $password);
        $yoyaku_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $yoyaku_sql = "SELECT id, reservation_date, start_time, end_time, location FROM yoyaku WHERE location = :location";
        $yoyaku_stmt = $yoyaku_pdo->prepare($yoyaku_sql);
        $yoyaku_stmt->bindParam(':location', $location, PDO::PARAM_INT);
        $yoyaku_stmt->execute();

        $yoyaku_row = $yoyaku_stmt->fetchAll(PDO::FETCH_ASSOC);

        $travel_sql = "SELECT id, area_id, facility_name FROM travel_data";
        $travel_stmt = $yoyaku_pdo->prepare($travel_sql);
        $travel_stmt->execute();

        $travel_row = $travel_stmt->fetchAll(PDO::FETCH_ASSOC);



            // $_GET から値を取得して変数に設定
        $reservation_date = $_GET['reservation'] ?? $reservation_date;
        $start_time = $_GET['start_time'] ?? $start_time;
        $end_time = $_GET['end_time'] ?? $end_time;

        } catch (PDOException $e) {
            echo "<p>データベースエラー: " . $e->getMessage() . "</p>";
        } catch (Exception $e) {
            echo "<p>エラー: " . $e->getMessage() . "</p>";
        }
    ?>

    
            <div class="container">
            <h1>予約確認フォーム</h1>
                <?php
                    echo '<label for="area_l">予約確認</label><br>
                    <select id="area_l" name="area">
                          <option value="">エリアを選択してください</option>
                          <option value="1">エリア１</option>
                          <option value="2">エリア２</option>
                     </select>';

              // ボタンの生成
              echo "<input type='button' value='エリアを決定'>";

              // 施設名の表示
              if (isset($_POST['areaid'])) {
                  $area_id = $_POST['areaid'];
                  foreach ($travel_row as $travel_rows) {
                      // 施設名の表示
                      if ($travel_rows['area_id'] == $area_id) {
                          $facility_name = htmlspecialchars($travel_rows['facility_name']);
                          echo "<p>$facility_name</p>";
                      }
                  }
              }
                ?>


        
            </div>
</body>
</html>