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
            <input type="radio" id="gomi4" name="gomi" value="4" required>
            <label for="gomi4">多い</label>
            <input type="radio" id="gomi3" name="gomi" value="3" required>
            <label for="gomi3">まぁまぁ多い</label>
            <input type="radio" id="gomi2" name="gomi" value="2">
            <label for="gomi2">まぁまぁ少ない</label>
            <input type="radio" id="gomi1" name="gomi" value="1">
            <label for="gomi1">少ない</label>
        </div>
        <br>
        <div class="form-group">
            <select id="areaid" name="areaid">
                <option value="">エリアを選択してください</option>
                <option value="1">エリア1</option>
                <option value="2">エリア2</option>
                <option value="3">エリア3</option>
                <option value="4">エリア4</option>
                <option value="5">エリア5</option>
                <option value="6">エリア6</option>
                <option value="7">エリア7</option>
                <option value="8">エリア8</option>
                <option value="9">エリア9</option>
                <option value="10">エリア10</option>
                <option value="11">エリア11</option>
                <option value="12">エリア12</option>
                <option value="13">エリア13</option>
                <option value="14">エリア14</option>
                <option value="15">エリア15</option>
                <option value="16">エリア16</option>
                <option value="17">エリア17</option>
                <option value="18">エリア18</option>
                <option value="19">エリア19</option>
                <option value="20">エリア20</option>
                <option value="21">エリア21</option>
                <option value="22">エリア22</option>
                <option value="23">エリア23</option>
                <option value="24">エリア24</option>
                <option value="25">エリア25</option>
            </select>
        </div>
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
