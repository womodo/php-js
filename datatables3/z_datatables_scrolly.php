<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>DataTables ScrollY Example</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">
    <style>
        .dt-scroll-body {
            overflow-y: scroll !important;
        }
    </style>
</head>
<body>
    <table id="example" class="display">
        <thead>
            <tr>
                <th>名前</th>
                <th>年齢</th>
                <th>職業</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>山田 太郎</td>
                <td>30</td>
                <td>エンジニア</td>
            </tr>
            <tr>
                <td>鈴木 花子</td>
                <td>25</td>
                <td>デザイナー</td>
            </tr>
            <!-- 行を追加 -->
        </tbody>
    </table>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                scrollY: '200px', // 縦スクロールを有効にし、スクロール領域の高さを設定
                scrollCollapse: true, // テーブルの高さがデータの高さに応じて調整される
                paging: false // ページングを無効にする
            });
        });
    </script>
</body>
</html>
