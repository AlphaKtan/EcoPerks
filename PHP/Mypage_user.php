<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/destyle.css@1.0.15/destyle.css"/>
    <link rel="stylesheet" href="../CSS/mypageStyle.css">
    <link rel="stylesheet" href="../CSS/hannbaka.css">
    <title>Google マップの表示</title>
    <style>
        html {
            zoom:normal !important;
        }
    </style>
</head>
<body>
    <header>
    <?php
        // デバッグ用の出力
        // echo "<pre>";
        // print_r($row);
        // echo "</pre>";

        session_start();
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['login_message'] = "ログインしてください。"; // メッセージをセッションに保存
            header('Location: message.php');
            exit;
        }

        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
        }
        
        // データベース接続情報
        require_once('db_local.php'); // データベース接続
        require_once('../Model/dbmodel.php');


        try {

            $yoyakusql = "SELECT username FROM users_kokyaku INNER JOIN users ON users_kokyaku.user_id = users.id WHERE users.id = :user_id";
            $stmt = $pdo->prepare($yoyakusql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();

            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

            } catch (PDOException $e) {
                echo "<p>データベースエラー: " . $e->getMessage() . "</p>";
            } catch (Exception $e) {
                echo "<p>エラー: " . $e->getMessage() . "</p>";
            }
    ?>
    <div class="flexBox">
        <div class="menu">
            <div class="openbtn"><span></span><span></span><span></span></div>
            <nav id="g-nav">
                <div id="g-nav-list"><!--ナビの数が増えた場合縦スクロールするためのdiv※不要なら削除-->
                    <ul>
                        <li><a href="../index.html">Top</a></li>
                        <li><a href="login_page.php">ログイン</a></li> 
                        <li><a href="form.html">アカウント作成</a></li> 
                        <li><a href="#">Contact</a></li> 
                    </ul>
                </div>
            </nav>
        </div>
        <div class="logo">
            <img src="../img/logo.jpg" alt="" class="logo2">
        </div>
        <div class="icon"></div>
    </div>
    </header>
    <div class="profile">
        <div class="userFlex">
            <div class="userFlexItem">
                <img src="../img/irasto.jpg" alt="" class="irasto">
            </div>
            <div class="userFlexItem">
                <h2 class="center">
                    <?php
                    if($row){
                        foreach($row as $rows){
                    $username = $rows['username'];
                     echo "$username";
                        }
                    }?>
                </h2>
            </div>
            <div class="userFlexItem">
                <h2 class="center">現在のポイント</h2>
                <div class="pointArea">
                    <img src="../img/point.jpg" alt="" class="point">
                    <h4>111</h4>
                </div>
            </div>
        </div>
    </div>
    
    <div class="informationBox">
        <div class="boxA">
            <div class="box1">
                <a href="./ReserveCheck_Customer.php">
                <h1>予約確認</h1>
                <p>Reservation</p>
                </a>
            </div>
            <div class="box2">
                <h1>ランキング</h1>
                <p>Ranking</p>
            </div>
        </div>
        <div class="boxA">
            <div class="box3">
                <h1>クーポン</h1>
                <p>Conpon</p>
            </div>
            <a href="ReserveCheck_Customer.php">
            <div class="box4">
                <h1>予約確認</h1>
                <p>CheckReserve</p>
            </div>
            </a>
        </div>
    </div>

    

    <div class="test" style="height: 700px;"></div>
    <footer>
        <div id="flexBox02">
            <div class="flexItem02">
                <h4>Mission</h4>
                <div class="progress">
                    <div class="progress-bar" style="width: 80%;"></div>
                </div>
            </div>
            <div class="flexItem02">
                <h4>QR</h4>
                <img src="../img/qr.png"width="45px" height="45px">
            </div>
            <div class="flexItem02">
                <h4>Ranking</h4>
                <img src="../img/ranking.png"width="50px" height="30px">
            </div>
        </div>
    </footer>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<!--自作のJS-->
<script src="../JS/hannbaka.js"></script>

<script>
    let inputText = ''; // 入力されたテキストを保持する変数

    // body全体にキー入力を検出するリスナーを設定
    document.body.addEventListener('keydown', function(event) {
        // Enterキーが押されたかどうかを確認
        if (event.key === 'Enter') {
            event.preventDefault(); // Enterキーのデフォルト動作を防止
            console.log(inputText);
            // 入力されたテキストが "[start]" ならリダイレクト
            if (inputText === 'start') {
                window.location.href = './start_user.php'; // リダイレクト先のURL
            }

            // 入力をリセット
            inputText = '';
        } else if (event.key.length === 1) {
            // アルファベットや数字などの1文字のキーが押された場合のみ追加
            inputText += event.key;
            
        }
    });
</script>
</body>
</html>
