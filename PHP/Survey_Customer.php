<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/formStyle.css">
    <title>アンケートフォーム</title>
</head>
<body>

<header>
    <div class="flexBox">
        <div class="menu"></div>
        <div class="logo">
            <img src="../img/logo.jpg" alt="" class="logo2">
        </div>
        <div class="icon"></div>
    </div>
</header>

<section class="login_form">
    <h1>アンケートフォーム</h1>
    <form action="Survey_Customer_upload.php" method="post" enctype="multipart/form-data">
        <div class="form-group"> 
            <label for="gomi">ゴミの量</label><br>
            <input type="radio" id="gomi1" name="gomi" value="1" required>
            <label for="gomi1">多い</label>
            <input type="radio" id="gomi2" name="gomi" value="2" required>
            <label for="gomi2">まぁまぁ</label>
            <input type="radio" id="gomi3" name="gomi" value="3">
            <label for="gomi3">少ない</label>
        </div>
        <br>
        <div class="form-group">
            <label for="body">お問い合わせ内容</label><br>
            <textarea id="body" name="body" rows="5" cols="50" required></textarea>
        </div>
        <div class="form-group">
            <label for="image">アップロード画像（任意）
            </label>
            <input type="file" id="image" name="image">
        </div>
        <br>
        <button type="submit" name="submit">確認</button>
    </form>
</section>

</body>
</html>
