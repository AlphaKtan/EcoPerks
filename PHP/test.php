<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/destyle.css@1.0.15/destyle.css"/>
    <link rel="stylesheet" type="text/css" href="../JS/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="../JS/slick/slick-theme.css"/>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Zen+Kaku+Gothic+New:wght@300;400;500;700;900&display=swap');
        body {
            margin: 0;
        }
        img {
            width:100%;
            height:auto;
        }
        #div {
            text-align: center;
            margin-top:160px;
        }
        #div2 {
            text-align: center;
            margin-top:100px;
        }
        .border_span{
            background-color: black;
            display: block;
            height: 2px;
            width: 70px;
            border: 10px;
            margin: 0 auto;
            margin-top: 10px;
        }
        .logo {
            width: 30%;
            min-width: 260px;
            object-fit: cover;
            padding-top: 15px;
            position: relative;
            left: 4%;
        }

        .logo1 {
            width: 30%;
            min-width: 120px;
        }
        .peoplue{
            bottom: 20px;
            width: 10%;
            min-width: 80px;
            object-fit: cover;
            padding-top: 15px;
            position: relative;
            left: 65%;
            display: block;
        }
        .textBox {
            text-align: center;
            margin: 30px auto 150px auto;
            width: calc(100% - 30%);
        }
        .textBox2 {
            text-align: center;
            margin: 30px auto 100px auto;
            width: calc(100% - 30%);
        }
        .textBox3 {
            text-align: center;
            margin: 30px auto 100px auto;
            width: calc(100% - 30%);
        }
        .textBox p{
            font-family: "Zen Kaku Gothic New", sans-serif;
            font-weight: 700;
            font-style: normal;
            font-size: clamp(1rem, 0.965rem + 0.16vw, 1.125rem);
            color: #515151;
            line-height:180%;
        }

        .link_button{
            min-width: 195px;
            padding: 10px;
            background-color: #43AEA9;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-align: center;
            margin-top: 120px;
        }

        .link_button:hover {
            background-color: #557981;
        }

    </style>
</head>
<body>

<div id="div">
    <img src="../img/logo_yoko.svg" class="logo1" alt="">
    <div class="textBox">
        <p>Eco Perksは、ゴミ掃除ツアーを通じて京都を美しくするサポートをします。</p>
    </div>
    <img src="../img/mountain.png" class="mountain" alt="">
</div>


<!-- <div id="div2">
    <div>
        <h1>地図でゴミ掃除スポットを探そう</h1>
        <span class="border_span"></span>
    </div>
    <div class="img_box">
        <img src="../img/map_and_people.png" class="logo">
    </div>
    <div class="textBox2">
        <p>気になる場所をマップからタップすると
        ツアーの詳細が確認できます。</p>
    </div>
    <img src="../img/mountain.png" class="mountain" alt="">
</div> -->

<!-- <div id="div2">
    <div>
        <h1>ツアーを選んで予約しよう！</h1>
        <span class="border_span"></span>
    </div>
    <div class="img_box">
        <img src="../img/map_and_people.png" class="logo">
    </div>
    <div class="textBox2">
        <p>ツアーの詳細画面では、
            開始時間や価格などが確認でき、
            そこから予約することができます。</p>
    </div>
    <img src="../img/mountain.png" class="mountain" alt="">
</div> -->

<!-- <div id="div2">
    <div>
        <h1>さあ、ツアーを始めましょう！</h1>
        <span class="border_span"></span>
    </div>
    <div class="img_box">
    <button class="link_button">アカウントを作成する</button>
        <img src="../img/people.png" class="peoplue">
    </div>
    <div class="textBox2">
        <p>アカウントを作成して、
            ツアーに参加しましょう！</p>
    </div>
    <img src="../img/mountain.png" class="mountain" alt="">
</div> -->

<!-- <div class="your-class">
    <div>your content</div>
    <div>your content</div>
    <div>your content</div>
</div> -->

<script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="../JS/slick/slick.min.js"></script>


<script type="text/javascript">
    $(document).ready(function(){
        $('.your-class').slick({
            setting-name: setting-value
        });
    });
</script>
</body>
</html>