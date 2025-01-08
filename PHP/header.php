<?php
    echo <<<HTML
        <header>
            <div class="flexBox">
                <div class="menu">
                <button class="link_button" onclick="location.href='../index.php';">戻る</button>
                </div>
                <div class="logo">
                    <img src="../img/logo_yoko.svg" alt="" class="logo2">
                </div>
                <div class="icon"></div>
            </div>
        </header>

        <div class="sub_header">
            <div class="sub_header_box1">
                <div style="display: flex;">
                    <p style="padding-left: 10px;"><?php echo $directory; ?></p>
                </div>
            </div>
            <div class="sub_header_box2" style="border-left:solid 1px #ffff;">
            </div>
        </div>
    HTML;
 ?>