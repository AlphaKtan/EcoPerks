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
    $imagePath = '../images/' . $image['imgpath']; // 画像のフルパス
    $defaultImage = '../img/irasto.jpg'; // デフォルト画像のパス
?>
<style>
* {
    box-sizing: border-box;
    line-height: 1.15;
}

body {
    background-color: #FFF6E9;
    padding: 0px;
    margin: 0px;
}

header {
    width: 100%;
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
    height: 40px;
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

.logo{
    width: 40px;
    height: auto;
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

.iconImg{
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

.left-menu {
    width: 250px;
    margin-top: 100px;
    padding: 0 20px 20px 10px;
    border-right: 1px solid #e0e0e0;
    display: flex;
    position: fixed;
    flex-direction: column;
    justify-content: space-between;
    height: 88%;
}

.menu-list-bottom {
    list-style-type: none;
    padding: 0;
    width: 100%;
    text-align: left;
}

.menu-list {
    list-style-type: none;
    padding: 0;
    width: 100%;
    margin-bottom: auto;
    
}

.menu-item{
    display: flex;
    align-items: center;
    justify-content: flex-start;
    width: 100%;
    padding: 10px 0 10px 5px;
    margin-bottom: 10px;
    cursor: pointer;
    border-radius: 8px;
    background-color: white;
    display: flex;
    align-items: center;
    gap: 10px;
    width: 100%;
    height: 100%;
    border-radius: 8px;
    padding: 10px 5px;
}
/* 
.menu-item:hover {
    background-color: #DBDBDB;
} */

.menu-item-icon {
    font-size: 24px;
    margin-right: 10px;
}

.menu-item-text-logo {
    display: inline-block;
    vertical-align: middle;
    position: relative;
    top: -3px;  /* 下に移動する量。調整が必要な場合はこの値を変更してください */
}

.menu-item-text{
    margin-left: 10px;
    display: inline-block;
    vertical-align: middle;
}

.menu-item-text-chat{
    margin-left: 10px;
}

a, a:hover, a:active, a:visited {
    color: #ffff;
    text-decoration: none;
}

.a_link{
    display: flex;
    align-items: center;
    gap: 8px;
    width: 100%;
    height: 100%;
}

.right-content {
    margin-left: 280px;
    padding-top:105px;
    width: auto;
    height: 100%;
}


/* スマホ用のスタイル (最大幅 768px) */
@media screen and (max-width: 768px) {
    .left-menu {
        position: fixed; /* 位置を固定 */
        width: 80px; /* メニューを縮小 */
        height: 100%; /* 全画面の高さ */
        overflow-y: auto; /* スクロールを許可 */
        border-right: 1px solid #e0e0e0; /* ボーダーを維持 */
    }

    .menu-item {
        justify-content: center; /* アイコンを中央揃え */
        flex-direction: column; /* アイコンを縦配置（オプション） */
        padding: 10px 0; /* 縦方向の余白を調整 */
    }

    .menu-item-text {
        display: none; /* テキストを非表示 */
    }
    
    .text-box {
        display: none; /* テキストを非表示 */
    }
    
    .logo {
        width: 40px; /* アイコンサイズを調整 */
        height: auto; /* アスペクト比を維持 */
    }

    .link_button:hover {
    background-color: #557981;
    }
    .user_Name{
        font-size: 10px;
    }


    .right-content {
        margin-left: 70px;
        padding-top: 105px;
        width: 100%;
        height: 100%;
    }

}

/* より小さいデバイス (最大幅 480px) */
@media screen and (max-width: 480px) {
    .left-menu {
        width: 60px; /* さらに縮小 */
    }

    .logo {
        width: 30px; /* アイコンをさらに縮小 */
    }

    .menu-item {
        padding: 5px 0; /* 余白をさらに縮小 */
    }
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
            <img src="<?php echo file_exists($imagePath) ? $imagePath : $defaultImage; ?>" width="50px" height="50px" class="iconImg">
        </div>
    </div>
    <div class="sub_header">
        <div class="sub_header_box1">
            <div style="display: flex;">
                <p class="link"><?php echo $directory; ?></p>
            </div>
        </div>
        <div class="sub_header_box2" style="border-left:solid 1px #ffff;">
        <p class="user_Name" style="margin:0; color:#f3f3f3;">ユーザーネーム</p>
            <p style="margin:0; color:#f3f3f3;">
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