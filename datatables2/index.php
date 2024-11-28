<?php
$var1 = 1;
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>DataTablesサンプル</title>
<link rel="stylesheet" href="./css/bootstrap-5.3.0.min.css">
<link rel="stylesheet" href="./css/dataTables.bootstrap5-2.0.8.min.css">
<link rel="stylesheet" href="./css/buttons.dataTables-3.0.2.min.css">
<link rel="stylesheet" href="./css/scroller.bootstrap5-2.4.3.min.css">
<style>
    #example_wrapper th {
        text-align: center;
        background-color: lightblue;
        vertical-align: middle;
    }
    #example_wrapper td {
        text-align: center;
    }

    .top_toolbar {
        text-align: center;
        background-color: black;
        color: white;
        padding: 10px;
    }

    /* ボタン */
    .dt-button {
        line-height: 1.2em !important;
    }
    /* 追加ボタン */
    .btn-add {
        font-weight: bold;
        width: 100px;
    }
    /* 承認更新ボタン */
    .btn-approval {
        background-color: #90ee90 !important;
        font-weight: bold;
        width: 120px;
        margin-left: 20px !important;
    }

    /* 情報 */
    .dt-info {
        padding-top: 0 !important;
    }

    /* チェックボックス */
    table.table.dataTable > tbody > tr.selected a {
        color: rgba(var(--bs-link-color-rgb), var(--bs-link-opacity, 1)) !important;
    }
    table.table.dataTable > tbody > tr.selected > * {
        box-shadow: none !important;
        color: var(--bs-table-color-state,var(--bs-table-color-type,var(--bs-table-color))) !important;
    }
    /* 承認済み */
    tr.approved > td {
        background-color: #daf5e1 !important;
    }
    /* 連携済み */
    tr.alignmented > td > input[type="checkbox"] {
        accent-color: #24140e;
    }
    /* リンクした行 */
    tr.highlight > td {
        background-color: #fff3b8 !important;
    }
    /* 更新対象 */
    tr.upd-target td.dt-select {
        background-color: #ffadad !important;
    }

