<style>
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
    height: 40px;
    background-color: #43AEA9;
}
.sub_header_box2{
    width: 20%;
    background-color: #43AEA9;
}
.menu {
    width: 100%;
}

.logo {
    height: 60px;
    width: 100%;
    text-align: center  ;
}

.logo2 {
    height: 60px;
}

.icon {
    width: 100%;
}

a:visited {
 color: #ffff;
}

a {
    text-decoration: none;
}

a:hover {}

a:active {
 color: #ffff;
}
</style>
<header>
    <div class="flexBox">
        <div class="menu"></div>
        <div class="logo">
            <img src="../img/logo_yoko.svg" alt="" class="logo2">
        </div>
        <div class="icon"></div>
    </div>
    <div class="sub_header">
        <div class="sub_header_box1">
            <div style="display: flex;">
                <p style="margin: 10px; color:#ffff;"><?php echo $directory; ?></p>
            </div>
        </div>
        <div class="sub_header_box2" style="border-left:solid 1px #ffff;">
        </div>
    </div>
</header>