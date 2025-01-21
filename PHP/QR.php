<?php
session_start();
$URL = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$_SESSION['URL'] = $URL;
if (!isset($_SESSION['user_id'])) {
    $_SESSION['login_message'] = "ログインしてください。"; // メッセージをセッションに保存
    header('Location: message.php');
    exit;
}
require_once('../Model/dbmodel.php');
$pdo = dbConnect();

$directory = '<a href="../index.php">マップ</a> > <a href="./Mypage_user.php">マイページ</a>';

// if (isset($_SESSION['user_id'])) {
//     $user_id = $_SESSION['user_id'];
// }
$user_id = 2;
$_SESSION['user_id'] = 2;
?>
<!DOCTYPE html>
<head>
    <title>QRコードの読み取り</title>
    <!-- <link rel="stylesheet" href="style.css"> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/destyle.css@1.0.15/destyle.css"/>
    <link rel="stylesheet" href="../CSS/mypageStyle.css">
    <style>
#wrapper {
    position: relative;
    width: 100%;
    height: 100vh; /* 画面全体を使用 */
    display: flex;
    flex-direction: column; /* 縦方向に要素を並べる */
    justify-content: center; /* 中央揃え */
    align-items: center; /* 横方向も中央揃え */
    background-color: #FFF6E9; /* 背景色（任意） */
}

#video, #camera-canvas, #rect-canvas {
    position: absolute;
    width: 640px; /* 映像の幅 */
    height: 480px; /* 映像の高さ */
    top: 140px;
}

#qr-msg {
    position: relative;
    margin-top: 500px; /* 映像の下に固定 */
    font-size: 18px; /* 文字サイズ */
    text-align: center; /* テキスト中央揃え */
    color: #333; /* テキスト色 */
}

#reload {
    position: relative;
    margin-top: 20px; /* メッセージの下に余白 */
    padding: 10px 20px; /* 内側の余白を調整 */
    font-size: 16px; /* ボタンの文字サイズ */
    background-color: #007bff; /* ボタン背景色 */
    color: #fff; /* ボタン文字色 */
    border: none; /* ボーダーを消す */
    border-radius: 5px; /* 角を丸くする */
    cursor: pointer; /* ポインタ変更 */
    transition: background-color 0.3s ease; /* ホバー時のアニメーション */
}

#reload:hover {
    background-color: #0056b3; /* ホバー時の色変更 */
}


    </style>
</head>
<body>
<?php include 'header.php';?>

    <div id="wrapper">
        <video id="video" autoplay muted playsinline></video>
        <canvas id="camera-canvas"></canvas>
        <canvas id="rect-canvas"></canvas>
        <span id="qr-msg">QRコード: 見つかりません</span>
        <button onclick="checkImage()" id="reload">もう一度</button>
    </div>
    <script src="../JS/jsQR.js"></script>
    <script src="../Js/jquery-3.7.1.min.js"></script>
    <script type="text/javascript">
        let username = "<?=$user_id; ?>";  // PHPの変数をjsの変数に代入
        console.log("user_id"+username);
        
    </script>
</body>
</html>

