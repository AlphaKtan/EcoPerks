<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>フォームテスト</title>
</head>
<body>
  <h1>フォームテスト</h1>
  <form method="POST" action="./confirm.php">
    <div>
      <div><label for="fullname">お名前</label></div>
      <div><input id="fullname" type="text" name="fullname"></div>
    </div>
    <div>
      <div><label for="mail">メールアドレス</label></div>
      <div><input id="mail" type="mail" name="mail"></div>
    </div>
    <div>
      <div><span>性別</span></div>
      <div><input id="male" name="gender" type="radio" value="男性" checked><label for="male">男性</label>
        <input id="female" name="gender" type="radio" value="女性"><label for="female">女性</label></div>
      </div>
      <div>
        <div><label for="title">ご用件</label></div>
        <div>
            <select id="title" name="title">
              <option value="お問合せ">お問合せ</option>
              <option value="お見積">お見積</option>
              <option value="その他">その他</option>
            </select>
        </div>
      </div>
      <div>
        <div><label for="body">お問い合わせ内容</label></div>
        <div><textarea id="body" name="body" id="body" cols="30" rows="10"></textarea></div>
      </div>
      <div>
        <button type="submit" name="submit">確認</button>
      </div>
  </form>
</body>
</html>