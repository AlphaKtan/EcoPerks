<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/destyle.css@1.0.15/destyle.css" />
    <link rel="stylesheet" type="text/css" href="../JS/slick-1.8.1/slick/slick.css" />
    <link rel="stylesheet" type="text/css" href="../JS/slick-1.8.1/slick/slick-theme.css" />
    <style>
      @import url("https://fonts.googleapis.com/css2?family=Zen+Kaku+Gothic+New:wght@300;400;500;700;900&display=swap");
      html {
        overflow-x: hidden;
      }
      body {
        margin: 0;
        font-family: "Zen Kaku Gothic New", sans-serif;
      }
      img {
        width: 100%;
        height: auto;
      }
      .start-slide {
        margin: 10% auto; /* 親要素に対して上下左右中央配置 */
        height: 85svh;
      }
      .div,
      .div2,
      .div3,
      .div4 {
        text-align: center;
        display: flex;
        flex-direction: column;
        position: relative;
      }
      .slick-track {
        display: flex;
      }
      .slick-slide {
        height: auto !important;
      }

      .border_span {
        background-color: #557981;
        display: block;
        height: 2px;
        width: 70px;
        border: 10px;
        margin: 10px auto 20px auto;
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
        width: min(max(130px, 10vw), 150px);
        margin: 0 auto; /* 中心配置を追加 */
      }
      .start-title {
        color: #515151;
        text-align: center;
      }
      .start-title h1 {
        font-weight: 700;
        font-style: normal;
        font-size: clamp(1.125rem, 1.02rem + 0.48vw, 1.5rem);
      }
      .people001,
      .people002 {
        width: min(max(80px, 10vw), 150px);
      }
      .people001 {
        bottom: -5%;
        object-fit: cover;
        position: absolute;
        right: 0%;
        display: block;
      }
      .people002 {
        bottom: auto;
        object-fit: cover;
        top: -5%;
        position: absolute;
        right: 0%;
        display: block;
      }
      .textBox001 {
        text-align: center;
        margin: 50px auto 150px auto;
        width: calc(100% - 30%);
        max-width: 500px;
      }
      .textBox002 {
        text-align: center;
        margin: 20px auto 50px auto;
        width: calc(100% - 20%);
        max-width: 500px;
      }

      .textBox001 p,
      .textBox002 p {
        font-family: "Zen Kaku Gothic New", sans-serif;
        font-weight: 500;
        font-style: normal;
        font-size: clamp(1rem, 0.965rem + 0.16vw, 1.125rem);
        color: #515151;
        line-height: 180%;
      }
      .link_button {
        max-width: 225px;
        width: 195px;
        height: 40px;
        padding: 10px;
        background-color: #43aea9;
        border: 2px solid #fff;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        text-align: center;
        box-shadow: 0px 2px 2px 0px rgba(0, 0, 0, 0.25);
        font-weight: 600;
      }
      .img_box {
        position: relative;
        max-width: 300px;
        width: calc(100% - 20%);
        margin: auto;
      }
      .button_box {
        position: relative;
        max-width: 350px;
        width: calc(100% - 5%);
        margin: 100px auto 100px auto;
      }
      .img_box .map-circle {
        display: flex; /* フレックスボックスを使用 */
        justify-content: center; /* 水平方向の中央配置 */
        align-items: center; /* 垂直方向の中央配置 */
        height: auto;
        width: 100%;
      }
      .img_box .map-circle img {
        border-radius: 100%;
        object-fit: cover;
        height: auto;
        width: 80%;
      }
      .mountain {
        width: 100%;
      }
      .link_button:hover {
        background-color: #557981;
      }
      .start-slide-top.--one {
        margin-top: 0px;
      }
      .start-slide-center {
        height: 60svh;
      }
      .start-slide-bottom {
        position: absolute;
        bottom: 0;
      }
      .start-slide .slick-dots {
        justify-content: center;
        bottom: auto;
      }
      .slick-dots li button:before {
        color: #515151;
      }
      .start-slide .slick-dots li.slick-active button:before {
        opacity: 0.75;
        color: #557981;
      }
      .login-bottom-bar {
        background-color: #515151;
        position: fixed;
        bottom: 0;
        width: 100%;
        text-align: center;
        padding: 15px 0;
        color: #fff;
      }
      .login-bottom-bar p {
        font-size: 0.875rem;
      }
      .login-bottom-bar span {
        border-bottom: 0.25px solid #fff;
        font-weight: 500;
      }
      @media screen and (min-width: 1024px) {
        .start-slide {
          margin: 5% auto 0 auto; /* 親要素に対して上下左右中央配置 */
          height: 80vh;
        }
        .div,
        .div2,
        .div3,
        .div4 {
          width: 30%;
          margin: 0 2%; /* 各要素の左右マージンを調整 */
          display: inline-block; /* インラインブロックに変更 */
          text-align: left; /* テキストの配置を左揃えに変更 */
          flex-direction: row; /* flex-directionをrowに変更 */
        }
        .start-slide-top {
          width: 100%;
        }
        .start-slide-center {
          width: 100%;
          height: 50vh;
          display: flex;
          align-items: center;
        }
        .start-slide-bottom {
          width: 100%;
          position: relative;
          bottom: 0;
        }
        .textBox001 {
          width: 100%;
          padding: 0 20px;
          margin: 0 auto 0 auto;
        }
        .textBox002 {
          width: 50%;
          padding: 0 20px;
          margin: 0 0 0 auto;
        }
        .textBox002 p {
          text-align: left;
        }
        .img_box {
          width: 50%;
          max-width: 400px;
          margin-right: 20px;
          display: flex;
          align-items: center;
        }
        .map-circle {
          width: 150px;
          height: 150px;
          margin-right: 20px;
        }
        .map-circle img {
          width: 100%;
          height: 100%;
          object-fit: cover;
        }
        .logo1 {
          width: 200px;
          margin-bottom: 20px;
        }
        .mountain {
          width: 100%;
          height: auto;
        }
        .button_box {
          max-width: 325px;
          width: calc(100% - 5%);
          margin: 100px auto;
        }
        .link_button {
          width: 100%;
          padding: 15px;
          height: 50px;
        }
        .border_span {
          background-color: #557981;
          display: block;
          height: 2px;
          width: 70px;
          border: 20px;
          margin: 15px auto 35px auto;
        }
      }
    </style>
  </head>
  <body>
    <div class="start-slide">
      <div class="div">
        <div class="start-slide-top --one">
          <img src="../img/logo_yoko.svg" class="logo1" alt="" />
        </div>
        <div class="start-slide-center">
          <div class="textBox001">
            <p>Eco Perksは、<br />ゴミ掃除ツアーを通じて<br />京都を美しくする<br />サポートをします。</p>
          </div>
        </div>
        <div class="start-slide-bottom">
          <img src="../img/mountain.png" class="mountain" alt="" />
        </div>
      </div>

      <div class="div2">
        <div class="start-slide-top">
          <div class="start-title">
            <h1>地図でゴミ掃除スポットを探そう</h1>
            <span class="border_span"></span>
          </div>
        </div>
        <div class="start-slide-center">
          <div class="img_box">
            <div class="map-circle">
              <img src="../img/map.png" />
            </div>
            <img src="../img/people.png" class="people001" alt="人のイラスト" />
          </div>
          <div class="textBox002">
            <p>気になる場所をマップからタップすると、<br />ツアーの詳細が確認できます。</p>
          </div>
        </div>
        <div class="start-slide-bottom">
          <img src="../img/mountain.png" class="mountain" alt="山岳風景のイラスト" />
        </div>
      </div>

      <div class="div3">
        <div class="start-slide-top">
          <div class="start-title">
            <h1>ツアーを選んで予約しよう！</h1>
            <span class="border_span"></span>
          </div>
        </div>
        <div class="start-slide-center">
          <div class="img_box">
            <div class="map-circle">
              <img src="../img/map.png" />
            </div>
            <img src="../img/people.png" class="people001" alt="人のイラスト" />
          </div>
          <div class="textBox002">
            <p>ツアーの詳細画面では、<br />開始時間や価格などが確認でき、<br />そこから予約することができます。</p>
          </div>
        </div>
        <div class="start-slide-bottom">
          <img src="../img/mountain.png" class="mountain" alt="山岳風景のイラスト" />
        </div>
      </div>

      <div class="div4">
        <div class="start-slide-top">
          <div class="start-title">
            <h1>さあ、ツアーを始めましょう！</h1>
            <span class="border_span"></span>
          </div>
        </div>
        <div class="start-slide-center">
          <div class="button_box">
            <button class="link_button">アカウントを作成する</button>
            <img src="../img/people.png" class="people002" alt="人々のイラスト" />
          </div>
          <div class="textBox002">
            <p>アカウントを作成して、ツアーに参加しましょう！</p>
          </div>
        </div>
        <div class="start-slide-bottom">
          <img src="../img/mountain.png" class="mountain" alt="山岳風景のイラスト" />
        </div>
      </div>
    </div>

    <div class="login-bottom-bar">
      <p>
        アカウントをお持ちの方は<span><a href="#">ログイン</a></span>
      </p>
    </div>
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript" src="../JS/slick-1.8.1/slick/slick.min.js"></script>

    <script type="text/javascript">
      $(document).ready(function () {
        $(".start-slide").slick({
          dots: true,
          arrows: false,
          infinite: true,
          speed: 300,
          slidesToShow: 1,
          adaptiveHeight: true,
        });
      });
    </script>
  </body>
</html>
