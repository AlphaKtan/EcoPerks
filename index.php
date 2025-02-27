<?php
    session_start();
    $URL = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $_SESSION['URL'] = $URL;
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['login_message'] = "ログインしてください。"; // メッセージをセッションに保存
        header('Location: php/message.php');
        exit;
    }
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    }
        


    require_once('Model/dbmodel.php');
    $pdo = dbConnect();

    try {
        $yoyakusql = "SELECT username FROM users_kokyaku INNER JOIN users ON users_kokyaku.user_id = users.id WHERE users.id = :user_id";
        $stmt = $pdo->prepare($yoyakusql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $sql = "SELECT imgpath FROM users WHERE id = :id";
        $stmt2 = $pdo->prepare($sql);
        $stmt2->bindValue(':id', $user_id);
        $stmt2->execute();

        $image = $stmt2->fetch();

        } catch (PDOException $e) {
            echo "<p>データベースエラー: " . $e->getMessage() . "</p>";
        } catch (Exception $e) {
            echo "<p>エラー: " . $e->getMessage() . "</p>";
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/destyle.css@1.0.15/destyle.css"/>
    <link rel="stylesheet" href="CSS/indexStyle.css">
    <link rel="stylesheet" href="CSS/hannbaka.css">
    <script src="Js/jquery-3.7.1.min.js"></script>
    <title>Google マップの表示</title>
    <style>
        #map {
            height: 800px; 
            width: 70%; /* マップの表示領域の幅 */
            margin-left: auto;
            margin-right: auto;
            margin-top: 40px;
        }

        #resetButton {
            background-color: white;
            padding: 10px;
            cursor: pointer;
            box-shadow: rgba(0, 0, 0, 0.3) 0px 1px 4px -1px;
        }

        .reset {
            position: absolute;
            top: 10px !important;
            left: 10px !important;
            z-index: 5;
        }

        .no_area{
            background-color:#3636365b;
        }



        .level_4{
            background-color: rgba(255, 0, 0, 0.7);
        }

        .level_3{
            background-color: rgba(255, 0, 0, 0.5);
        }

        .level_2{
            background-color: rgba(255, 0, 0, 0.3);
        }

        .level_1{
            background-color: rgba(255, 0, 0, 0.1);
        }
        .selectBox {
            background-color: rgba(0, 0, 255, 0.5);
        }

        #search {
            width: 40%;
            border: solid 1px;
            background: white;
            margin: 5px 5px 5px 8%;
            padding: 10px;
        }

        .facility {
            display: block;
            width: 80%;
            margin: 10px 10px 10px 8%;
            padding: 15px;
            border: solid 1px #43AEA9;
            background: #fff;
            transition-duration: .4s;
        }

        .facility:hover {
            transform: scale(1.1);
        }
        .menu-item {
            display: flex;
            align-items: center;
            /* justify-content: flex-start; */
            width: 100%;
            padding: 10px 0 10px 5px;
            margin-bottom: 10px;
            margin-left: 30px;
            cursor: pointer;
            border-radius: 8px;
            background-color: white;
            display: flex;
            align-items: center;
            gap: 10px;
            width: 85%;
            height: 100%;
            border-radius: 8px;
            padding: 10px 5px;
        }

        .menu-list {
            list-style-type: none;
            padding: 0;
            width: 100%;
            margin-bottom: auto;   
        }

        .a_link{
            display: flex;
            align-items: center;
            gap: 8px;
            width: 100%;
            height: 100%;
        }

        .logout_form{
            display: flex;
            justify-content: right;
            align-items: center;
            height: 101%;
            margin: 0px 25px;
        }

        .iconImg{
            border-radius: 50%;
            object-fit: cover;
        }

        .sub_header_box2{
            padding-left: 5px;
            display: flex;
            flex-direction: column;
            justify-content: space-around;
            align-items: flex-start;
        }

        @media screen and (max-width: 768px) {
            #map {
                height: 400px; 
                width: 90%; /* マップの表示領域の幅 */
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="flexBox">
            <div class="menu">
                <div class="openbtn"><span></span><span></span><span></span></div>
                <nav id="g-nav">
                    <div id="g-nav-list"><!--ナビの数が増えた場合縦スクロールするためのdiv※不要なら削除-->
                        <ul class="menu-list">
                            <li class="menu-item"><a href="php/Mypage_user.php" class="a_link">マイページ</a></li>
                            <li class="menu-item"><a href="php/coupons.php" class="a_link">クーポン</a></li>
                            <li class="menu-item"><a href="php/ReserveCheck_Customer.php" class="a_link">予約確認</a></li>
                            <li class="menu-item"><a href="php/qr.php" class="a_link">QR読み取り</a></li>
                        </ul>
                    </div>
                </nav>
            </div>
            <div class="logo">
                <img src="img/logo_yoko.svg" alt="" class="logo2">
            </div>
            <div class="icon">
                <div class="logout_form">
                    <img src="images/<?php echo $image['imgpath']; ?>"  width="50px" height="50px" class="iconImg">
                </div>
            </div>
        </div>

    <div class="sub_header">
        <div class="sub_header_box1">
            <div style="display: flex;">
                <p style="padding-left: 10px; color:#ffff;"><a href="#">マップ</a></p>
            </div>
        </div>
        <div class="sub_header_box2" style="border-left:solid 1px #ffff; color:#ffff;">
            <p class="user_Name">ユーザーネーム</p>
            <p>
                <?php
                    if($row){
                        foreach($row as $rows){
                        $username = $rows['username'];
                        echo $username;
                        }
                    }
                ?>
            </p>
        </div>
    </div>
    </header>




    <div class="search-toggle">
        <button onclick='mapDisplay()' id="mapSearchBtn">地図検索</button>
        <button onclick='searchDisplay()' id="keywordSearchBtn">キーワード検索</button>
    </div>
    
    <div style="display: flex;">
    <p style="margin: 0 auto;">地図をクリックすると、下に施設一覧が表示されます</p>
    </div>

    <!-- 元の位置に戻るボタン -->
    <div id="map" style="display: block;"></div>

    <div id="searchBar" style="display: none;">
        <input type="text" id="search" placeholder="キーワードを入力してください" />
    </div>

    <div class="areainfo"></div>
    <div class="results"></div>





<script>
    function mapDisplay() {
        const mapDiv = document.getElementById('map');
        const searchBar = document.getElementById('searchBar');
        let result = document.querySelector('.results');
        
        result.innerHTML = "";

        mapDiv.style.display = 'block';
        searchBar.style.display = 'none';
    }

    function searchDisplay() {
        const mapDiv = document.getElementById('map');
        const searchBar = document.getElementById('searchBar');
        const search = document.getElementById('search');
        let selectedElement = document.querySelector('.areainfo');
        // 空にする
        selectedElement.innerHTML = '';

        mapDiv.style.display = 'none';
        searchBar.style.display = 'block';

        search.value = '';
        search.focus();
    }

    let search = document.getElementById("search");
    let result = document.querySelector('.results');
    result.innerHTML = "";

    function searchFacilities() {
        const keyword = document.getElementById("search").value.trim();
        // 入力が空の場合はリクエストしない
        if (keyword === "") {
            result.innerHTML = ""; // 空の場合はリセット
            return;
        }
        $.ajax({
            type: "POST",
            url: "./test_output.php",
            dataType: "json",
            data: { keyword: keyword }
        }).done(function(data) {
            result.innerHTML = "";
            data.forEach(function(results) {
            result.textContent += `
                <a href='PHP/yoyaku.php?location=${results.id}' link class="facility">
                    ${results.facility_name} (${results.romaji})
                </a>
            `;
            });
            result.innerHTML = result.textContent;
                    
        }).fail(function(jqXHR, textStatus, errorThrown)  {
            console.error("AJAXリクエストに失敗しました");
            console.error("HTTPステータス:", jqXHR.status); // ステータスコード
            console.error("レスポンス内容:", jqXHR.responseText); // サーバーの返答内容
            console.error("エラーメッセージ:", errorThrown);
        }); 
    }

    search.oninput = searchFacilities;


    var initialZoom = 12; // 初期ズームレベル
    let center = {lat: 35.010121680059264, lng: 135.7370724742484};
    
    function initMap() {
        function Grid(size) {
            this.tileSize = size;
        }

        Grid.prototype.getTile = function(coord, zoom, ownerDocument) {
            var div = ownerDocument.createElement('div');
            if (zoom > 10) {
                div.innerHTML = `<span class="spanBox" style="display:none;">${coord}</span>`;
            }
            
            div.style.width = this.tileSize.width + 'px';
            div.style.height = this.tileSize.height + 'px';
            div.style.borderStyle = 'solid';
            div.style.borderWidth = '1px';

            // Gridオブジェクトのインスタンスプロパティとして持つnextIdを使って連番のidを設定する
            if (!this.nextId) {
                this.nextId = 1; // 最初のdiv要素のidを1から始める
            }

            // div要素にidを設定する
            div.id = "div" + this.nextId;
            div.classList.add('divBox');
            // div.classList.add('active');
            // 次のdiv要素のidに進める
            this.nextId++;
            
            return div;
        };


            // 画面サイズを取得して判定
    var isMobile = window.innerWidth <= 768; // スマホの場合

            // スマホの場合の設定
    if (isMobile) {
        initialZoom = 11; // スマホでは初期ズームを11に設定
        center = { lat: 34.985121680059264, lng: 135.7370724742484 };
    } else {
        initialZoom = 12; // パソコンでは初期ズームを12に設定
    }

    // マップの設定
    var mapOptions = {
        zoom: initialZoom, // 初期ズームレベルを設定
        center: center,
        mapTypeId: 'roadmap',
        minZoom: initialZoom,
        maxZoom: initialZoom + 3,
        restriction: {
            latLngBounds: {
                north: 35.510121680059264, // 最北の緯度
                south: 34.510121680059264, // 最南の緯度
                east: 136.7370724742484,   // 最東の経度
                west: 134.7370724742484,   // 最西の経度
            },
        },
        disableDefaultUI: true, // すべてのデフォルトUIを無効化
        mapTypeControl: false, // 地図タイプ切り替えコントロールを無効化
    };

    // Google Mapを作成
    var map = new google.maps.Map(document.getElementById('map'), mapOptions);       

        function updateGridSize() {
            var zoom = map.getZoom();
            var baseSize = 100; // 基本サイズ
            var tileSize = baseSize * Math.pow(2, zoom - 12); // ズームレベル12を基準とする
            map.overlayMapTypes.clear(); // 以前のオーバーレイをクリア
            map.overlayMapTypes.insertAt(0, new Grid(new google.maps.Size(tileSize, tileSize)));
        }

        map.addListener('zoom_changed', updateGridSize); // ズームレベルが変わったときに呼び出す
        updateGridSize(); // 初期設定
        
        // タイルにクリックした時のイベント
        google.maps.event.addDomListener(map.getDiv(), 'click', function(event) {
            
            var tiles = document.getElementsByClassName('divBox');
                    
            for (var i = 0; i < tiles.length; i++) {
                var tile = tiles[i];
                if (tile && isMouseOverTile(event, tile)) {
                    var areaId = getAreaIdFromTile(tile.querySelector('span').innerHTML); // タイルの内容に基づいてエリアIDを取得
                    if(areaId === undefined) {
                        areaId = null;
                    }
                    
                    if (areaId !== null) {
                        // 前に選択されていたセルの選択を解除
                        if (previouslySelected) {
                            previouslySelected.classList.remove('selectBox');
                        }
                        // 新しく選択されたセルにselectedクラスを追加
                        tile.classList.add('selectBox');
                        previouslySelected = tile;
                        // window.location.href = 'php/search.php?area_id=' + areaId; // PHPスクリプトにエリアIDを送信
                        first(areaId);
                    }
                }
            }
        });

        let previouslySelected = null;
        function first(areaId) {
            let selectedElement = document.querySelector('.areainfo');
            // 空にする
            selectedElement.innerHTML = '';

            $.ajax({
                type: "POST",
                url: "PHP/indexinfo.php",
                dataType: "json",
                data: { area_id: areaId }
            }).done(function(data) {
                selectedElement.innerHTML += `<h2>エリア${areaId}</h2>`;
                data.forEach(function(area) {
                    selectedElement.innerHTML += `
                    <a href='PHP/yoyaku.php?location=${area.id}' link class="facility">
                        ${area.facility_name} (${area.romaji})
                    </a>`;
                });
            }).fail(function(jqXHR, textStatus, errorThrown)  {
                console.error("AJAXリクエストに失敗しました");
                console.error("HTTPステータス:", jqXHR.status); // ステータスコード
                console.error("レスポンス内容:", jqXHR.responseText); // サーバーの返答内容
                console.error("エラーメッセージ:", errorThrown);
            }); 
        }
        // タイル内容からエリアIDを取得する関数（例: 座標に基づくマッピング）
        function getAreaIdFromTile(tileContent) {
            switch (tileContent) {
                case "(9196, 4151)":
                    return 1;
                    break;
                case "(9197, 4151)":
                    return 2;
                    break;
                case "(9194, 4152)":
                    return 3;
                    break;
                case "(9195, 4152)":
                    return 4;
                    break;
                case "(9196, 4152)":
                    return 5;
                    break;
                case "(9197, 4152)":
                    return 6;
                    break;
                case "(9198, 4152)":
                    return 7;
                    break;
                case "(9195, 4153)":
                    return 8;
                    break;
                case "(9196, 4153)":
                    return 9;
                    break;
                case "(9197, 4153)":
                    return 10;
                    break;
                case "(9198, 4153)":
                    return 11;
                    break;
                case "(9194, 4154)":
                    return 12;
                    break;
                case "(9195, 4154)":
                    return 13;
                    break;
                case "(9196, 4154)":
                    return 14;
                    break;
                case "(9197, 4154)":
                    return 15;
                    break;
                case "(9198, 4154)":
                    return 16;
                    break;
                case "(9194, 4155)":
                    return 17;
                    break;
                case "(9195, 4155)":
                    return 18;
                    break;
                case "(9196, 4155)":
                    return 19;
                    break;
                case "(9197, 4155)":
                    return 20;
                    break;
                case "(9198, 4155)":
                    return 21;
                    break;
                case "(9195, 4156)":
                    return 22;
                    break;
                case "(9196, 4156)":
                    return 23;
                    break;
                case "(9197, 4156)":
                    return 24;
                    break;
                case "(9198, 4156)":
                    return 25;
                    break;
                default:
                    break;
            }
        }
        var coordList = [];
        var feach = [];
        //IDが付与されていないタイルにclass"active"を付与してカラーを変える
        const locationID = [
            "(9196, 4151)", "(9197, 4151)", "(9194, 4152)", "(9195, 4152)", "(9196, 4152)",
            "(9197, 4152)", "(9198, 4152)", "(9195, 4153)", "(9196, 4153)", "(9197, 4153)",
            "(9198, 4153)", "(9194, 4154)", "(9195, 4154)", "(9196, 4154)", "(9197, 4154)",
            "(9198, 4154)", "(9194, 4155)", "(9195, 4155)", "(9196, 4155)", "(9197, 4155)",
            "(9198, 4155)", "(9195, 4156)", "(9196, 4156)", "(9197, 4156)", "(9198, 4156)"
        ];

        // タイルが DOM に追加されるまで定期的に確認
        function checkTiles(coordList) {
            // `.divBox`クラスの要素を取得
            const point = document.querySelectorAll('.divBox');

            // 取得できない場合は終了
            if (point.length === 0) {
                console.log('タイルがないよ');
                return;
            }

            // 各タイルをループ処理
            point.forEach((element, index) => {
                const spanBox = element.querySelector('span');
                
                const findMethod = locationID.find(int => int == spanBox.textContent);
                const findMethod2 = locationID.find(int => int == element.textContent);

                // エリアをとってきたやつを入れる
                feach.forEach(function(fa) {
                    if (findMethod === fa.coord) {
                        let divBoxArea_id = element.querySelector('.area_id');
    
                        // すでに存在する .area_id の span を削除
                        if (divBoxArea_id) {
                            divBoxArea_id.remove();
                        }

                        element.innerHTML += `<span class="area_id">エリア${fa.area_id}</span>`;
                    }
                });

                // ゴミアンケートのデータベースにあるエリアを取得
                coordList.forEach(function(coordID) {
                    if(coordID.area === findMethod) {
                        element.classList.remove('level_1', 'level_2', 'level_3', 'level_4');
                        switch (coordID.level) {
                            case 1:
                                element.classList.add('level_1');
                                break;
                            case 2:
                                element.classList.add('level_2');
                                break;
                            case 3:
                                element.classList.add('level_3');
                                break;
                            case 4:
                                element.classList.add('level_4');
                                break;
                        }
                    }
                });
                
                if (!findMethod) {
                    // 一致する場合にクラスを付与
                    element.classList.add('no_area');
                }
            });
        }

        // if ( document.readyState !== "loading" ) {
            const interval = setInterval(() => {
                coord();
                rairu();
                console.log("更新");
            }, 2000); // 2msごとにチェック
        // }
        


        function rairu() {
            $.ajax({
                type: "POST",
                url: "PHP/area_feach.php",
                dataType: "json",
            }).done(function(area){
                feach = area;
            }).fail(function(jqXHR, textStatus, errorThrown)  {
                console.error("AJAXリクエストに失敗しました");
                console.error("HTTPステータス:", jqXHR.status); // ステータスコード
                console.error("レスポンス内容:", jqXHR.responseText); // サーバーの返答内容
                console.error("エラーメッセージ:", errorThrown);
            });
        }
        function coord() {
            $.ajax({
                type: "POST",
                url: "coord.php",
                dataType: "json",
            }).done(function(data) {
                coordList = data; // グローバル変数にデータを格納
                checkTiles(coordList);
            }).fail(function(jqXHR, textStatus, errorThrown)  {
                console.error("AJAXリクエストに失敗しました");
                console.error("HTTPステータス:", jqXHR.status); // ステータスコード
                console.error("レスポンス内容:", jqXHR.responseText); // サーバーの返答内容
                console.error("エラーメッセージ:", errorThrown);
            });
        }


         
        // タイルにマウスがクリックしたかどうかを判定する関数
        function isMouseOverTile(event, tile) {
            var tileRect = tile.getBoundingClientRect();
            return event.clientX >= tileRect.left && event.clientX <= tileRect.right &&
                event.clientY >= tileRect.top && event.clientY <= tileRect.bottom;
        }
    }

    window.initMap = initMap;
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDJo1xgL-YovharbZkPctzW07zRCy61qp4&callback=initMap"></script>
   
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<!--自作のJS-->
<script src="JS/hannbaka.js"></script>
</body>
</html>
