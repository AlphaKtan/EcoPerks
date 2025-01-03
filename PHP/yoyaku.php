<?php 
session_start();

if (isset($_GET['location'])) {
    $location = $_GET['location'];
    $_SESSION['location'] = $location;
}

$URL = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$_SESSION['URL'] = $URL;
if (!isset($_SESSION['user_id'])) {
    $_SESSION['login_message'] = "ログインしてください。"; // メッセージをセッションに保存
    header('Location: message.php');
    exit;
}
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
}

require '../Model/dbModel.php';

// DB接続
$pdo = dbConnect();

if ($_SESSION['location']) {
    $facilitySql = "SELECT facility_name FROM travel_data WHERE id = :facility";
    $facilityStmt = $pdo->prepare($facilitySql);
    $facilityStmt->bindParam(':facility', $location, PDO::PARAM_STR);
    $facilityStmt->execute();
    $facilityRow = $facilityStmt->fetch(PDO::FETCH_ASSOC);
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

//デバッグ用の出力
// echo "<pre>";
// print_r($row[0]);    
// echo "</pre>";
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>予約画面</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../CSS/shiftStyle.css">
    <style>
        label {
            display: block;
        }

        .facility_span {
            padding: 10px;
            background-color: #43AEA9;
            color: #fff;
            border: none;
            border-radius: 5px;
            margin-left: auto;
            font-size: 50px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
    <?php if(isset($facilityRow['facility_name'])){echo "<span class='facility_span'>".$facilityRow['facility_name']."</span>";} ?>
        <h3 class="mb-4">
            <a href="?ym=<?= $prev ?>&location=<?= $location ?>">&lt;</a>
            <span class="mx-3">
                <?= $html_title ?>
            </span>
            <a href="?ym=<?= $next ?>&location=<?= $location ?>">&gt;</a>
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
    
    <div class="shift_look"></div>
<!-- JavaScript -->
<script type="text/javascript">
    let ym = "<?=$ym; ?>";  // 無理やりPHPの$ymをJavaScriptの変数ymに代入
    let day_count = "<?=$day_count; ?>";
    let locationId = "<?= $_SESSION['location']; ?>";
</script>
<script src="../Js/jquery-3.7.1.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/jquery-3.5.1.min.js"></script>
<script src="../js/moment.min.js"></script>
<script src="../js/ja.js"></script>
<script src="../js/bootstrap-datetimepicker.min.js"></script>
<script src="../Js/custom.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>

document.addEventListener("DOMContentLoaded", function() {
    fetchShiftDates();
});


// 選ばれた日付マスに色を付ける処理
let previouslySelected = null;
let selectedDate = null;

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
    fetchShiftData();
}

function fetchShiftDates() {
    $.ajax({
        type: "POST",
        url: "../PHP/yoyaku_shift.php",
        dataType: "json",
        data: { facility: locationId, ym: ym, day_count: day_count }
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

function fetchShiftData() {
    let selectedElement = document.querySelector('.shift_look');
    let i = 1;

    // 空にする
    if(selectedElement) {
        selectedElement.innerHTML = '';
    }
    $.ajax({
        type: "POST",
        url: "./yoyaku_fetch.php",
        dataType: "json",
        data: { reservation_date: selectedDate, facility: locationId }
    }).done(function(data) {
        console.log(data);
        
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
                    <div class="look">予定が入っていません</div>`;
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
                
                selectedElement.textContent +=
                    `&lt;div class="date"&gt;${newDate.getFullYear()}年${(newDate.getMonth() + 1).toString().padStart(2, '0')}月${newDate.getDate().toString().padStart(2, '0')}日（${weekdays[getWeek]}）&lt;/div&gt;`;
                    console.log(selectedElement.textContent);
                    selectedElement.innerHTML = selectedElement.textContent;
            }
            selectedElement.textContent +=`<form action="yoyaku_insert.php" method="post">
                <input type="hidden" name="date" value="${selectedDate}">`;
            // dataが空でない場合、取得したデータをループで回す
            data.forEach(function(circle) {
                // circleが空でないことを確認
                if (Object.keys(circle).length !== 0) {
                    let data = {
                        start_time: circle.start_time_only + ':00',
                        end_time: circle.end_time_only + ':00',
                    };
                    if (selectedElement) {
                        selectedElement.textContent += `
                            <label for="facility${i}">    
                                <div class="look">
                                    <input type="radio" name="facility" id="facility${i}" value='${JSON.stringify(data)}'>
                                    <span class="time">${circle.start_time_only} - ${circle.end_time_only}</span>
                                    <span class="facility">${circle.facility_name}</span>
                                </div>
                            </label>
                        `;
                        i++;
                    }
                }
            });
            if (selectedElement) {
                selectedElement.textContent += 
                    `<input type="submit"></form></div>`;
                selectedElement.innerHTML = selectedElement.textContent;
            }
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