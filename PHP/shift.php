<?php
session_start();
require '../Model/dbModel.php';

// DB接続
$pdo = dbConnect();

$directory="管理者ページ>ゴミ拾い日追加";

$URL = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$_SESSION['URL'] = $URL;
if (!isset($_SESSION['admin_id'])) {
    $_SESSION['login_message'] = "ログインしてください。"; // メッセージをセッションに保存
    header('Location: ./admin_message.php');
    exit;
}
if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
}

// タイムゾーンを設定
date_default_timezone_set('Asia/Tokyo');

// 前月・次月リンクが押された場合は、GETパラメーターから年月を取得
if (isset($_GET['ym'])) {
    $ym = $_GET['ym'];
} else {
    // 今月の年月を表示
    $ym = date('Y-m');
}

// タイムスタンプを作成し、フォーマットをチェックする
$timestamp = strtotime($ym . '-01');
if ($timestamp === false) {
    $ym = date('Y-m');
    $timestamp = strtotime($ym . '-01');
}

// 今日の日付 フォーマット　例）2021-06-3
$today = date('Y-m-d');

// カレンダーのタイトルを作成　例）2021年6月
$html_title = date('Y年n月', $timestamp);

// 前月・次月の年月を取得
// 方法１：mktimeを使う mktime(hour,minute,second,month,day,year)
$prev = date('Y-m', mktime(0, 0, 0, date('m', $timestamp)-1, 1, date('Y', $timestamp)));
$next = date('Y-m', mktime(0, 0, 0, date('m', $timestamp)+1, 1, date('Y', $timestamp)));

// 方法２：strtotimeを使う
// $prev = date('Y-m', strtotime('-1 month', $timestamp));
// $next = date('Y-m', strtotime('+1 month', $timestamp));

// 該当月の日数を取得
$day_count = date('t', $timestamp);
// セッションに保存
$_SESSION["day_count"] = $day_count;

// １日が何曜日か　0:日 1:月 2:火 ... 6:土
// 方法１：mktimeを使う
$youbi = date('w', mktime(0, 0, 0, date('m', $timestamp), 1, date('Y', $timestamp)));
// 方法２
// $youbi = date('w', $timestamp);


// カレンダー作成の準備
$weeks = [];
$week = '';

// 第１週目：空のセルを追加
// 例）１日が火曜日だった場合、日・月曜日の２つ分の空セルを追加する
$week .= str_repeat('<td></td>', $youbi);

for ( $day = 1; $day <= $day_count; $day++, $youbi++) {

    // 2021-06-03
    $date = $ym . '-' . sprintf('%02d', $day);
    if ($today == $date) {
        $week .= "<td class='today' data-date='$date' onclick='selectDate(\"$date\")'>" . $day . " ";
    } else {
        $week .= "<td data-date='$date' onclick='selectDate(\"$date\")'>" . $day;
    }
    
    $week .= '</td>';

    // 週終わり、または、月終わりの場合
    if ($youbi % 7 == 6 || $day == $day_count) {

        if ($day == $day_count) {
            // 月の最終日の場合、空セルを追加
            // 例）最終日が水曜日の場合、木・金・土曜日の空セルを追加
            $week .= str_repeat('<td></td>', 6 - $youbi % 7);
        }

        // weeks配列にtrと$weekを追加する
        $weeks[] = '<tr>' . $week . '</tr>';

        // weekをリセット
        $week = '';
    }
}
// プリセットの時間を取得
$sql = "SELECT id, start_time, end_time FROM preset ORDER BY start_time, end_time ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);

//デバッグ用の出力
// echo "<pre>";
// print_r($row[0]);    
// echo "</pre>";
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>シフト登録画面</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../CSS/shiftStyle.css">
</head>

<body>

<?php include './admin_header.php' ?>

<style>

.facilityName{
    margin-left:auto;
}

