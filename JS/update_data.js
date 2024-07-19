document.addEventListener('DOMContentLoaded', function () {
    function fetchData() {
        const searchUsername = encodeURIComponent(document.getElementById('searchUsername').value);
        
        fetch('../php/fetch_data.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                'searchUsername': searchUsername
            })
        })
        .then(response => response.text())
        .then(data => {
            document.getElementById('data-table').innerHTML = data;
        })
        .catch(error => console.error('Error fetching data:', error));
    }

    // 初回データ取得
    fetchData();
    //更新時間ms
    setInterval(fetchData, 100);

    // フォームのサブミットを防ぐ
    document.querySelector('form').addEventListener('submit', function (event) {
        event.preventDefault();
        fetchData();
    });
});

