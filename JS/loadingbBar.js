$(function() {
    var loadCount = 0, //loading状況の初期化
        imgLength = $("img").size(); //読み込む画像の数を取得
    $("img").each(function() {
        var src = $(this).attr("src");
        $("<img>")
            .attr("src", src)
            .load(function() {
                loadCount++; //画像が読み込まれたら、loading状況を更新
            });
    });
 
    var timer = setInterval(function() { //一定間隔でloading状況をローディングバーに反映
        $(".loadingBar").css({
            "width": (loadCount / imgLength) * 100 + "%" //読み込まれた画像の数を画像全体で割り、%としてローディングバーのwidthに設定
        });
        if((loadCount / imgLength) * 100 == 100){ //100%読み込まれたらローディングバーを隠す
            clearInterval(timer);
            $(".loadingBar").delay(200).animate({
                "opacity": 0
            }, 200);
        }
    }, 5);
});

$("<img>")
    .attr("src", src)
    .load(function() {
    loadCount++; //画像が読み込まれたら、loading状況を更新
});

var timer = setInterval(function() {
    $(".loadingBar").css({
        "width": (loadCount / imgLength) * 100 + "%"
    });
    if((loadCount / imgLength) * 100 == 100){
        clearInterval(timer);
        $(".loadingBar").delay(200).animate({
            "opacity": 0
        }, 200);
    }
}, 5);