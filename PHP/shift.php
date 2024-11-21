<?php
// 今はデータベースの登録をtest_time_changeにしている
require '../Model/dbModel.php';

// DB接続
$pdo = dbConnect();

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
$today = date('Y-m-j');

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
        $week .= "<td class='today' onclick='selectDate(\"$date\")'>" . $day . "<div class='circle' style='display:none;'></div>";
    } else {
        $week .= "<td onclick='selectDate(\"$date\")'>" . $day . "<div class='circle' style='display:none;'></div>";
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
$sql = "SELECT id, start_time, end_time FROM preset";
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
    <title>シフト登録画面</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../CSS/shiftStyle.css">
</head>
<body>
    <div class="container mt-5">
        <h3 class="mb-4">
            <a href="?ym=<?= $prev ?>">&lt;</a>
            <span class="mx-3">
                <?= $html_title ?>
            </span>
            <a href="?ym=<?= $next ?>">&gt;</a>

            <form action="" method="post">
                <select name="area" id="area">
                    <option hidden>選択してください</option>
                    <?php for($i=1;$i<=25;$i++){ ?>
                        <option value="<?php echo $i ?>">エリア<?php echo $i ?></option>
                    <?php }?>
                </select>
                <br>
                <select name="facility" id="facility">
                    <option hidden>選択してください</option>
                    <option disabled="disabled" id="notApplicable">該当なし</option>
                </select>
            </form>
        </h3>
        <table class="table table-bordered">
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

    <div class="shiftDiv" id="shiftDiv">
        <p style="color: white;">プリセットから追加</p>
    <div class="presetDiv">
        <form action="" method="post">
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

        <button>その他時間追加</button>
        <button onclick='entryFunction()'>シフト追加</button>
    </div>


<!-- JavaScript -->
<script src="../Js/jquery-3.7.1.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/jquery-3.5.1.min.js"></script>
<script src="../js/moment.min.js"></script>
<script src="../js/ja.js"></script>
<script src="../js/bootstrap-datetimepicker.min.js"></script>
<script src="../Js/custom.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>


<script>
    var optval;
    let flag = 0;
    let area_id;
    $(function(){
        // よく使う要素を変数へ格納する
        var area = document.getElementById("area");
        var facility = document.getElementById("facility");
        var notApplicable = document.getElementById("notApplicable");
        
        // エリア情報切り替え
        $('#area').on("change",function(){
            // 選ばれたエリアを保存
            area_id = area.value;

            // 施設情報をクリアする
            selectDataClearOnly(facility);
            
            // 施設のデータを取得する
            getAreaData(area.value);
            // 施設が選択されるたびに関数起動
            $('#facility').on("change",function(){
                // フラグが1なら施設が選択されている状態
                flag = 1;
                // 施設IDを保存
                facility_id = facility.value;

                circleFunction();
            })

            // エリアが選ばれていたら「該当なし」を消す処理
            notApplicable.style.display ='none';

            // 施設が選択されたらerrorを消す
            if (document.querySelector('.error')) {
                document.querySelector('.error').style.display = 'none';
            }

        })
    });

// 選ばれた日付マスに色を付ける処理
let previouslySelected = null;
let selectedDate = null;
let shiftDiv = document.getElementsByClassName('shiftDiv');

function selectDate(date) {
    // 前に選択されていたセルの選択を解除
    if (previouslySelected) {
        previouslySelected.classList.remove('selected');
    }

    // 新しく選択されたセルにselectedクラスを追加
    let element = event.target;
    element.classList.add('selected');
    previouslySelected = element;

    // 選択された日付を保持
    selectedDate = date;
    // 日付を選択されたらshiftDivが出てくる関数
    onShiftDiv();
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
// データベースに登録する処理
function entryFunction() {
    if (flag === 1) {       // 施設が選択されている時
            console.log(selectedDate);
            
            // チェックされたチェックボックスの値を取得
            let selectedPresets = [];
            $('input[name="preset"]:checked').each(function() {
                selectedPresets.push($(this).val());
            });
            // エリア情報があれば情報取得する
            $.ajax({
            type: "POST",
            url: "../PHP/shift_entry.php",
            dataType: "json",
            data: { presets: selectedPresets, date: selectedDate, area: area_id, facility: facility_id }
        }).done(function(responseData) {
            console.log("レスポンスデータ:", responseData);
            if (Array.isArray(responseData)) {
                responseData.forEach(data => {
                    console.log(data);
                    if (data === "正常に完了") {
                        amazingSample(data);    
                    } else {
                        oopsSwalSample(data);
                    }
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

function circleFunction() {

    $.ajax({
        type: "POST",
        url: "../PHP/shift_circle.php",
        dataType: "json",
        data: { reservation_date: selectedDate, facility: facility_id }
    }).done(function(data) {
        
        // 取得したデータをループで回す
        data.forEach(function(circle){
            var circle = document.getElementById("circle");

            if (circle.length !== 0) {
                // 選択された日付 + 施設のシフトが1つでも登録されているとき
                console.log(circle);
                circle.style.display = "";
            } else {
                console.log("失敗");
                circle.style.display = "none";
            }
        });  
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