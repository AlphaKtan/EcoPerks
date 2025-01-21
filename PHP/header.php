<?php
    try {


        
        $yoyakusql = "SELECT username FROM users_kokyaku INNER JOIN users ON users_kokyaku.user_id = users.id WHERE users.id = :user_id";
        $stmt = $pdo->prepare($yoyakusql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

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
<style>
* {
    box-sizing: border-box;
}

header {
    width: 100%;
    height: 60px;
    top: 0; /* 上部から配置の基準位置を決める */
    background-color: white;
    position: fixed;
    z-index: 100;
}

.sub_header{
    display:flex; 
    background-color: white;
}
.sub_header_box1{
    display: flex;
    justify-content: center;
    flex-flow: column;
    width: 80%;
    height: 47px;
    background-color: #43AEA9;
}
.sub_header_box2{
    width: 20%;
    background-color: #43AEA9;
    padding-left: 5px;
    display: flex;
    flex-direction: column;
    justify-content: space-around;
    align-items: flex-start;
    color: #ffff;
}
.menu {
    width: 100%;
    display: block;
}

.logo_yoko {
    height: 60px;
    width: 100%;
    text-align: center  ;
}

.logo2 {
    height: 60px;
}

.icon {
    width: 100%;
    display: flex;
    justify-content: right;
    align-items: center;
    height: 101%;
    margin: 0px 25px;
}

.iconImg {
    border-radius: 50%;
    object-fit: cover;
}

.link:visited {
 color: #ffff;
}

.link {
    text-decoration: none;
    margin: 10px;
    color:#ffff;
}

.link:active {
    color: #ffff;
}
</style>
<link rel="stylesheet" href="../CSS/hannbaka.css">
<header>
    <div class="flexBox">
        <div class="menu">
            <div class="openbtn"><span></span><span></span><span></span></div>
            <nav id="g-nav">
                <div id="g-nav-list"><!--ナビの数が増えた場合縦スクロールするためのdiv※不要なら削除-->
                    <ul class="menu-list">
                        <li class="menu-item"><a href="../php/Mypage_user.php" class="a_link">マイページ</a></li>
                        <li class="menu-item"><a href="../php/coupons.php" class="a_link">クーポン</a></li>
                        <li class="menu-item"><a href="../php/ReserveCheck_Customer.php" class="a_link">予約確認</a></li>
                        <li class="menu-item"><a href="../php/qr.php" class="a_link">QR読み取り</a></li>
                    </ul>
                </div>
            </nav>
        </div>
        <div class="logo_yoko">
            <img src="../img/logo_yoko.svg" alt="" class="logo2">
        </div>
        <div class="icon">
            <img src="../images/<?php echo $image['imgpath']; ?>"  width="50px" height="50px" class="iconImg">
        </div>
    </div>
    <div class="sub_header">
        <div class="sub_header_box1">
            <div style="display: flex;">
                <p class="link"><?php echo $directory; ?></p>
            </div>
        </div>
        <div class="sub_header_box2" style="border-left:solid 1px #ffff;">
        <p style="margin:0;">ユーザーネーム</p>
            <p style="margin:0;">
                <?php
                    if($userRow){
                        $username = $userRow['username'];
                        echo $username;
                    }
                ?>
            </p>
        </div>
    </div>
</header>

<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="../JS/hannbaka.js"></script>