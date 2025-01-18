<style>
* {
    box-sizing: border-box;
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