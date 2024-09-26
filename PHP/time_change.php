
<select name="area" id="areaSelect">
    <option value="">選択してください</option>
    <?php 
    for($i=1; $i <= 25; $i++){
        echo '<option value="' . $i . '">' . 'エリア'. $i .'</option>';
    }
    ?>
    <option value="">選択してください</option>


</select>

<div id="areaInfo"></div>

<script>
document.getElementById('areaSelect').addEventListener('change', function() {
    var areaValue = this.value;

    if (areaValue) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'get_time_chage.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                document.getElementById('areaInfo').innerHTML = xhr.responseText;
            }
        };
        xhr.send('area=' + areaValue);
    } else {
        document.getElementById('areaInfo').innerHTML = '';
    }
});
</script>

