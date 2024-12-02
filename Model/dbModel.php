<?php

    /**
     * DB接続用関数
     *
     * @return PDO dbの接続情報
     */
    // データベース接続情報
    function dbConnect(){
        // local
        $pdo = new PDO('mysql:host=localhost;dbname=ecoperks;charset=utf8','root','');
        
        // lolipop
        // $pdo = new PDO("mysql:host=mysql305.phy.lolipop.lan;dbname=LAA1516370-ecoperks;charset=utf8", "LAA1516370", "ecoperks2024");
        return $pdo;
    }

    function dbConn(){
        // local
        $conn = new mysqli('localhost', 'root', '', 'ecoperks');

        // lolipop
        // $conn = new mysqli('mysql305.phy.lolipop.lan', 'LAA1516370', 'ecoperks2024', 'LAA1516370-ecoperks');
        return $conn;
    }

    // 各modelファイルより情報取得
    require_once('time_change_func.php');
    // require_once('ReserveCheck_Corpo_func.php');
    require_once('Reservation_func.php');

