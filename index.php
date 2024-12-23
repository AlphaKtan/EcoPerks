<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/destyle.css@1.0.15/destyle.css"/>
    <link rel="stylesheet" href="CSS/indexStyle.css">
    <link rel="stylesheet" href="CSS/hannbaka.css">
    <script src="Js/jquery-3.7.1.min.js"></script>
    <title>Google マップの表示</title>
    <style>
        #map {
            height: 360px; /* マップの表示領域の高さ */
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
        /* .divBox.active{
            background-color:seashell !important;
        } */
        .no_area{
            background-color:#3636365b !important;
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
    </style>
</head>
<body>
    <header>
        <div class="flexBox">
            <div class="menu">
                <div class="openbtn"><span></span><span></span><span></span></div>
                <nav id="g-nav">
                    <div id="g-nav-list"><!--ナビの数が増えた場合縦スクロールするためのdiv※不要なら削除-->
                        <ul>
                            <li><a href="#">Top</a></li>
                            <li><a href="php/coupons.php">クーポン</a></li> 
                            <li><a href="php/ReserveCheck_Customer.php">予約確認</a></li> 
                            <li><a href="php/Mypage_user.php">Mypage</a></li> 
                        </ul>
                    </div>
                </nav>
            </div>
            <div class="logo">
                <img src="img/logo_yoko.svg" alt="" class="logo2">
            </div>
            <div class="icon">
                <form action="php/logout.php" method="post" class = "logout_form">
                    <button type="submit">ログアウト</button>
                </form>
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
    <div id="map" style="height: 800px; display: block;"></div>

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
            console.log(data);
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
            if (zoom > 11) {
                div.innerHTML = `<span class="spanBox" style="display:none;">${coord}</span>`;
            }
            
            // console.log(zoom);
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
            // console.log(div.id);
            div.classList.add('divBox');
            // div.classList.add('active');
            // 次のdiv要素のidに進める
            this.nextId++;
            
            return div;
        };

        var map = new google.maps.Map(document.getElementById('map'), {
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
        });

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
                    console.log(area);
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
                console.log('No tiles found.');
                return;
            }

            // 各タイルをループ処理
            point.forEach((element, index) => {
                const spanBox = element.querySelector('span');
                
                console.log(spanBox.textContent);
                console.log(element.textContent);
                
                const findMethod = locationID.find(int => int == spanBox.textContent);
                const findMethod2 = locationID.find(int => int == element.textContent);

                console.log("ファインド1"+findMethod);
                console.log("ファインド2"+findMethod2);
                

                // エリアをとってきたやつを入れる
                feach.forEach(function(fa) {
                    if (findMethod === fa.coord) {
                        element.innerHTML += `<div>エリア${fa.area_id}</div>`;
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
            }, 5000); // 2msごとにチェック
        // }
        


        function rairu() {
            $.ajax({
                type: "POST",
                url: "PHP/area_feach.php",
                dataType: "json",
            }).done(function(area){
                feach = area;
                console.log(feach);
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
                
                console.log(data);
                
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
