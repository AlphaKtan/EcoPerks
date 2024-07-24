$(".openbtn").click(function () {
    $(this).toggleClass('active'); // ボタン自身に active クラスを付与
    $("#g-nav").toggleClass('panelactive');

    if ($("#g-nav").hasClass('panelactive')) {
        $("#g-nav").css('height', '100vh'); // panelactive クラスがある場合に高さを設定
    } else {
        $("#g-nav").css('height', ''); // panelactive クラスがない場合に高さをリセット
    }
});

$("#g-nav a").click(function () {
    $(".openbtn").removeClass('active'); // ボタンの active クラスを除去
    $("#g-nav").removeClass('panelactive'); // ナビゲーションの panelactive クラスも除去
    $("#g-nav").css('height', ''); // ナビゲーションの高さをリセット
});
