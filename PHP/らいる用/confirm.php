<?php 
if(isset($_POST['submit'])){

  // POSTされたデータをエスケープ処理して変数に格納
  $fullname = htmlentities($_POST['fullname']);
  $mail  = htmlentities($_POST['mail']);
  $gender = htmlentities($_POST['gender']);
  $title = htmlentities($_POST['title']);
  $body = htmlentities($_POST['body']);
}
else {
  header("Location:./index.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>フォーム確認ページ</title>
</head>
<body>
  <h1>フォーム確認ページ</h1>
  <div>
    お名前：<?php echo($fullname); ?>
  </div>
  <div>
    メールアドレス：<?php echo($mail); ?>
  </div>
  <div>
    性別：<?php echo($gender); ?>
  </div>
  <div>
    ご用件：<?php echo($title); ?>
  </div>
  <div>
   お問い合わせ内容：
  </div>
  <div><?php echo(nl2br($body)); ?></div>
</body>
</html>