.container{
        background: white;
        padding: 10px 10px 30px 10px;
        border: solid;
        width: 80%;
        margin: 0 0 0 0;
    }

@media screen and (max-width: 768px) {
    .container{
        background: white;
        padding: 10px 10px 30px 10px;
        border: solid;
        width: 80%;
        margin: 0 0 0 0;
    }

    .shift_look {
        width: 75%;
        margin-bottom: 20px;
        margin-left: 10px;
        background: white;
    }

    #shiftDiv {
        background-color: rgba(0, 0, 0, 0.8);
        padding: 30px;
        width: 80%;
    }
}

</style>
    <div class="container mt-5" style="">
        <h3 class="mb-4" style="">
            <a href="?ym=<?= $prev ?>" style="color:rgb(13, 110, 253);">&lt;</a>
            <span class="mx-3">
                <?= $html_title ?>
            </span>
            <a href="?ym=<?= $next ?>" style="color:rgb(13, 110, 253);">&gt;</a>

            

            <div class="facilityName">
                <?= $_SESSION['facility']; ?>
            </div>
        </h3>
        <table class="table table-bordered" style="margin-bottom: 0 !important;">
            <tr>
                <th>日</th>
                <th>月</th>
                <th>火</th>
                <th>水</th>
                <th>木</th>
                <th>金</th>
                <th>土</th>
            </tr>
            <?php
                foreach ($weeks as $week) {
                    echo $week;
                }
            ?>
        </table>
    </div>
    
    <div class="shift_look"></div>

    <div class="shiftDiv" id="shiftDiv">
        <p style="color: white;">プリセットから追加</p>
    <div class="presetDiv">
    <button onclick='entryTimeFunction()' class="entryTimeBtn">プリセット時間を追加する</button>
    <button onclick='entryTimeFunction()' class="back entryTimeBtn" style="display: none;">プリセットから追加に戻る</button>
        <div class="entryTime" style="display: none; background: white; padding: 5px; margin-bottom: 10px;">
            <form id="presetForm" action="" method="post">
                <label for="start-time" step="1">開始時間:</label><br>
                <input type="time" id="start-time" name="start-time"><br>
                <label for="end-time" step="1">終了時間:</label><br>
                <input type="time" id="end-time" name="end-time"><br>
            </form>
        </div>

        
        <button onclick='entryPresetFunction()' class="entryPreset" style="display: none;">プリセットに追加</button>

        <form action="" method="post" class="form">
        <?php 
            foreach ($row as $rows) {
                // 文字列をDateTimeオブジェクトに変換
                $start_time = new DateTime($rows['start_time']);
                $end_time = new DateTime($rows['end_time']);
                //行のidを取得
                $rowId = $rows['id'];
                
                // とってきた時間帯のid(番号)をクラスに適応
                echo <<<HTML
                <label for="preset{$rowId}" class="box">
                    <input type="checkbox" name="preset" id="preset{$rowId}" value="{$rowId}">
                    <label for="preset{$rowId}">{$start_time->format('H:i')} ～ {$end_time->format('H:i')}</label>
                </label>
                HTML;
            }
        ?>
        </form>

        <select name="" class="coupon">
            <option hidden value="">クーポンの価格</option>
            <option value="50">50</option>
            <option value="100">100</option>
            <option value="150">150</option>
            <option value="200">200</option>
            <option value="250">250</option>
        </select>
        <button onclick='entryFunction()' class="entryBtn" style="">ゴミ拾い日追加</button>
        <script>
            const startTimeInput = document.getElementById("start-time");
            const endTimeInput = document.getElementById("end-time");

            // 分を00に固定する関数
            function setTimeToWholeHour(input) {
                input.addEventListener("input", function() {
                    const [hour] = input.value.split(":");  // 時間を取得
                    input.value = `${hour}:00`;  // 分を00に固定して再設定
                });
            }

            // 開始時間と終了時間が正しい順番かをチェック
            function validateTimes() {
                const startTime = startTimeInput.value;
                const endTime = endTimeInput.value;

                if(startTime && endTime) {
                    // 開始時間が終了時間と同じかそれより後の場合
                    if (startTime > endTime) {
                        alert("開始時間は終了時間よりも前に設定してください。");
                        startTimeInput.value = "";  // 入力値をリセット
                        endTimeInput.value = "";    // 入力値をリセット
                    }
                }
            }

            // 入力された時間が開始時間 < 終了時間 になるように検証
            startTimeInput.addEventListener("input", validateTimes);
            endTimeInput.addEventListener("input", validateTimes);

            // 開始時間と終了時間に対して分を00に固定
            setTimeToWholeHour(startTimeInput);
            setTimeToWholeHour(endTimeInput);
        </script>
