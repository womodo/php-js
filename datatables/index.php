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
#example_wrapper td {
    text-align: center;
}

.highlight td {
    background-color: yellow !important;
}


table.table.dataTable.table-striped > tbody > tr:nth-of-type(2n) > * {
    box-shadow: none !important;
    color: var(--bs-table-color-state,var(--bs-table-color-type,var(--bs-table-color))) !important;
}
table.table.dataTable.table-striped > tbody > tr:nth-of-type(2n+1).selected > * {
    box-shadow: inset 0 0 0 9999px rgba(var(--dt-row-stripe), 0.05) !important;
    color: var(--bs-table-color-state,var(--bs-table-color-type,var(--bs-table-color))) !important;
}
table.table.dataTable > tbody > tr.selected a {
    color: rgba(var(--bs-link-color-rgb), var(--bs-link-opacity, 1)) !important;
}
</style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Bootstrap 5 と DataTables のサンプル</h1>
        <table id="example" class="table table-striped table-bordered table-hover table-sm" style="width: 100%;">
            <thead>
                <tr>
                    <th>承認　<input type="checkbox" id="select-all"></th>
                    <!-- <th></th> -->
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

            var table = new DataTable('#example', {
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/2.0.8/i18n/ja.json',  // 日本語化
                },
                layout: {
                    topStart: {
                        // トップ部分にボタン表示
                        buttons: [
                            'copy',
                            {
                                text: 'GET',
                                action: get_button,
                                // action: function (e, dt, node, config) {
                                //     alert('ボタンがクリックされました');
                                // }
                            },
                            'showSelected'
                        ],
                    },
                    bottomStart: {
                        info: {
                            empty: '',
                            text: ' 全 _TOTAL_ 件 ',
                        },
                    },
                },
                language: {
                    select: {
                        rows: {
                            _: ' %d 件選択',
                            0: '',
                        }
                    }
                },

                ordering: true,       // 並び替え機能
                searching: true,      // 絞り込み検索機能
                paging: false,        // ページ送り機能
                lengthChange: false,  // 表示件数切替機能
                info: true,           // 総件数表示機能

                scrollY: tableHeight + 'px',  // 縦スクロールを表示
                scrollCollapse: true,         //データ行数が少ない場合に調整する

                ajax: {
                    url: 'data.json',
                    // dataSrc: 'data',
                },
                columns: [
                    { },                                // 0
                    { data: 'name' },                   // 1
                    { data: 'position' },               // 2
                    { data: 'office' },                 // 3
                    { data: 'age' },                    // 4
                    { data: 'start_date' },             // 5
                    { data: 'salary' },                 // 6
                ],
                columnDefs: [
                    {
                        targets: 0,
                        orderable: false,
                        render: DataTable.render.select(),
                    },
                    {
                        targets: 3,
                        render: function(data, type, row, meta) {
                            return '<a href="#" class="details-link" name="office[' + meta["row"] + ']">' + data + '</a>';
                        }
                    }
                ],
                select: {
                    style: 'multi',
                    // selector: 'td:first-child',
                    selector: 'td:first-child.dt-select',
                    // headerCheckbox: true,
                    headerCheckbox: false,
                },
                order: [[2, 'asc'], [3, 'desc']],

                rowCallback: function(row, data) {
                    if (data["office"] == '東京') {
                        this.api().row(row).select();
                    }
                    if (data["office"] == '札幌') {
                        $(row).find('input.dt-select-checkbox').prop('disabled', true);
                        $(row).find('td').removeClass('dt-select');
                    }
                }
            });


            // 画面がリサイズされた時
            $(window).resize(function() {
                var newTableHeight = $(window).height() - 300;
                $('.dt-scroll-body').css('max-height', newTableHeight + 'px');
            });

            // 行のリンクをクリックした時
            $('#example tbody').on('click', 'a.details-link', function() {
                event.preventDefault();

                // クリックされたリンクの親の行を取得
                var tr = $(this).closest('tr');
                var row = table.row(tr);

                // 行のデータを取得
                var rowData = row.data();
                console.log(rowData);

                // 行に色を付ける
                $('#example tbody tr').removeClass('highlight');  // 他の行のハイライトを解除
                tr.addClass('highlight');
            });

            // 全選択チェックボックスをクリックした時
            $('#select-all').on('click', function() {
                var rows = table.rows({ 'search': 'applied' }).nodes();
                var state = this.checked;
                $.each(rows, function(index, row) {
                    if (!$('input[type="checkbox"]', row).prop('disabled')) {
                        if (state) {
                            table.row(row).select();
                        } else {
                            table.row(row).deselect();
                        }
                    }
                });
            });
        });

        // GETボタンを押した時
        function get_button() {
            var selectedData = [];
            var table = $('#example').DataTable();
            table.$('tr.selected').each(function() {
                var tr = $(this).closest('tr');
                var row = table.row(tr);
                selectedData.push(row.data());
            });
            console.log(selectedData);
        }

    </script>
</body>
</html>