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
            // if(area_name!=test.facility_name){
            //     let new_element = document.createElement('h3');
            //     new_element.textContent = test.facility_name;
            //     yoyaku.appendChild(new_element);
            //     area_name = test.facility_name;
            // }

            // 予約情報をセット
            let deleteDiv = document.createElement('div');
            deleteDiv.classList.add("deleteDiv");
            let new_element = document.createElement('div');
            // let formDelete = document.createElement('form');
            let inputDelete = document.createElement('input');
            inputDelete.type = 'button';
            inputDelete.value = '削除';
            
            // 削除ボタンのイベントリスナーを追加
            inputDelete.addEventListener('click', function() {
                // 予約を削除するAPIを呼び出す
                $.ajax({
                    type: "POST",
                    url: "../PHP/delete_reservation.php", // 削除用のエンドポイント
                    data: { reservation_id: test.id }, // 予約のIDを送信
                    dataType: "json"
                }).done(function(response) {
                    if (response.success) {
                        // 削除成功の処理
                        alert("予約が削除されました。");
                        deleteDiv.remove(); // 削除された予約情報を表示から消去
                    } else {
                        alert("削除に失敗しました。");
                    }
                }).fail(function() {
                    alert("削除処理中にエラーが発生しました。");
                });
            });
            let startDate = new Date(test.start_time);
            let endDate = new Date(test.end_time);
            let yearOnly = startDate.getFullYear();
            let monthOnly = startDate.getMonth();
            let dayOnly = startDate.getDay();
            let hourOnly = startDate.getHours().toString().padStart(2, '0');
            let minutes = startDate.getMinutes().toString().padStart(2, '0');

            let endDayOnly = endDate.getDay();
            let endHourOnly = endDate.getHours().toString().padStart(2, '0');
            let endMinutes = endDate.getMinutes().toString().padStart(2, '0');
            let new_element2 = document.createElement('div');
            let facility_element = document.createElement('div');
            facility_element.textContent = test.facility_name;
            
            deleteDiv.appendChild(facility_element);
            new_element.textContent = " 開催日付 : "+yearOnly+"年"+monthOnly+"月"+dayOnly+"日";
            new_element2.textContent = "予定時刻 : "+hourOnly+":"+minutes+" ～ "+endHourOnly+":"+endMinutes;
            deleteDiv.appendChild(new_element);
            deleteDiv.appendChild(new_element2);
            deleteDiv.appendChild(inputDelete);
            yoyaku.appendChild(deleteDiv);
        });
    });
}
