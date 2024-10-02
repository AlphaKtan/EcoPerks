<?php

    /**
     * DB接続用関数
     *
     * @return PDO dbの接続情報
     */
    // データベース接続情報
    function dbConnect(){
        $db = new PDO('mysql:host=localhost;dbname=ecoperks;charset=utf8','root','');
        return $db;
    }

    // 各modelファイルより情報取得
    require 'time_change_func.php';
    require 'gender.php';
    require 'subject.php';
    require 'result.php';

?>