<?php

    /**
     * DB接続用関数
     *
     * @return PDO dbの接続情報
     */
    // データベース接続情報
    function dbConnect(){
        $pdo = new PDO('mysql:host=localhost;dbname=ecoperks;charset=utf8','root','');
        return $pdo;
    }

    // 各modelファイルより情報取得
    require_once('time_change_func.php');
    require_once('Reserve_func.php');
    // require_once('ReserveCheck_Corpo_func.php');
    // require_once('ReserveCheck_Customer_func.php');

?>