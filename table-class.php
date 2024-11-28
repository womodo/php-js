<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>jQuery Example</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <table id="exampleTable" border="1">
        <tr>
            <td class="highlight">1-1</td>
            <td>1-2</td>
        </tr>
        <tr>
            <td>2-1</td>
            <td class="highlight">2-2</td>
        </tr>
        <tr>
            <td>3-1</td>
            <td>3-2</td>
        </tr>
        <tr>
            <td class="highlight">4-1</td>
            <td class="highlight">4-2</td>
        </tr>
    </table>

    <script>
        $(document).ready(function() {
            $('#exampleTable tr').each(function() {
                if ($(this).find('td.highlight').length > 0) {
                    // この行には 'highlight' クラスの td が存在する
                    $(this).css('background-color', 'yellow'); // 行に背景色を設定するなどの処理をここに記述
                    var specificTdContent = $(this).find('td').eq(1).text(); // 2番目のtdの内容を取得
                    console.log(specificTdContent); // 特定のtdの内容をコンソールに出力
                }
            });

            // $('#exampleTable td.highlight').each(function() {
            //     console.log($(this));
            //     $(this).closest('tr').css('background-color','yellow');
            // });
        });
    </script>
</body>
</html>
