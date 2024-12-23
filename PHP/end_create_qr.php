<?php 
    // 仮の値を代入
    $area_id = 1;
    $location = 1;
?>
<script src="../JS/qrcode.min.js"></script>
<div id="qrcode"></div>
<!-- <input type="text" id="text" placeholder="QRコードに変換するテキスト"> -->
<button id="generate">QRコード生成</button>

<script type="text/javascript">
    let area = "<?=$area_id; ?>";  // PHPの変数をjsの変数に代入
    let location_id = "<?=$location; ?>";
</script>
<script>
    document.getElementById('generate').addEventListener('click', function() {
        let fetchQR = document.getElementById('qrcode');
        // QRコードがすでに生成されている場合は、削除
        fetchQR.innerHTML = "";

        let now = new Date();
        // 埋め込みたいデータ
        let data = {
            area_id: area,
            location: location_id,
            create_time: now,
            s: 1
        };

        // データをJSON文字列に変換
        let jsonData = JSON.stringify(data);

        // QRコード生成
        let qrcode = new QRCode(document.getElementById('qrcode'), {
            text: jsonData,
            colorDark: '#000000',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.H
        });
    });
</script>
