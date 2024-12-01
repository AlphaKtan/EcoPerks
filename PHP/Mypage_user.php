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
        //require_once('dn_connection.php');
        require_once('db_local.php'); // データベース接続
        require_once('../Model/dbmodel.php');


        try {

            $yoyakusql = "SELECT username FROM users_kokyaku INNER JOIN users ON users_kokyaku.user_id = users.id WHERE users.id = :user_id";
            $stmt = $pdo->prepare($yoyakusql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();

            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $sql = "SELECT imgpath FROM users WHERE id = :id";
            $stmt2 = $pdo->prepare($sql);
            $stmt2->bindValue(':id', $user_id);
            $stmt2->execute();

            $image = $stmt2->fetch();

            } catch (PDOException $e) {
                echo "<p>データベースエラー: " . $e->getMessage() . "</p>";
            } catch (Exception $e) {
                echo "<p>エラー: " . $e->getMessage() . "</p>";
            }
    ?>
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
    <div class="profile">
        <div class="userFlex">
            <div class="userFlexItem">
                <div id="dragDropArea">
                    <div class="drag-drop-inside">
                        <div id="previewArea">
                        <img src="../images/<?php echo $image['imgpath']; ?>" width="300" height="300" class="iconImg">
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
                    <h4>？？pt</h4>
                </div>
            </div>
        </div>
    </div>
    
    <div class="informationBox">
        <div class="boxA">
            
            
        </div>
        <div class="boxA">
            <div class="box3">
            <a href="./coupons.php">
                <h1>クーポン</h1>
                <p>Conpon</p>
                </a>
            </div>
            <a href="ReserveCheck_Customer.php">
            <div class="box4">
                <h1>予約確認</h1>
                <p>CheckReserve</p>
            </div>
            </a>
        </div>
        <div class="boxA">
            <div class="box3">
            <a href="./upload.php">
                <h1>名前変更</h1>
                <p>ChangeName</p>
                </a>
            </div>
            <a href="../index.html">
            <div class="box4">
                <h1>ホームに戻る</h1>
                <p>back home</p>
            </div>
            </a>
        </div>
    </div>

 
    
    <div class="test" style="height: 700px;"></div>
    <!-- <footer>
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
    </footer> -->
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

<script src="../JS/pass.js"></script>


</body>
</html>
