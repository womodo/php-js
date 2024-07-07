<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Dynamic DataTables</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
</head>
<body>
    <input type="month" id="monthPicker">
    <button id="loadData">データをロード</button>
    <table id="example" class="display" style="width:100%">
        <thead>
            <tr id="tableHead"></tr>
        </thead>
        <tbody id="tableBody"></tbody>
    </table>

    <script>
        $(document).ready(function() {
            function loadData(month) {
                $.ajax({
                    url: 'xdata.php',
                    method: 'GET',
                    data: { month: month },
                    dataType: 'json',
                    success: function(data) {
                        $('#example').DataTable().destroy();

                        // 動的にカラムヘッダーを生成
                        var tableHead = $('#tableHead');
                        tableHead.empty();
                        data.columns.forEach(function(column) {
                            tableHead.append('<th>' + column.title + '<br>(' + column.day + ')</th>');
                        });

                        // // DataTables を初期化または再描画
                        // if ($.fn.dataTable.isDataTable('#example')) {
                        //     $('#example').DataTable().clear().destroy();
                        // }

                        console.log(data);
                        $('#tableBody').empty();

                        // DataTablesを初期化
                        $('#example').DataTable({
                            sort: false,
                            data: data.data,
                            // columns: data.columns.map(col => ({ title: col.title }))
                        });
                    }
                });
            }

            // 現在の年月を取得し、初期表示
            var currentMonth = new Date().toISOString().slice(0, 7);
            $('#monthPicker').val(currentMonth);
            loadData(currentMonth);

            // ボタンがクリックされた時の動作
            $('#loadData').click(function() {
                var selectedMonth = $('#monthPicker').val();
                if (selectedMonth) {
                    loadData(selectedMonth);
                } else {
                    alert('年月を選択してください。');
                }
            });
        });
    </script>
</body>
</html>
