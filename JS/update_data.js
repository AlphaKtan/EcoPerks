document.addEventListener('DOMContentLoaded', function () {
    function fetchData() {
        const searchUsername = encodeURIComponent(document.getElementById('searchUsername').value);
        
        fetch('fetch_data.php', { // fetch_data.php のパスを確認
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                'searchUsername': searchUsername
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(data => {
            // 'data-table' の ID を持つテーブルにデータを挿入
            const tableBody = document.querySelector('#data-table tbody');
            if (tableBody) {
                tableBody.innerHTML = data;
            } else {
                console.error("Element with ID 'data-table' not found");
            }
        })
        .catch(error => console.error('Error fetching data:', error));
    }

    // 初回データ取得
    fetchData();
    // 更新間隔ms
    setInterval(fetchData, 100);

    // フォームのサブミットを防ぐ
    document.querySelector('form').addEventListener('submit', function (event) {
        event.preventDefault();
        fetchData();
    });
});



