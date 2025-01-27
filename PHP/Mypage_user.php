<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/destyle.css@1.0.15/destyle.css"/>
    <link rel="stylesheet" href="../CSS/mypageStyle.css">
    <title>マイページ</title>
    <style>
        html {
            zoom:normal !important;
        }
    </style>
    
</head>
<body>
    <?php
        // デバッグ用の出力
        // echo "<pre>";
        // print_r($row);
        // echo "</pre>";

        session_start();
        $URL = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $_SESSION['URL'] = $URL;
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['login_message'] = "ログインしてください。"; // メッセージをセッションに保存
            header('Location: message.php');
            exit;
        }
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
        }
        
        // データベース接続情報
        // 接続はこれ一つでlocalとlolipop切り替えれる
        require_once('../Model/dbmodel.php');
        $pdo = dbConnect();

        $directory = '<a href="../index.php">マップ</a> > <a href="./Mypage_user.php">マイページ</a>';
        try {

            $yoyakusql = "SELECT username FROM users_kokyaku INNER JOIN users ON users_kokyaku.user_id = users.id WHERE users.id = :user_id";
            $stmt = $pdo->prepare($yoyakusql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $sql = "SELECT imgpath FROM users WHERE id = :id";
            $stmt2 = $pdo->prepare($sql);
            $stmt2->bindValue(':id', $user_id);
            $stmt2->execute();

            $image = $stmt2->fetch();
            if($row){
                $username = $row['username'];
            }

            } catch (PDOException $e) {
                echo "<p>データベースエラー: " . $e->getMessage() . "</p>";
            } catch (Exception $e) {
                echo "<p>エラー: " . $e->getMessage() . "</p>";
            }
    ?>

<?php include 'header.php';?>


<div class="userpage" style="margin-top:100px;">
        <div class="userFlexItem">
            <a href="./upload.php">
                <div id="dragDropArea">
                    <div class="drag-drop-inside">
                        <div id="previewArea">
                        <img src="../images/<?php echo $image['imgpath']; ?>" width="146" height="140" class="iconImg">
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="userFlexItem">
            <h2 class="center">ユーザー名</h2>
        </div>
        
        <div class="userFlexItem">
            <a href="./upload.php">
            <h2 class="center" style="color: black;">
                <?php echo $username; ?>
            </h2>
            </a>
        </div>

        <div class="boxA">
            <div class="boxB">
                <div class="box3">
                    <a href="./coupons.php" style="display: block;/* width: 100%; */height: 100%;">
                        <h1>クーポン</h1>
                        <p>Conpon</p>
                    </a>
                </div>

                <div class="box3">
                    <a href="ReserveCheck_Customer.php" style="display: block;/* width: 100%; */height: 100%;">
                        <h1>予約確認</h1>
                        <p>CheckReserve</p>
                    </a>
                </div>
            </div>
            <div class="box5">
                <a href="./upload.php" style="display: block;/* width: 100%; */height: 100%;">
                    <h1>名前変更</h1>
                    <p>ChangeName</p>
                </a>
            </div>
        </div>
        
        <a href="./QR.php">
        <div class="QR_botton">
            <img src="..\img\qr_white.png" class="QRimg">
        </div>
        </a>
</div>

    <script src="../JS/pass.js"></script>


</body>
</html>