<!-- JavaScript -->
<script type="text/javascript">
    let ym = "<?=$ym; ?>";  // 無理やりPHPの$ymをJavaScriptの変数ymに代入
    let day_count = "<?=$day_count; ?>";
    let facility_id = "<?=$_SESSION['location_id']; ?>";
    let area_id = "<?=$_SESSION['admin_area_id']; ?>";

</script>
<script src="../JS/jquery-3.7.1.min.js"></script>
<script src="../JS/bootstrap.min.js"></script>
<script src="../JS/jquery-3.5.1.min.js"></script>
<script src="../JS/moment.min.js"></script>
<script src="../JS/ja.js"></script>
<script src="../JS/bootstrap-datetimepicker.min.js"></script>
<script src="../JS/custom.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>


<script>
    var optval;
    let flag = 1;
    
    $(function(){
        // よく使う要素を変数へ格納する
        var area = document.getElementById("area");
        var facility = document.getElementById("facility");
        var notApplicable = document.getElementById("notApplicable");
        
            let selectedElement = document.querySelector('.shift_look');
            // 空にする
            if(selectedElement) {
                selectedElement.innerHTML = '';
            }
            // エリアを変えたときに<span class="circle"></span>を削除
            circleDeleteAll();
            // 施設が選択されるたびに関数起動

            // if(document.querySelector('.selected')) {
            //     // 施設を切り替えるたびにシフトを取ってくる
            //     fetchShiftData();
            // }
            fetchShiftDates();
        })

    let coupon;
    $('.coupon').on('change', function() {
        coupon = $(this).val(); // 選択された値を取得
        const shiftDivElement = document.getElementById('shiftDiv');
        const errorElement = shiftDivElement.querySelector('.error');
        if (errorElement) {
            errorElement.remove();
        }
    });

// 選ばれた日付マスに色を付ける処理
let previouslySelected = null;
let selectedDate = null;
let shiftDiv = document.getElementsByClassName('shiftDiv');

function selectDate(date) {
    // 新しく選択されたセルにselectedクラスを追加
    let element = event.target;
    if (element.classList.contains('circle')) {
        return;
    }

    // 前に選択されていたセルの選択を解除
    if (previouslySelected) {
        previouslySelected.classList.remove('selected');
    }

    element.classList.add('selected');
    previouslySelected = element;

    // 選択された日付を保持
    selectedDate = date;
    // 日付を選択されたらshiftDivが出てくる関数
    onShiftDiv();
    // 施設名が選択されていてカーソルがあっている状態の時だけシフトを取得
    if (flag === 1) {
        fetchShiftData();
    }
}

