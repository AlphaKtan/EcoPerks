<!doctype html>
<html>
    <!-- test.php -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Map with Street View Toggle</title>
    <style>
        .look {
            padding: 5px;
            border-left: 3px solid green;
            border-top: 1px solid #f5f5f5;
            border-bottom: 1px solid #f5f5f5;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
<script src="Js/jquery-3.7.1.min.js"></script>
<input type="text" id="search">
<div class="results"></div>

<script>
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
            <div class="result">
                <div class="facility">
                    ${results.facility_name} (${results.romaji})
                </div>
            </div>
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

    // search.oninput = searchFacilities;
    // inputイベントを使って検索処理を実行
    search.addEventListener('keydown', function(event) {
        if (event.key !== 'Enter') {
            searchFacilities();
        }
    });

</script>






