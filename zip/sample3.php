<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <title>DataTables with Checkboxes</title>
</head>
<body>
    <table id="example" class="display" style="width: 100%;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Item</th>
                <th>Option 1</th>
                <th>Option 2</th>
                <th>Option 3</th>
                <th>Actions</th>
            </tr>
        </thead>
    </table>

    <script>
        $(document).ready(function() {
            var table = $('#example').DataTable({
                "ajax": "fetch_data.php",
                "columns": [
                    { "data": "id" },
                    { "data": "item" },
                    { "data": "opt1", "render": function(data, type, row) {
                        return '<input type="checkbox" class="opt1" data-id="' + row.id + '"' + (data == 1 ? ' checked' : '') + '>';
                    }},
                    { "data": "opt2", "render": function(data, type, row) {
                        return '<input type="checkbox" class="opt2" data-id="' + row.id + '"' + (data == 1 ? ' checked' : '') + '>';
                    }},
                    { "data": "opt3", "render": function(data, type, row) {
                        return '<input type="checkbox" class="opt3" data-id="' + row.id + '"' + (data == 1 ? ' checked' : '') + '>';
                    }},
                    { "data": null, "render": function(data, type, row) {
                        return '<button class="update" data-id="' + row.id + '">変更</button>';
                    }}
                ]
            });

            // 変更ボタンのクリックイベント
            $('#example tbody').on('click', '.update', function() {
                var id = $(this).data('id');
                var opt1 = $('.opt1[data-id="' + id + '"]').is(':checked') ? 1 : 0;
                var opt2 = $('.opt2[data-id="' + id + '"]').is(':checked') ? 1 : 0;
                var opt3 = $('.opt3[data-id="' + id + '"]').is(':checked') ? 1 : 0;
                var opt4 = $('.opt4[data-id="' + id + '"]').is(':checked') ? 1 : 0;

                // AjaxでPHPにデータを送信
                $.ajax({
                    url: 'update.php',
                    type: 'POST',
                    data: {
                        id: id,
                        opt1: opt1,
                        opt2: opt2,
                        opt3: opt3
                    },
                    success: function(response) {
                        alert('更新が成功しました！');
                        table.ajax.reload();
                    },
                    error: function() {
                        alert('エラーが発生しました');
                    }
                });
            });
        });
    </script>
</body>
</html>