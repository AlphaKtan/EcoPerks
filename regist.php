<?php 
$first_name_frigana_value = "";
$last_name_frigana_value = "";
$phonenumber_value = "";
$email_value = "";
$userName_value = "";

if ($_POST["first_name_furigana"]){
    $first_name_frigana_value = $_POST["first_name_furigana"];
}
if ($_POST["last_name_furigana"]){
    $last_name_frigana_value = $_POST["last_name_furigana"];
}
if ($_POST["phonenumber"]){
   $phonenumber_value = $_POST["phonenumber"];
}
if ($_POST["email"]){
   $email_value = $_POST["email"];
}
if ($_POST["userName"]){
    $userName_value = $_POST["userName"];
}
?>
<!-- 顧客登録です -->
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/formStyle.css">
    <title>アカウント登録</title>
</head>
<body>
    <header>
        <div class="flexBox">
            <div class="menu">
                <button class="menu_button" type="button">
                    管理者?
                </button>
            </div>
            <div class="logo">
                <img src="img/logo.jpg" alt="" class="logo2">
            </div>
            <div class="icon"></div>
        </div>
    </header>

    <section class="login_form">
        <h1>ご登録が完了した場合ログインページへ移動します。</h1>
        <form action="php/form.php" method="post" autocomplete="off">
            <div class="form-group">
                <label for="first_name_furigana">姓フリガナ Firstname Japan Furigana</label>
                <input type="text" id="first_name_furigana" name="first_name_furigana" required  value="<?php echo $first_name_frigana_value;?>">
            </div>
            <div class="form-group">
                <label for="last_name_furigana">名フリガナ Lastname Japan Furigana</label>
                <input type="text" id="last_name_furigana" name="last_name_furigana" required  value="<?php echo $last_name_frigana_value;?>">
            </div>
            <div class="form-group">
                <label for="phonenumber">電話番号 Phonenumber</label>
                <input type="tel" id="phonenumber" name="phonenumber" required  value="<?php echo $phonenumber_value;?>">
            </div>
            <div class="form-group">
                <label for="email">メールアドレス Mail</label>
                <input type="email" name="email"required value="<?php echo $email_value;?>">
            </div>
            <div class="form-group">
                <label for="userName">ユーザーネーム Username</label>
                <input type="text" id="userName" name="userName" required value="<?php echo $userName_value;?>">
            </div>
            <div class="form-group">
                <label for="password">パスワード Password<p>(※大文字、小文字数字を含めた8文字以上)</p></label>
                <input type="password" id="password" name="password" required autocomplete="new-password">
            </div>
            <div class="form-group">
                <label for="confirmPassword">再パスワード rePassword</label>
                <input type="password" id="confirmPassword" name="confirmPassword" required autocomplete="new-password">
            </div>
            <button type="submit">登録 Click</button>
        </form>
    </section>
</body>
</html>

