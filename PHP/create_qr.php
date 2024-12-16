<?php 
    // 仮の値を代入
    $area_id = 1;
    $location = 2;
?>
<script src="../JS/qrcode.min.js"></script>
<div id="qrcode"></div>
<!-- <input type="text" id="text" placeholder="QRコードに変換するテキスト"> -->
<button id="generate">QRコード生成</button>

<script src="../Js/jquery-3.7.1.min.js"></script>
<script type="text/javascript">
    let area = "<?=$area_id; ?>";  // PHPの変数をjsの変数に代入
    let location_id = "<?=$location; ?>";
</script>
<script>
    document.getElementById('generate').addEventListener('click', function() {
        let fetchQR = document.getElementById('qrcode');
        // QRコードがすでに生成されている場合は、削除
        fetchQR.innerHTML = "";

        var now = new Date();
        // 埋め込みたいデータ
        var data = {
            area_id: area,
            location: location_id,
            create_time: now
        };

        // データをJSON文字列に変換
        var jsonData = JSON.stringify(data);

        // QRコード生成
        var qrcode = new QRCode(document.getElementById('qrcode'), {
            text: jsonData,
            colorDark: '#000000',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.H
        });
    });
</script>
