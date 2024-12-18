<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        #button1 {
            font-size: 24px;
            font-weight: bold;
        }

        .valueId {
            color: red;
        }

        .no {
            color: blue; /* ボタンが青くなる */
        }
    </style>
</head>
<body>
    <div id="button-container">
        <button id="button1" onclick="getfunc()">ボタン</button>
    </div>
    <script>
        function getfunc() {
            let getid = document.getElementById("button1");
            let valueIDElements = document.getElementsByClassName("valueId");

            // 現在の"valueId"クラスを全て削除し、"no"クラスを追加
            for (let i = 0; i < valueIDElements.length; i++) {
                valueIDElements[i].classList.remove('valueId');
            }

            // ボタンに"valueId"クラスを追加
            getid.classList.add('valueId');
        }
    </script>
</body>
</html>