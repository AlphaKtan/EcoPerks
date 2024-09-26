
<select name="area" id="areaSelect">
    <option value="">選択してください</option>
    <?php 
    for($i=1; $i <= 25; $i++){
        echo '<option value="' . $i . '">' . 'エリア'. $i .'</option>';
    } ?>
</select>

<div id="areaInfo"></div>

<script>
document.getElementById('areaSelect').addEventListener('change', function() {
    var areaValue = this.value;

    if (areaValue) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'get_time_chage.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send('area=' + areaValue);
        xhr.onreadystatechange = function() {
            try {
                const jsonData = JSON.parse(xhr.responseText); // JSONをパース
                let output = "";
                jsonData.forEach(function(item) {
                    output += "施設名: " + item.facility_name + "<br>";
                });
                document.getElementById('areaInfo').innerHTML = output;
            } catch (e) {
                console.error("JSONパースエラー: ", e);
                document.getElementById('areaInfo').innerHTML = "データを取得できませんでした。";
            }
        };
    } else {
        document.getElementById('areaInfo').innerHTML = '';
    }
});
</script>