</style>
<script src="./js/jquery-3.7.1.min.js"></script>
<script src="./js/bootstrap.bundle-5.3.0.min.js"></script>
<script src="./js/dataTables-2.0.8.min.js"></script>
<script src="./js/dataTables.bootstrap5-2.0.8.min.js"></script>
<script src="./js/dataTables.buttons-3.0.2.min.js"></script>
<script src="./js/buttons.dataTables-3.0.2.min.js"></script>
<script src="./js/buttons.html5-3.0.2.min.js"></script>
<script src="./js/dataTables.scroller-2.4.3.min.js"></script>
<script src="./js/scroller.bootstrap5-2.4.3.min.js"></script>
<script src="./js/dataTables.select-2.0.3.min.js"></script>
<script>
$(function() {
    // テーブルの高さ
    var tableHeight = $(window).height() - 300;

    // DataTables設定
    var table = new DataTable('#example', {
        ordering: true,                 // 並び替え機能
        searching: true,                // 絞り込み検索機能
        paging: false,                  // ページ送り機能
        lengthChange: false,            // 表示件数切替機能
        info: true,                     // 総件数表示機能
        scrollY: tableHeight + 'px',    // 縦スクロールを表示
        scrollCollapse: true,           // データ行数が少ない場合に調整する

        // レイアウト設定
        layout: {
            top: function() {
                var toolbar = document.createElement('div');
                toolbar.className = 'top_toolbar dt-buttons';
                toolbar.innerHTML = '<b>Custom tool bar! Text/images etc.</b><button class="dt-button" style="background-color:white;color:black;" type="button" onclick="btn_reload()"><span>Reload</span></button>';
                return toolbar;
            },
            topStart: {
                // 検索ボックス
                search: {
                    text: '検索',
                },
            },
            topEnd: {
                // ボタン設定
                buttons: [
                    // 'copy',
                    // 'showSelected',
                    {
                        extend: 'copy',
                        enabled: false,
                    },
                    {
                        text: '追加',
                        className: 'btn-add',
                        action: function(e, dt, node, config) {
                            alert('追加ボタン');
                        }
                    },
                    {
                        text: '承認更新',
                        className: 'btn-approval',
                        action: btn_approval,
                    },
                ],
            },
            bottomStart: false,
            bottomEnd: {
                // 情報表示
                info: {
                    empty: '',
                    text: '全 _TOTAL_ 件',
                },
            },
        },
        
        // Ajaxでデータ取得
        ajax: {
            url: 'getList.php',
            type: 'post',
            data: {
                user_id: 123,           // 引数
            },
            dataSrc: 'employees',       // データソース
        },

        // 項目設定
        columns: [
            { data: 'name' },           // 0
            { data: 'position' },       // 1
            { data: 'office' },         // 2
            { data: 'age' },            // 3
            { data: 'start_date' },     // 4
            { data: 'salary' },         // 5
            { },                        // 6
        ],
        // 項目定義
        columnDefs: [
            {
                // 項目にリンクを設定
                targets: 2,
                render: function(data, type, row, meta) {
                    return '<a href="#" class="details-link" name="office[' + meta["row"] + ']">' + data + '</a>';
                }
            },
            {
                targets: 3,
                render: function(data, type, row, meta) {
                    return data + ' 歳';
                },
            },
            {
                // チェックボックス項目の設定
                targets: 6,
                orderable: false,        // 並び替え不可
                render: DataTable.render.select(),
            },
        ],
        
        // 並び替え
        order: [
            [2, 'asc'],
            [3, 'desc'],
        ],

        // チェックボックス設定
        select: {
            style: 'multi',                     // 複数選択
            headerCheckbox: false,              // 全選択チェックボックスは独自処理
            selector: 'td.dt-select input',     // 選択の切り替えはチェックボックスのみ
        },
        
        // 言語設定
        language: {
            url: 'https://cdn.datatables.net/plug-ins/2.0.8/i18n/ja.json',  // 日本語化
            select: {
                rows: null,
            },
        },

        // テーブルの描画が完了した後
        rowCallback: function(row, data) {
            if (data['approved'] == '1') {
                // 承認済みの場合は、チェックボックスにチェックを入れて行の色を変更
                $(row).attr('data-programmatic', 'true');
                this.api().row(row).select();
                $(row).addClass('approved');

                if (data['alignmented'] == '1') {
                    // 連携済みは変更不可
                    $(row).find('input.dt-select-checkbox').off('click');
                    $(row).addClass('alignmented');
                    $(row).find('td').removeClass('dt-select');
                }
            }
            if (data['disabled'] == '1') {
                // 変更対象外は変更不可
                $(row).find('input.dt-select-checkbox').prop('disabled', true);
                $(row).find('td').removeClass('dt-select');
            }
        },
    });

    // 画面がリサイズされた時にテーブルの高さを変更
    $(window).resize(function() {
        var newTableHeight = $(window).height() - 300;
        $('.dt-scroll-body').css('max-height', newTableHeight + 'px');
    });

    // 行のリンクをクリックした時
    $('#example tbody').on('click', 'a.details-link', function() {
        event.preventDefault();

        // クリックしたリンクの行を取得
        var tr = $(this).closest('tr');
        var row = table.row(tr);
        // 行のデータを取得
        var rowData = row.data();

        if (!event.ctrlKey) {
            // Ctrlキーが押されていない場合
            $('#example tbody tr').removeClass('highlight');    // 他の行のハイライトを削除
        }

        // 行に色を付ける(ハイライト)
        tr.addClass('highlight');

        var hltDatas = [];
        table.rows('.highlight').every(function(rowIdx, tableLoop, rowLoop) {
            hltDatas.push(this.data());
        });
        console.log(hltDatas);
    });

    // チェックボックスにチェックを入れた時
    table.on('select', function(e, dt, type, indexes) {
        // 対象の行のデータを取得
        var row = table.rows(indexes).nodes()[0];
        var rowData = table.rows(indexes).data().get(0);

        var isProgrammatic = $(row).attr('data-programmatic') === 'true';
        if (isProgrammatic) {
            // Ajaxで取得して最初に表示する際
            $(row).removeAttr('data-programmatic');

        } else {
            // 未承認のデータにチェックを入れたら更新対象
            if (rowData['approved'] == '0') {
                $(row).addClass('upd-target');
            } else {
                $(row).removeClass('upd-target');
            }
        }
    });

    // チェックボックスのチェックを外した時
    table.on('deselect', function(e, dt, type, indexes) {
        // 対象の行のデータを取得
        var row = table.rows(indexes).nodes()[0];
        var rowData = table.rows(indexes).data().get(0);

        // 承認済みのデータのチェックを外したら更新対象
        if (rowData['approved'] == '1') {
            $(row).addClass('upd-target');
        } else {
            $(row).removeClass('upd-target');
        }
    });

    // 全選択チェックボックスをクリックした時
    $('#select-all').on('click', function() {
        var rows = table.rows({ 'search': 'applied' }).nodes();
        var state = this.checked;
        $.each(rows, function(index, row) {
            // 全選択チェックボックスと合わせたチェックに変更（連携済みと無効は変更しない）
            if (!$(row).hasClass('alignmented') && !$('input[type="checkbox"]', row).prop('disabled')) {
                if (state) {
                    table.row(row).select();
                } else {
                    table.row(row).deselect();
                }
            }
        });
    });
});

// リロード処理
function btn_reload() {
    var table = $('#example').DataTable();
    table.ajax.reload();
}

// 承認処理
function btn_approval(e, dt, node, config) {
    var table = $('#example').DataTable();

    // 更新対象の行を取得
    var updDatas = [];
    table.rows('.upd-target').every(function(rowIdx, tableLoop, rowLoop) {
        updDatas.push(this.data());
    });
    console.log(updDatas);
}
</script>
</head>
<body>
    <div class="container mt-5">
        <table id="example" class="table table-bordered table-hover table-sm" style="width: 100%;">
            <thead>
                <tr>
                    <th>名前</th>
                    <th>ポジション</th>
                    <th>オフィス</th>
                    <th>年齢</th>
                    <th>開始日</th>
                    <th>給与</th>
                    <th>承認　<br><input type="checkbox" id="select-all"></th>
                </tr>
            </thead>            
        </table>
    </div>
</body>
</html>