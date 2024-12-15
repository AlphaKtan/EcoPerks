<script src="../JS/qrcode.min.js"></script>
<div id="qrcode"></div>
<input type="text" id="text" placeholder="QRコードに変換するテキスト">
<button id="generate">QRコード生成</button>

<script>
    document.getElementById('generate').addEventListener('click', function() {
        var now = new Date();
        // 埋め込みたいデータ
        var data = {
            area_id: 1,
            location: 2,
            create_time: now
        };

        // データをJSON文字列に変換
        var jsonData = JSON.stringify(data);
        var text = document.getElementById('text').value;

        // QRコード生成
        var qrcode = new QRCode(document.getElementById('qrcode'), {
            text: jsonData,
            colorDark: '#000000',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.H
        });
    });
</script>
