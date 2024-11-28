<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>DataTables</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
<style>

#example_wrapper th {
    text-align: center;
    background-color: lightblue;
}
</style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Bootstrap 5 と DataTables のサンプル</h1>
        <table id="example" class="table table-striped table-hover table-sm" style="width: 100%;">
            <thead>
                <tr>
                    <th><input type="checkbox" id="checkAll">承認</th>
                    <th>名前</th>
                    <th>ポジション</th>
                    <th>オフィス</th>
                    <th>年齢</th>
                    <th>開始日</th>
                    <th>給与</th>
                </tr>
            </thead>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/select/2.0.3/js/dataTables.select.js"></script>
    <script src="https://cdn.datatables.net/select/2.0.3/js/select.dataTables.js"></script>
    <script>
        $(function() {
            var tableHeight = $(window).height() - 300;

            var table = $('#example').DataTable({
                language: {
                    url: './js/ja.json',
                },
                ajax: {
                    "url": "data.json",
                    "type": "POST"
                },
                columns: [
                    {
                        data: "id",
                        render: function(data, type, row, meta) {
                            return '<input type="checkbox" class="row-checkbox" value="' + data + '">';
                        },
                    },
                    { "data": "name" },
                    { "data": "position" },
                    { "data": "office" },
                    { "data": "age" },
                    { "data": "start_date" },
                    { "data": "salary" }
                ],
                paging: false,
                scrollY: tableHeight + 'px',
                scrollCollapse: true,
                layout: {
                    topStart: {
                        buttons: [
                            'copy',
                            {
                                text: 'Add',
                                action: function (e, dt, node, config) {
                                    alert('ボタンがクリックされました');
                                }
                            }
                        ]
                    }
                },
                select: true,
            });

            // 全選択チェックボックス
            $('#checkAll').click(function() {
                var rows = table.rows({ 'search': 'applied' }).nodes();
                $('input[type="checkbox"]', rows).prop('checked', this.checked);
            });

            // 画面がリサイズされた時
            $(window).resize(function() {
                var newTableHeight = $(window).height() - 300;
                $('.dt-scroll-body').css('max-height', newTableHeight + 'px');
            });
        });

    </script>
</body>
</html>