function onShiftDiv() {
    // shiftDivのdisplay:none;を解除
    if (selectedDate) {
        for (let i = 0; i < shiftDiv.length; i++) {
            shiftDiv[i].classList.remove('shiftDiv');
        }
    }
}
// カレンダー関連
$(function () {
    var ua = navigator.userAgent;
    if ((ua.indexOf('iPhone') > 0 || ua.indexOf('iPad') > 0 || ua.indexOf('Android') > 0) && ua.indexOf('Mobile') > 0) {
        // スマートフォン・タブレット'
        $('input[name="start_datetime"]').removeClass('task-datetime').attr('type', 'datetime-local');
        $('input[name="end_datetime"]').removeClass('task-datetime').attr('type', 'datetime-local');
        $('input[name="ym"]').removeAttr('id').attr('type', 'month');
        $('input[name="start_date"], input[name="end_date"]').removeClass('search-date').attr('type', 'date');
        $('.visually-hidden').removeClass('visually-hidden').addClass('form-label');
    } else {
        $('.sp-label').remove();
    }

    $('input[type=month]').focus(function() {
        // フォーカスした時
        $('.sp-label').hide();
    }).blur(function(){
        // フォーカスを外した時
        if (!$(this).val()) {
            $('.sp-label').show();
        }
    });

    moment.updateLocale('ja', {
        week: { dow: 1 }
    });
    $('#ymPicker').datetimepicker({
        format: 'YYYY-MM'
    });
    $('.task-datetime').datetimepicker({
        dayViewHeaderFormat: 'YYYY年 MMMM',
        format: 'YYYY/MM/DD HH:mm'
    });
    $('.search-date').datetimepicker({
        dayViewHeaderFormat: 'YYYY年 MMMM',
        format: 'YYYY/MM/DD'
    });
    $('#selectColor').bind('change', function(){
        $(this).removeClass();
        $(this).addClass('form-select').addClass($(this).val());
    });
});

// アラートプリセットに登録できたとき
function presetAmazingSample(data) {
    swal.fire({
    icon: "success",
    title: "プリセットに登録出来ました！！",
    text: data,
    });
}

// アラートシフトが登録できたとき
function amazingSample(data) {
    swal.fire({
    icon: "success",
    title: "シフトを登録出来ました！！",
    text: data,
    });
}

// アラートシフトが登録できなかったとき(重複したとき)
function oopsSwalSample(data) {
  Swal.fire({
    icon: "error", // エラーメッセージのアイコン
    title: "重複エラー", 
    html: data, 
    confirmButtonText: "閉じる" // ボタンのテキスト
  });
}
</script>

<script>
// その他時間追加を出す関数
function entryTimeFunction() {
    let presetForm = document.querySelector('.form');
    let styleForm = getComputedStyle(presetForm);
    let entryTime = document.querySelector('.entryTime');
    let back = document.querySelector('.back');
    let entryPreset = document.querySelector('.entryPreset');
    let entryTimeBtn = document.querySelector('.entryTimeBtn');
    let entryBtn = document.querySelector('.entryBtn');
    let coupon = document.querySelector('.coupon');

    if (styleForm.display !== 'none') {
        // 現在表示されている場合に非表示にする
        presetForm.style.display = 'none';
        entryTime.style.display = 'block';
        back.style.display = '';
        entryPreset.style.display = '';
        entryTimeBtn.style.display = 'none';
        entryBtn.style.display = 'none';
        coupon.style.display = 'none';
    } else {
        // 非表示の場合に再表示
        presetForm.style.display = 'block';
        entryTime.style.display = 'none';
        back.style.display = 'none';
        entryPreset.style.display = 'none';
        entryTimeBtn.style.display = '';
        entryBtn.style.display = '';
        coupon.style.display = '';
    }
// いるかいらんかわからん
    // const shiftDivElement = document.getElementById('shiftDiv');
    // const errorElement = shiftDivElement.querySelector('.error');
    // if (errorElement) {
    //     errorElement.remove();
    // }
}