<script>
    // 有効期限　今回は3分
    const VALID_DURATION = 3 * 60 * 1000;
    // Webカメラの起動
    const video = document.getElementById('video');
    let contentWidth;
    let contentHeight;

    const media = navigator.mediaDevices.getUserMedia({ audio: false, video: {width:640, height:480} })
    .then((stream) => {
        video.srcObject = stream;
        video.onloadeddata = () => {
            video.play();
            contentWidth = video.clientWidth;
            contentHeight = video.clientHeight;
            canvasUpdate();
            checkImage();
        }
    }).catch((e) => {
        console.log(e);
    });

    // カメラ映像のキャンバス表示
    const cvs = document.getElementById('camera-canvas');
    const ctx = cvs.getContext('2d');
    const canvasUpdate = () => {
    cvs.width = contentWidth;
    cvs.height = contentHeight;
    ctx.drawImage(video, 0, 0, contentWidth, contentHeight);
    requestAnimationFrame(canvasUpdate);
    }

    // QRコードの検出
    const rectCvs = document.getElementById('rect-canvas');
    const rectCtx =  rectCvs.getContext('2d');
    let checkImageTimer; // タイマーIDを保持する変数
    let jsonCode = null;

    const checkImage = () => {
        // imageDataを作る
        const imageData = ctx.getImageData(0, 0, contentWidth, contentHeight);
        // jsQRに渡す
        const code = jsQR(imageData.data, contentWidth, contentHeight);



        // QRが検出されなかった場合「QRコード: 見つかりません」を表示する
        if (!code) {
            console.log("QRcodeが見つかりません…", code);
            rectCtx.clearRect(0, 0, contentWidth, contentHeight);
            document.getElementById('qr-msg').textContent = `QRコード: 見つかりません`;

            // タイマーをセット
            checkImageTimer = setTimeout(() => { checkImage(); }, 500);
            return;
        }

        // 今日の日付をnowに保存
        const now = new Date();

        // QRの情報がjsonに変換する
        // jsonに変換できなかった場合は、nullを格納する
        try {
            jsonCode = JSON.parse(code.data);
        } catch (error) {
            jsonCode = null; 
            console.log("jsonじゃない");
            return;     
        }

        // create_timeをDateオブジェクトに変換
        // QRcodeの有効期限が切れていたら「有効期限が過ぎています」を表示する
        const create_time = new Date(jsonCode.create_time);
        if(now - create_time > VALID_DURATION) {
            console.log("有効期限が過ぎています");
            return;
        }

        console.log("QRcodeが見つかりました", code);

        // もし、「area_id」と「location」と「create_time」がjsonに入っていて、sが0の時(参加)の時はDBを更新する
        if(jsonCode.area_id && jsonCode.location && jsonCode.create_time && jsonCode.s === 0) {
            console.log("更新処理を開始");
            statusUpDate(jsonCode.area_id, jsonCode.location, jsonCode.create_time, jsonCode.s);
        }

        // もし、「area_id」と「location」と「create_time」がjsonに入っていて、sが1の時(参加終了)の時はDBを更新する
        if(jsonCode.area_id && jsonCode.location && jsonCode.create_time && jsonCode.s === 1) {
            console.log("終わりの更新処理を開始");
            statusUpDate(jsonCode.area_id, jsonCode.location, jsonCode.create_time, jsonCode.s);

            sendDownloadedFileName(jsonCode.area_id, jsonCode.location);
        }


        // 後で内容を変える
        document.getElementById('qr-msg').innerHTML = `QRコード：<a href="${code.data}">${code.data}</a>`;

        // 四辺形の描画
        drawRect(code.location);
        // タイマーを停止
        if (checkImageTimer) {
            clearTimeout(checkImageTimer);
            setTimeout(() => { rectCtx.clearRect(0, 0, contentWidth, contentHeight); }, 500);
            console.log("QRコードが検出されたため、タイマーを停止しました。");
        }
    };


    // 四辺形の描画
    const drawRect = (location) => {
    rectCvs.width = contentWidth;
    rectCvs.height = contentHeight;
    drawLine(location.topLeftCorner, location.topRightCorner);
    drawLine(location.topRightCorner, location.bottomRightCorner);
    drawLine(location.bottomRightCorner, location.bottomLeftCorner);
    drawLine(location.bottomLeftCorner, location.topLeftCorner)
    }

    // 線の描画
    const drawLine = (begin, end) => {
    rectCtx.lineWidth = 4;
    rectCtx.strokeStyle = "#F00";
    rectCtx.beginPath();
    rectCtx.moveTo(begin.x, begin.y);
    rectCtx.lineTo(end.x, end.y);
    rectCtx.stroke();
    }

    function statusUpDate(area_id, location_id, create_time, status) {
        $.ajax({
            type: "POST",
            url: "../PHP/statusUpDate.php",
            dataType: "json",
            data: { username: username, area_id: area_id, location: location_id, create_time: create_time, status: status }
        }).done(function(data) {
            console.log(data);
              
            if(data === '正常に完了1') {
                console.log("成功");
                alert("参加開始されました");
            } else if(data === '正常に完了2') {
                console.log("成功");
                alert("終了されました");
            } else {
                console.log("しっぱい");
                console.log(data);
            }
 
        }).fail(function(jqXHR, textStatus, errorThrown)  {
            console.error("AJAXリクエストに失敗しました");
            console.error("HTTPステータス:", jqXHR.status); // ステータスコード
            console.error("レスポンス内容:", jqXHR.responseText); // サーバーの返答内容
            console.error("エラーメッセージ:", errorThrown);
        }); 
    }

    // ダウンロードしたファイル名を送信する
    function sendDownloadedFileName(area, location){
        
        /* post送信する */
        const form = document.createElement('form');
        form.action = 'coupons.php';
        form.method = 'post';
        // 送信データ作成
        // 下記のように動的にフォームを作る
        // もちろん複数フォームを作成することもできる　
        const data1 = document.createElement('input');
        data1.value = area;
        data1.name = 'area';

        const data2 = document.createElement('input');
        data2.value = location;
        data2.name = 'location';

        form.appendChild(data1);
        form.appendChild(data2);
    
        document.body.appendChild(form);

        form.submit();
    }


</script>

<!--いつか使う
開始時間より30分を超えている場合:
「30分経過したので参加できません」
開始時間より15分以上早い場合:
「マイページから予約時間を確認してください」マイページのリンク張る、マイページに「開始時間の15分前から参加可能です」と書く
予約が見つからない場合:
「予約データが見つかりません。予約が確認できないか、キャンセルされた可能性があります。」
参加できた場合:
「参加できました！お楽しみください。エリア: X、開始時間: Y」
 -->


