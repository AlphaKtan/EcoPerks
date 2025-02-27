<style>
* {
    box-sizing: border-box;
    line-height: 1.15;
}

html {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    -ms-text-size-adjust: 100%;
    -webkit-text-size-adjust: 100%;
}

/* 通常のテキストに対してのフォント設定 */
body {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
}

/* ヘッダータグに対するフォント設定 */
h1, h2, h3, h4, h5, h6 {
    font-family: inherit;
    font-weight: 500;
    line-height: 1.2;
}

/* ボタンに対するフォント設定 */
button, .btn {
    font-family: inherit;
    font-weight: 400;
}

/* リストに対するフォント設定 */
ul, ol {
    font-family: inherit;
    font-weight: 400;
    font-size: 1rem;
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
    display: block;
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
    color: #000;
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
<header>
    <div class="flexBox">
        <div class="menu"></div>
        <div class="logo_yoko">
            <img src="../img/logo_yoko.svg" alt="" class="logo2">
        </div>
        <div class="icon"></div>
    </div>
    <div class="sub_header">
        <div class="sub_header_box1">
            <div style="display: flex;">
                <p class="link"><?php echo $directory; ?></p>
            </div>
        </div>
        <div class="sub_header_box2" style="border-left:solid 1px #ffff;">
        <p style="margin:0; color:white;">施設名</p>
            <p style="margin:0; color:white;">
                <?php
                    if($_SESSION['facility']){
                        echo $_SESSION['facility'];
                    }
                ?>
            </p>
        </div>
    </div>
</header>

<div class="left-menu">
        <div>
            <ul class="menu-list" style="margin: 1rem 0;">
                <p class="text-box" style="margin: 5px 0;">シフト設定</p>
                <li class="menu-item shift"><a href="shift.php" class="a_link"><img src="../img/shift.svg" class="logo"><span class="menu-item-text">シフト追加</span></a></li>
                <li class="menu-item time_change"><a href="time_change.php" class="a_link"><img src="../img/shift.svg" class="logo"><span class="menu-item-text">シフト削除</span></a></li>
                <p class="text-box" style="margin-top: 25px;">申込確認</p>
                <li class="menu-item sankakanri"><a href="sankakanri.php" class="a_link"><img src="../img/user.svg" class="logo"><span class="menu-item-text">参加者一覧</span></a></li>
                <p class="text-box" style="margin-top: 25px;">当日処理</p>
                <li class="menu-item create_qr"><a href="create_qr.php" class="a_link"><img src="../img/QR.svg" class="logo"><span class="menu-item-text">QRコード参加ページ</span></a></li>
                <li class="menu-item end_create_qr"><a href="end_create_qr.php" class="a_link"><img src="../img/QR.svg" class="logo"><span class="menu-item-text">QRコード終了ページ</span></a></li>
                <p class="text-box" style="margin-top: 25px;">オプション</p>
                <li class="menu-item admin_login"><a href="./logout_page.php" class="a_link"><img src="../img/main_user.svg" class="logo"><span class="menu-item-text">ログアウト</span></a></li>
                <li class="menu-item access_log"><a href="access_log.php" class="a_link"><img src="../img/log.svg" class="logo"><span class="menu-item-text">アクセスログ表示ページ</span></a></li>
                <li class="menu-item data"><a href="data.php" class="a_link"><img src="../img/dataDB.svg" class="logo"></span><span class="menu-item-text">データ管理ページ</span></a></li>
            </ul>
            <ul class="menu-list-bottom">
            <script>
                let directory = <?= json_encode(pathinfo(basename($_SERVER['REQUEST_URI']), PATHINFO_FILENAME)); ?>;
                console.log(directory);
                let element = document.querySelector(`.${directory}`);
                element.style.background = '#DBDBDB';
            </script>


            </ul>
        </div>
    </div>
    <div class="right-content">