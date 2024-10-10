/**
 * セレクトボックスをクリアする処理
 * param sl クリアしたいセレクトボックス
 */
function selectDataClear(sl){
    // 指定した子要素をすべて削除
    while(sl.lastChild){
        sl.removeChild(sl.lastChild);
    }

    // 最初のレコードを追加
    var option = document.createElement("option");
    option.text = "すべて";
    option.value = "";
    sl.appendChild(option);
}

/**
 * エリア情報を取得する処理
 * param areaId  エリアID
 */
function getAreaData(areaId){

    // エリアIDが取得できない場合は処理終了
    if(areaId == ""){ return; }

    // エリア情報があれば情報取得する
    $.ajax({
        type: "POST",
        url: "../PHP/area.php",
        dataType: "json",
        data: {area: areaId}
    }).done(function(data) {
        // 取得したデータをループで回す
        data.forEach(function(test){
            // optionがある場合、データのループを実施
            var option = document.createElement("option");
            option.text = test.facility_name;
            option.value = test.id;
            facility.appendChild(option);
        });
    });
}

/**
 * 予約データを取得する
 * param areaId  エリアID
 * param facility_id 施設ID
 * param time 時間
 */
function getYoyakuData(areaId,facility_id,time){
    area_name = ""

    // エリア情報があれば情報取得する
    $.ajax({
        type: "POST",
        url: "../PHP/get_time_change.php",
        dataType: "json",
        data: {area: areaId,facility: facility_id, date: time}
    }).done(function(data) {

        // データがある場合、データのループを実施
        data.forEach(function(test){
            
            // 施設名のラベルを設定 
            if(area_name!=test.facility_name){
                let new_element = document.createElement('h3');
                new_element.textContent = test.facility_name;
                yoyaku.appendChild(new_element);
                area_name = test.facility_name;
            }

            // 予約情報をセット
            let new_element = document.createElement('p');
            new_element.textContent = test.start_time+" ～ "+test.end_time;
            yoyaku.appendChild(new_element);
        });
    });
}