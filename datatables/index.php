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
</style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Bootstrap 5 と DataTables のサンプル</h1>
        <table id="example" class="table table-striped table-bordered table-hover table-sm" style="width: 100%;">
            <thead>
                <tr>
                    <th></th>
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
                                text: 'Add',
                                action: function (e, dt, node, config) {
                                    alert('ボタンがクリックされました');
                                }
                            }
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
                // columns: [
                //     { data: 'id', orderable: false, render: DataTable.render.select(), },
                //     { data: 'name' },
                //     { data: 'position' },
                //     { data: 'office' },
                //     { data: 'age' },
                //     { data: 'start_date' },
                //     { data: 'salary' },
                // ],
                columnDefs: [
                    {
                        targets: 0,
                        orderable: false,
                        render: DataTable.render.select(),
                    },
                    {
                        targets: 1,
                        data: 'name',
                    },
                    {
                        targets: 2,
                        data: 'position',
                    },
                    {
                        targets: 3,
                        data: 'office',
                        render: function (data, type, row, meta) {
                            return '<a href="#" name="office[' + meta["row"] + ']">' + data + '</a>';
                        }
                    },
                    {
                        targets: 4,
                        data: 'age',
                    },
                    {
                        targets: 5,
                        data: 'start_date',
                    },
                    {
                        targets: 6,
                        data: 'salary',
                    }
                ],
                select: {
                    style: 'multi',
                    selector: 'td:first-child',
                    headerCheckbox: true,
                },
                order: [[2, 'asc'], [3, 'desc']]
            });


            // var tableHeight = $(window).height() - 300;

            // var table = $('#example').DataTable({
            //     language: {
            //         url: './js/ja.json',
            //     },
            //     ajax: {
            //         "url": "data.json",
            //         "type": "POST"
            //     },
            //     columnDefs: [
            //         {
            //             orderable: false,
            //             render: DataTable.render.select(),
            //             targets: 0
            //         }
            //     ],
            //     select: {
            //         style: 'os',
            //         selector: 'td:first-child'
            //     },
            //     order: [[1, 'asc']],
            //     columns: [
            //         {
            //             data: "id",
            //             render: function(data, type, row, meta) {
            //                 return '<input type="checkbox" class="row-checkbox" value="' + data + '">';
            //             },
            //             orderable: false,
            //         },
            //         { "data": "name" },
            //         { "data": "position" },
            //         { "data": "office" },
            //         { "data": "age" },
            //         { "data": "start_date" },
            //         { "data": "salary" }
            //     ],
            //     paging: false,
            //     scrollY: tableHeight + 'px',
            //     scrollCollapse: true,
            //     layout: {
            //         topStart: {
            //             buttons: [
            //                 'copy',
            //                 {
            //                     text: 'Add',
            //                     action: function (e, dt, node, config) {
            //                         alert('ボタンがクリックされました');
            //                     }
            //                 }
            //             ]
            //         }
            //     },
            //     select: true,
            // });

            // // 全選択チェックボックス
            // $('#checkAll').click(function() {
            //     var rows = table.rows({ 'search': 'applied' }).nodes();
            //     $('input[type="checkbox"]', rows).prop('checked', this.checked);
            // });

            // // 画面がリサイズされた時
            // $(window).resize(function() {
            //     var newTableHeight = $(window).height() - 300;
            //     $('.dt-scroll-body').css('max-height', newTableHeight + 'px');
            // });
        });

    </script>
</body>
</html>