<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
        .logo {
            width: 30%;
            /* height: 60px; */
            min-width: 120px;

        }
        .textBox {
            text-align: center;
            margin: 30px auto 150px auto;
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

    </style>

</head>
<body>
    

<div id="div">
    <img src="../img/logo.jpg" class="logo" alt="">
    <div class="textBox">
        <p>Eco Perksは、ゴミ掃除ツアーを通じて京都を美しくするサポートをします。</p>
    </div>
    <img src="../img/mountain.png" class="mountain" alt="">
</div>

<div class="your-class">
    <div>your content</div>
    <div>your content</div>
    <div>your content</div>
  </div>

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