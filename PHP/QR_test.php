<!DOCTYPE html>
<head>
    <title>QRコードの読み取り</title>
    <!-- <link rel="stylesheet" href="style.css"> -->
    <style>
        #wrapper{
            position: relative;
        }

        #video{
            position: absolute;
            top: 0px;
            left: 0px;
            visibility: hidden;
        }

        #camera-canvas{
            position: absolute;
            top: 0px;
            left: 0px;
            z-index: 50;
        }

        #rect-canvas{
            position: absolute;
            top: 0px;
            left: 0px;
            z-index: 100;
        }

        #qr-msg{
            position: absolute;
            top: 500px;
            left: 0px;
        }

        #reload{
            position: absolute;
            top: 550px;
            left: 0px;
        }

    </style>
</head>
<body>
        <div id="wrapper">
            <video id="video" autoplay muted playsinline></video>
            <canvas id="camera-canvas"></canvas>
            <canvas id="rect-canvas"></canvas>
            <span id="qr-msg">QRコード: 見つかりません</span>
            <button onclick="checkImage()" id="reload">もう一度</button>
        </div>
    <script src="../JS/jsQR.js"></script>
</body>
</html>

<script>
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

    const checkImage = () => {
        // imageDataを作る
        const imageData = ctx.getImageData(0, 0, contentWidth, contentHeight);
        // jsQRに渡す
        const code = jsQR(imageData.data, contentWidth, contentHeight);

        // 検出結果に合わせて処理を実施
        if (code) {
            console.log("QRcodeが見つかりました", code);
            drawRect(code.location);
            document.getElementById('qr-msg').innerHTML = `QRコード：<a href="${code.data}">${code.data}</a>`;

            // タイマーを停止
            if (checkImageTimer) {
                clearTimeout(checkImageTimer);
                setTimeout(() => { rectCtx.clearRect(0, 0, contentWidth, contentHeight); }, 500);

                console.log("QRコードが検出されたため、タイマーを停止しました。");
            }
        } else {
            console.log("QRcodeが見つかりません…", code);
            rectCtx.clearRect(0, 0, contentWidth, contentHeight);
            document.getElementById('qr-msg').textContent = `QRコード: 見つかりません`;

            // タイマーをセット
            checkImageTimer = setTimeout(() => { checkImage(); }, 500);
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
UPDATE yoyaku
SET status = 1
WHERE 
  area_id = :area_id AND 
  location = :location AND 
  username = :username AND 
  reservation_date = CURDATE() AND 
  start_time - INTERVAL 15 MINUTE <= NOW() AND 
  start_time + INTERVAL 30 MINUTE > NOW(); -->


