<select name="area" id="areaSelect">
    <option  hidden value="">選択してください</option>
    <option value="">すべて</option>
    <?php 
    for($i=1; $i <= 25; $i++){
        echo '<option value="' . $i . '">' . 'エリア'. $i .'</option>';
    }
    ?>
</select>

<select name="facility_name" id="">
    <option  hidden value="">選択してください</option>
    <option value="">すべて</option>
</select>

<div id="areaInfo"></div>

<script>
document.getElementById('areaSelect').addEventListener('change', function() {
    var areaValue = this.value;
    
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'get_time_chage.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send('area=' + areaValue);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                try {
                    const jsonData = JSON.parse(xhr.responseText); // JSONをパース
                
                    // 施設名と時間帯をマッピングするためのオブジェクト
                    let facilityMap = {};

                    jsonData.forEach(item => {
                        // facility_infoとtime_infoが存在するかを確認
                        if (item.facility_info && item.time_info) {
                            let facilityName = item.facility_info.facility_name;
                            let timeRange = item.time_info.start_time + ' ～ ' + item.time_info.end_time + ' 状態 ' + item.time_info.status;

                            // 施設名がまだマップに登録されていなければ、初期化
                            if (!facilityMap[facilityName]) {
                                facilityMap[facilityName] = new Set(); // 重複を排除するためにSetを使用
                            }
                            // 時間帯をセットに追加（重複しないように）
                            facilityMap[facilityName].add(timeRange);
                        }
                    });

                    // 施設情報をHTMLに整形
                    let facilityInfo = Object.keys(facilityMap).map(function(facilityName) {
                        let times = Array.from(facilityMap[facilityName]).join('<br>'); // Setを配列に変換して時間帯を連結
                        return '<strong>' + facilityName + '</strong><br>' + times; // 施設名と時間帯をまとめる
                    }).join('<br><br>'); // 各施設情報の間に改行を追加

                    // HTMLを更新
                    document.getElementById('areaInfo').innerHTML = facilityInfo;
                } catch (error) {
                    document.getElementById('areaInfo').innerHTML = xhr.responseText;
                }

            }
        };
});
</script>
