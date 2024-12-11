<?php
    session_start();
?>
<style>
p{
    color: #f3f3f3;
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
</style>
<div class="sub_header">
    <div class="sub_header_box1">
        <p style="padding-left: 10px;"><?php $_SESSION['navinfo']; ?></p>
    </div>
    <div class="sub_header_box2" style="border-left:solid 1px #ffff;">
    </div>
</div>