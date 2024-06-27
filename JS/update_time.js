//ログイン画面での時刻表示
document.addEventListener("DOMContentLoaded", function() {
    function updateTime() {
        const now = new Date();
        const year = now.getFullYear();
        const month = now.getMonth() + 1; // 月は0から始まるため、+1します
        const date = now.getDate();
        const hours = now.getHours();
        const minutes = now.getMinutes();
        const seconds = now.getSeconds();

        
        const formattedTime = `${year}年${month}月${date}日 ${hours}時${minutes}分${seconds}秒 `;
        
        document.getElementById('time').textContent = formattedTime;
    }

    setInterval(updateTime, 50);
    updateTime();
});
