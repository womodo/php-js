<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DataTables カラム変更</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
</head>
<body>

<div class="container mt-5">
    <div class="row mb-3">
        <div class="col-md-3">
            <label for="monthPicker">年月を選択:</label>
            <input type="month" id="monthPicker" class="form-control">
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <table id="example" class="table table-striped table-bordered" style="width:100%"></table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    var table;

    // 指定された年月に基づいてテーブルを更新する関数
    function updateTable(year, month) {
        // その月の日数を計算
        var daysInMonth = new Date(year, month, 0).getDate();
        var newData = [];
        var newColumns = [];

        // ダミーデータ生成
        for (var i = 0; i < 10; i++) { // 行数は任意
            var rowData = [];
            for (var j = 1; j <= daysInMonth; j++) {
                rowData.push('Data ' + (i + 1) + '-' + j);
            }
            newData.push(rowData);
        }

        // カラムヘッダ生成
        for (var j = 1; j <= daysInMonth; j++) {
            newColumns.push({ title: j + '日' });
        }

        // テーブルが既に存在する場合は破棄
        if (table) {
            table.destroy();
            $('#example').empty(); // 古いデータをクリア
        }

        // DataTableの再初期化
        table = $('#example').DataTable({
            data: newData,
            columns: newColumns,
            scrollX: true, // 横スクロールを有効にする
            autoWidth: false,
            lengthChange: false,
            searching: false,
            paging: false,
            info: false
        });
    }

    // 月の入力が変更されたときのイベントハンドラ
    $('#monthPicker').on('change', function() {
        var selectedDate = $(this).val();
        var year = parseInt(selectedDate.split('-')[0]);
        var month = parseInt(selectedDate.split('-')[1]);
        updateTable(year, month);
    });

    // 初期表示用に現在の年月でテーブルを表示
    var today = new Date();
    var currentYear = today.getFullYear();
    var currentMonth = today.getMonth() + 1; // 月は0始まりなので+1する
    $('#monthPicker').val(currentYear + '-' + ('0' + currentMonth).slice(-2));
    updateTable(currentYear, currentMonth);
});
</script>

</body>
</html>
