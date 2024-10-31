<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/time_changeStyle.css">
    <title>Document</title>
    <style>
        select {
            width: 200px;
        }
    </style>
</head>
<body>
    <form action="" method="post">
        <select name="area" id="area">
            <option hidden>選択してください</option>
            <option value="">すべて</option>
            <?php for($i=1;$i<=25;$i++){ ?>
                <option value="<?php echo $i ?>">エリア<?php echo $i ?></option>
            <?php }?>
        </select>
        <br>
        <select name="facility" id="facility">
            <option hidden>選択してください</option>
            <option value="">すべて</option>
        </select>
        <br>
        <input name="date" type="date" id="date" />
    </form>
    <div id="yoyaku"></div>
<script src="../Js/jquery-3.7.1.min.js"></script>
<script src="../Js/custom.js"></script>
<script>
    var optval;
    $(function(){
        // よく使う要素を変数へ格納する
        var area = document.getElementById("area");
        var facility = document.getElementById("facility");
        var date = document.getElementById("date");
        var yoyaku = document.getElementById('yoyaku');
        
        // エリア情報切り替え
        $('#area').on("change",function(){

            // 予約一覧をクリアする
            yoyaku.innerHTML = '';

            // 施設情報をクリアする
            selectDataClear(facility);

            // 施設のデータを取得する
            getAreaData(area.value);

            // 予約情報を取得する
            getYoyakuData(area.value,facility.value,date.value);

        })


        // 施設情報切り替え
        $('#facility').on("change",function(){

            // 予約一覧をクリアする
            yoyaku.innerHTML = '';

            // 予約情報を取得する
            getYoyakuData(area.value,facility.value,date.value);
        })


        // 時間情報切り替え
        $('#date').on("change",function(){

            // 予約一覧をクリアする
            yoyaku.innerHTML = '';

            // 予約情報を取得する
            getYoyakuData(area.value,facility.value,date.value);
        });
        
    });
</script>
</body>
</html>