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
        // if (!isset($_SESSION['user_id'])) {
        //     $_SESSION['login_message'] = "ログインしてください。"; // メッセージをセッションに保存
        //     header('Location: message.php');
        //     exit;
        // }

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
                <div id="dragDropArea">
                    <div class="drag-drop-inside">
                        <div id="previewArea">
                            <label for="fileInput" id="fileInputLabel">
                                <img alt="User's Avatar" id="previewImage" src="data:image/png;base64,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" class="nav-user-photo-large" />
                            </label>
                            <p class="drag-drop-buttons" style="display: none">
                                <input id="fileInput" type="file" accept="image/*" value="ファイルを選択" name="photo" onChange="photoPreview(event)">
                            </p>
                        </div>
                    </div>
                </div>
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
    let fileArea = document.getElementById('dragDropArea');
    let fileInput = document.getElementById('fileInput');

    fileArea.addEventListener('dragover', function(evt){
        evt.preventDefault();
        fileArea.classList.add('dragover');
    });

    fileArea.addEventListener('dragleave', function(evt){
        evt.preventDefault();
        fileArea.classList.remove('dragover');
    });

    fileArea.addEventListener('drop', function(evt){
        evt.preventDefault();
        fileArea.classList.remove('dragenter');
        let files = evt.dataTransfer.files;
        fileInput.files = files;
        photoPreview('onChange',files[0]);
    });

    function photoPreview(event, f = null) {
        let file = f;
        if(file === null){
            file = event.target.files[0];
        }
        let reader = new FileReader();
        let previewImage = document.getElementById("previewImage");
        let fileInputLabel = document.getElementById('fileInputLabel')

        // 許可する拡張子以外の場合
        if (file && !isValidFile(file)) {
            alert('拡張子が jpeg, jpg, png, bmp, gif 以外のファイルはアップロードできません。');

            resetFileInput();
            return; // 処理を中断
        }

        reader.onload = function(event) {
            let img = document.createElement("img");
            img.alt = "User's Avatar"
            img.setAttribute('class', "nav-user-photo-large")
            img.setAttribute("src", reader.result);
            img.setAttribute("id", "previewImage");
            fileInputLabel.replaceChild(img, previewImage);
        };

        reader.readAsDataURL(file);
    }

    function isValidFile(file) {
        const allowExtensions = '.(jpeg|jpg|png|bmp|gif)$'; // 許可する拡張子

        return file.name.match(allowExtensions)
    }

    function resetFileInput() {
        document.getElementById('fileInput').value = '';
    }
</script>


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