// プリセットに追加ボタンを押すと追加された時間をプリセットテーブルにinsertする処理
function entryPresetFunction() {
    const startTimePreset = document.getElementById("start-time").value;
    const endTimePreset = document.getElementById("end-time").value;
    $.ajax({
        type: "POST",
        url: "../PHP/shift_entryPreset.php",
        dataType: "json",
        data: { start_time: startTimePreset, end_time: endTimePreset }
    }).done(function(data) {
        
        if(data === "正常に完了") {
            presetAmazingSample(data);
            console.log(data);
            
        } else {
            oopsSwalSample(data);
        }
                
    }).fail(function(jqXHR, textStatus, errorThrown)  {
        console.error("AJAXリクエストに失敗しました");
        console.error("HTTPステータス:", jqXHR.status); // ステータスコード
        console.error("レスポンス内容:", jqXHR.responseText); // サーバーの返答内容
        console.error("エラーメッセージ:", errorThrown);
    }); 
}

// データベースに登録する処理
function entryFunction() {
    if (flag === 1) {       // 施設が選択されている時
            
            // チェックされたチェックボックスの値を取得
            let selectedPresets = [];
            $('input[name="preset"]:checked').each(function() {
                selectedPresets.push($(this).val());
            });
            // クーポンが選択されているかどうか
            let coupon2 = document.querySelector('.coupon');
            if (coupon2.value !== null && coupon2.value !== "") {
                // エリア情報があれば情報取得する
                $.ajax({
                type: "POST",
                url: "../PHP/shift_entry.php",
                dataType: "json",
                data: { presets: selectedPresets, date: selectedDate, area: area_id, facility: facility_id, price: coupon }
                }).done(function(responseData) {
                    console.log("レスポンスデータ:", responseData);
                    if (Array.isArray(responseData)) {
                        responseData.forEach(data => {
                            if (data === "正常に完了") {
                                amazingSample(data);    
                            } else {
                                oopsSwalSample(data);
                            }
                            fetchShiftDates();
                        });
                    } else {
                        console.warn("期待していない形式のデータが返されました:", responseData);
                    }
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    console.error("AJAXリクエストに失敗しました");
                    console.error("HTTPステータス:", jqXHR.status); // ステータスコード
                    console.error("レスポンス内容:", jqXHR.responseText); // サーバーの返答内容
                    console.error("エラーメッセージ:", errorThrown);
                });            
            } else {
                const shiftDivElement = document.getElementById('shiftDiv');
                // 既にエラーメッセージが存在しないか確認
                if (!shiftDivElement.querySelector('.error')) {
                    // エラーを出す処理
                    shiftDivElement.insertAdjacentHTML('afterbegin', '<p class="error" style="color: red; margin-top: 10px;">couponの価格を選択してください</p>');
                } else {
                    const errorStyle = getComputedStyle(errorElement); 
                    if (errorStyle.display === 'none') {
                        errorStyle.display = '';
                    }
                }
            }
    } else if(flag === 0) {
        const shiftDivElement = document.getElementById('shiftDiv');
        // 既にエラーメッセージが存在しないか確認
        if (!shiftDivElement.querySelector('.error')) {
            // エラーを出す処理
            shiftDivElement.insertAdjacentHTML('afterbegin', '<p class="error" style="color: red; margin-top: 10px;">施設名まで選択してください</p>');
        } else {
            const errorStyle = getComputedStyle(errorElement); 
            if (errorStyle.display === 'none') {
                errorStyle.display = '';
            }
        }
    }
}

