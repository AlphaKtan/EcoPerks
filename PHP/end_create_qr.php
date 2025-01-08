<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $URL = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $_SESSION['URL'] = $URL;
    if (!isset($_SESSION['admin_id'])) {
        $_SESSION['login_message'] = "ログインしてください。"; // メッセージをセッションに保存
        header('Location: ./admin_message.php');
        exit;
    }
    if (isset($_SESSION['admin_id'])) {
        $admin_id = $_SESSION['admin_id'];
    }
    // 仮の値を代入
    $area_id = $_SESSION['admin_area_id'];
    $location = $_SESSION['location_id'];
    // このページのタイトルを入力
    $title = "終了QRコード生成";
    include "admin_home.php";
?>
<script src="../JS/qrcode.min.js"></script>
<style>
    div#qrcode {
    width: fit-content;
    padding: 25px;
    background: white;
    border-radius: 5%;
}
</style>
<div id="qrcode"></div>

<script type="text/javascript">
    let area = "<?=$area_id; ?>";  // PHPの変数をjsの変数に代入
    let location_id = "<?=$location; ?>";
</script>
<script>
    function createQR() {
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

        fetchQR.removeAttribute("title");
    }

    createQR();

    const interval = setInterval(() => {
            createQR();
                console.log("更新");
            }, 3 * 60 * 1000);
</script>
