// 好きなワードを入れる
let pass = '79';

let inputText = ''; // 入力されたテキストを保持する変数

// body全体にキー入力を検出するリスナーを設定
document.body.addEventListener('keydown', function(event) {
    // Enterキーが押されたかどうかを確認
    if (event.key === 'Enter') {
        event.preventDefault(); // Enterキーのデフォルト動作を防止
        console.log(inputText);
        // 入力されたテキストが "[start]" ならリダイレクト
        if (inputText === pass) {
            window.location.href = './start_user.php'; // リダイレクト先のURL
        }

        // 入力をリセット
        inputText = '';
    } else if (event.key.length === 1) {
        // アルファベットや数字などの1文字のキーが押された場合のみ追加
        inputText += event.key;
        
    }
});