<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/indexStyle.css">
    <link rel="stylesheet" href="../CSS/yoyakukakuninStyle.css">
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

    
    // デバッグ用の出力
    // echo "<pre>";
    // print_r($row);
    // echo "</pre>";


    // データベース接続情報
    require_once('db_local.php'); // データベース接続

    $location = '';
    $reservation_date = "";
    $start_time = "";
    $end_time = "";
    $id = "";



    try {
        // データベースに接続
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbUsername, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        

        $yoyakusql = "SELECT id, reservation_date, start_time, end_time, location FROM yoyaku WHERE location = :location";
        $stmt = $pdo->prepare($yoyakusql);
        $stmt->bindParam(':location', $location, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);



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
                        if($row){
                            foreach($row as $rows){
                                $location = $rows['location'];
                                echo "<li><h2>$location</h2>";
                                $reservation_date = $rows['reservation_date'];
                                echo "<p>日程：$reservation_date</p>";
                                $start_time = $rows['start_time'];
                                echo "<p>開始時間：$start_time</p>";
                                $end_time = $rows['end_time'];
                                echo "<p>終了時間：$end_time</p></li>";
                            }
                        } else {
                            throw new Exception("指定された施設が見つかりません。");
                        }
                ?>


        
            </div>
</body>
</html>