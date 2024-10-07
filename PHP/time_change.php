<select name="area" id="areaSelect">
    <option hidden value="">選択してください</option>
    <option value="">すべて</option>
    <?php 
    for ($i = 1; $i <= 25; $i++) {
        echo '<option value="' . $i . '">' . 'エリア' . $i . '</option>';
    }
    ?>
</select>
<br>
<br>
<br>
<select name="facility_name" id="facilitySelect">
    <option hidden value="">選択してください</option>
</select>
<br>
<br>
<br>
<input type="date" id="reservation_date" name="reservation_date">

<div id="areaInfo"></div>

<script>
// 施設名と時間帯をマッピングするためのオブジェクト
let facilityMap = {};
let jsonData = [];

document.getElementById('areaSelect').addEventListener('change', function() {
    let selectedFacility = this.value;

    document.getElementById('areaInfo').innerHTML = '';

    if (facilityMap[selectedFacility]) {
        let times = Array.from(facilityMap[selectedFacility]).join('<br>'); // Setを配列に変換して時間帯を連結
        document.getElementById('areaInfo').innerHTML = '<strong>' + selectedFacility + '</strong><br>' + times;
    }   
    ariaTimeFilter();
});

document.getElementById('facilitySelect').addEventListener('change', function() {
    ariaTimeFilter();
});

document.getElementById('reservation_date').addEventListener('change', function() {
    ariaTimeFilter();
});

function ariaTimeFilter() {
    alert("ああああ");
}





</script>
