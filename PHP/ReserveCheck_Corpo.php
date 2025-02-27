<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/indexStyle.css">
    <link rel="stylesheet" href="../CSS/yoyakukakuninStyle.css">
    <title>予約フォーム</title>
    <script>
        // フォームの自動送信を行うためのJavaScript
        function submitForm() {
            document.getElementById('areaForm').submit();
        }
        function goBack() {
            history.back();
        }
    </script>
</head>
<body>
    <header>
        <div class="flexBox">
            <div class="menu">
            <button class="link_button" onclick="history.back();">戻る</button>
            </div>
            <div class="logo">
                <img src="../img/logo.jpg" alt="" class="logo2">
            </div>
            <div class="icon"></div>
        </div>
    </header>

    <?php
    // データベース接続情報
    require_once('../Model/dbModel.php');
    $pdo = dbConnect();

    try {
        // データベースに接続
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbUsername, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // POSTで送信されたエリアIDを取得
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['area_id'])) {
            $selected_area = $_POST['area_id'];

            // 選択されたエリアIDに基づいてデータベースから施設情報を取得
            $yoyakusql = "SELECT username, area_id, reservation_date, start_time, end_time, location FROM yoyaku WHERE area_id = :area_id";
            $stmt = $pdo->prepare($yoyakusql);
            $stmt->bindParam(':area_id', $selected_area, PDO::PARAM_INT);
            $stmt->execute();

            // 結果を取得
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

    } catch (PDOException $e) {
        echo "<p>データベースエラー: " . $e->getMessage() . "</p>";
    } catch (Exception $e) {
        echo "<p>エラー: " . $e->getMessage() . "</p>";
    }
    ?>

    <div class="container">
        <h1>予約確認フォーム</h1>
        <!-- エリア選択のためのフォーム -->
        <form method="POST" id="areaForm" action="">
            <label for="areaSelect">エリアを選択してください：</label>
            <select name="area_id" id="areaSelect" onchange="submitForm()" required>
                <option hidden value="">選択してください</option>
                <?php 
                for ($i = 1; $i <= 25; $i++) {
                    // 選択したエリアを保持するための処理
                    $selected = (isset($selected_area) && $selected_area == $i) ? 'selected' : '';
                    echo '<option value="' . $i . '" ' . $selected . '>エリア' . $i . '</option>';
                } ?>
            </select>
        </form>

        <?php
        // フォームが送信され、データベースから施設情報が取得された場合
        if (isset($row) && !empty($row)) {
            foreach ($row as $rows) {
                $location = $rows['location'];
                $area_id = $rows['area_id'];
                $reservation_date = $rows['reservation_date'];
                $start_time = $rows['start_time'];
                $end_time = $rows['end_time'];

                echo <<< html
                <li><h2>$location</h2>
                <p>エリア：$area_id</p>
                <p>日程：$reservation_date</p>
                <p>開始時間：$start_time</p>
                <p>終了時間：$end_time</p></li>                
                html;
            }
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo "<p>選択されたエリアに施設は見つかりませんでした。</p>";
        }
        ?>
    </div>
    <div class="backButton">
        <button onclick="goBack()">ひとつ前に戻る</button>
    </div>
</body>
</html>
