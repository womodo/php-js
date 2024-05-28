<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Ajax Example</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>AjaxでShift_JISのJSONを取得</h1>
    <button id="get-data">データ取得</button>
    <pre id="result"></pre>

    <script>
    $(document).ready(function(){
        $('#get-data').on('click', function(){
            $.ajax({
                url: 'your_php_script.php',
                dataType: 'json',
                beforeSend: function(xhr){
                    xhr.overrideMimeType("application/json;charset=utf-8");
                },
                success: function(data) {
                    console.log(data);

                    console.log(data["message"]);
                    console.log(data["status"]);
                }
            });
        });
    });
    </script>
</body>
</html>