function fetchShiftDates() {
    $.ajax({
        type: "POST",
        url: "../PHP/shift_circle.php",
        dataType: "json",
        data: { facility: facility_id, ym: ym, day_count: day_count }
    }).done(function(data) {
        // console.log("シフト日付データ:", data); 
        // データが正しく取得されているか確認

        // shiftDates 配列の中に shift_date プロパティがある場合、それを取得
        const shiftDates = data.map(item => item.shift_date); // 例: ["2024-12-04", "2024-12-08"]

        // すべての td 要素を取得
        const tdElements = document.querySelectorAll("td[data-date]");

        // まず、すべての td 要素から円を削除
        tdElements.forEach(function(td) {
            const existingCircle = td.querySelector("span.circle");
            if (existingCircle) {
                td.removeChild(existingCircle); // 既存の円を削除
            }
        });

        // 新たに円を追加
        shiftDates.forEach(function(shiftDate) {
            // tdArray を使って該当する td を探す
            const foundTd = Array.from(tdElements).find(td => td.getAttribute("data-date") === shiftDate);

            if (foundTd) {
                // td に既に <span class="circle"> が含まれていないかチェック
                if (!foundTd.querySelector("span.circle")) {
                    // 一致する td に円を追加
                    const circleSpan = document.createElement("span");
                    circleSpan.classList.add("circle");
                    foundTd.appendChild(circleSpan);
                }
            } else {
                console.log("一致するtdは見つかりませんでした:", shiftDate);
            }
        });
    }).fail(function(jqXHR, textStatus, errorThrown)  {
        console.error("AJAXリクエストに失敗しました");
        console.error("HTTPステータス:", jqXHR.status); // ステータスコード
        console.error("レスポンス内容:", jqXHR.responseText); // サーバーの返答内容
        console.error("エラーメッセージ:", errorThrown);
    }); 
}

function circleDeleteAll() {
    const circles = document.querySelectorAll("span.circle");

    // 取得した <span class="circle"> をループで削除
    circles.forEach(function(circle) {
        circle.parentElement.removeChild(circle);
    });
}

function fetchShiftData() {
    let selectedElement = document.querySelector('.shift_look');
    // 空にする
    if(selectedElement) {
        selectedElement.innerHTML = '';
    }
    $.ajax({
        type: "POST",
        url: "../PHP/shift_fetch.php",
        dataType: "json",
        data: { reservation_date: selectedDate, facility: facility_id }
    }).done(function(data) {
        if(selectedElement) {
            selectedElement.innerHTML = '';
        }
        // もしdataが空の場合は「シフトが入っていません」を表示
        if (data.length === 0) {
            if (selectedElement) {
                let newDate = new Date(selectedDate);
                let getWeek = newDate.getDay();
                const weekdays = ['日', '月', '火', '水', '木', '金', '土'];

                selectedElement.innerHTML +=
                    `<div class="date">${newDate.getFullYear()}年${(newDate.getMonth() + 1).toString().padStart(2, '0')}月${newDate.getDate().toString().padStart(2, '0')}日（${weekdays[getWeek]}）</div>
                    <div class="look">シフトが入っていません</div>`;
            }
        } else {
            if(selectedElement) {
                selectedElement.innerHTML = '';
            }
            if (selectedElement) {
                // 例) 2024年11月01日（金）のように出力するための処理
                let newDate = new Date(selectedDate); // 最初のデータを使う
                let getWeek = newDate.getDay();
                const weekdays = ['日', '月', '火', '水', '木', '金', '土'];
                
                selectedElement.innerHTML +=
                    `<div class="date">${newDate.getFullYear()}年${(newDate.getMonth() + 1).toString().padStart(2, '0')}月${newDate.getDate().toString().padStart(2, '0')}日（${weekdays[getWeek]}）</div>`;
            }
            // dataが空でない場合、取得したデータをループで回す
            data.forEach(function(circle) {
                // circleが空でないことを確認
                if (Object.keys(circle).length !== 0) {
                    if (selectedElement) {
                        selectedElement.innerHTML += `
                            <div class="look">
                                <span class="time">${circle.start_time_only} - ${circle.end_time_only}</span>
                                <span class="facility">${circle.facility_name}</span>
                            </div>
                        `;
                    }
                }
            });
        }
    }).fail(function(jqXHR, textStatus, errorThrown)  {
        console.error("AJAXリクエストに失敗しました");
        console.error("HTTPステータス:", jqXHR.status); // ステータスコード
        console.error("レスポンス内容:", jqXHR.responseText); // サーバーの返答内容
        console.error("エラーメッセージ:", errorThrown);
    }); 
}
</script>
</body>
</html>