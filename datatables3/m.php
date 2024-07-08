<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Data Editing Interface</title>
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/select/2.0.3/css/select.dataTables.min.css">
</head>
<body>

<div style="display: flex; justify-content: space-evenly; white-space: nowrap;">
    <div id="cust_grp_table">
        <h3>Customer Group Table</h3>
        <button id="addRow">Add Row</button>
        <button id="submit">Save</button>
        <table id="cust_grp" class="display">
            <thead>
                <!-- <tr>
                    <th></th>
                    <th>得意先グループ</th>
                    <th>種類</th>
                    <th>得意先グループ名</th>
                    <th>サイクル</th>
                    <th>時間</th>
                </tr> -->
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <div id="cust_table">
        <h3>Customer Table</h3>
        <table id="cust" class="display">
            <thead>
                <tr>
                    <th>CUST_GRP_CD</th>
                    <th>CUST_CD</th>
                    <th>ODR_TYPE</th>
                    <th>CUST_NAME</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/select/2.0.3/js/dataTables.select.min.js"></script>
<script>
$(document).ready(function() {
    // テーブル1
    var table1 = new DataTable('#cust_grp', {
        paging: false,
        select: {
            style: 'single',
            toggleable: false,
            selector: 'td:first-child',
            headerCheckbox: false,
        },
        ajax: {
            url: 'fetch_cust_grp_data.php',
            type: 'POST',
        },
        dataSrc: '',
        columns: [
            { data: null },
            { data: 'CUST_GRP_CD', title: '得意先グループ' },
            { data: 'ODR_TYPE', title: '種類' },
            { data: 'CUST_GRP_NAME', title: '得意先グループ名' },
            { data: 'ODR_TIMINGS', title: 'サイクル' },
            { data: 'ODR_TIMES', title: '時間' },
            { data: null },
        ],
        columnDefs: [
            {
                orderable: false,
                render: DataTable.render.select(),
                targets: 0
            },
            {
                targets: [1, 2, 3, 4, 5],
                render: function(data, type, row) {
                    return `<input type="text" value="${data}">`;
                }
            },
            {
                orderable: false,
                render: function(data, type, row) {
                    return '<input type="checkbox">';
                },
                targets: 6
            }
        ],
        order: [
            [1, 'asc']
        ],
    });

    // テーブル2
    var table2 = new DataTable('#cust', {
        paging: false,
        columns: [
            { data: 'CUST_GRP_CD' },
            { data: 'CUST_CD' },
            { data: 'ODR_TYPE' },
            { data: 'CUST_NAME' },
        ],
    });

    // テーブル1の行を選択した時
    table1.on('select', function (e, dt, type, indexes) {
        if (type == 'row') {
            var data = table1.row(indexes).data();
            var CUST_GRP_CD = data.CUST_GRP_CD;
            var ODR_TYPE = data.ODR_TYPE;

            console.log(data);
            
            $.ajax({
                url: 'fetch_cust_data.php',
                type: 'POST',
                data: {
                    CUST_GRP_CD: CUST_GRP_CD,
                    ODR_TYPE: ODR_TYPE,
                },
                success: function(response) {
                    if (response.data) {
                        table2.clear().draw();
                        table2.rows.add(response.data).draw();
                    } else {
                        table2.clear().draw();
                    }
                },
                error: function(xhr, status, error) {
                    alert("Error : " + error);
                }
            });
        }
    });

    // // テーブル1の行の選択を外した時
    // table1.on('deselect', function (e, dt, type, indexes) {
    //     if (type == 'row') {
    //         table2.clear().draw();
            
    //         $.ajax({
    //             url: 'fetch_cust_data.php',
    //             type: 'POST',
    //             success: function(response) {
    //                 table2.clear().draw();
    //                 if (response.data) {
    //                     table2.rows.add(response.data).draw();
    //                 }
    //             },
    //             error: function(xhr, status, error) {
    //                 alert("Error : " + error);
    //             }
    //         });
    //     }
    // });

    // 行追加ボタンのクリックイベント
    $('#addRow').on('click', function() {
        // 新しい行のデータ
        var newRowData = {
            CUST_GRP_CD: '',
            ODR_TYPE: '',
            CUST_GRP_NAME: '',
            ODR_TIMINGS: '',
            ODR_TIMES: '',
        };
        table1.row.add(newRowData).draw();
    });

    // Saveボタンのクリックイベント
    $('#submit').on('click', function(e) {
        e.preventDefault();

        // var columnDefs = table1.settings().init().columns;
        // var columnNames = columnDefs.map(function(col) {
        //     return col.data;
        // });
        // console.log(columnNames);

        // var selectedRows = table1.rows({ selected: true }).nodes();
        // var selectedData = [];

        // selectedRows.each(function(row) {
        //     var rowData = {};
        //     $(row).find('td').each(function(index) {
        //         var inputElement = $(this).find('input, select');
        //         if (inputElement.length > 0) {
        //             rowData[columnNames[index]] = inputElement.val();
        //         } else {
        //             rowData[columnNames[index]] = $(this).text();
        //         }
        //     });
        //     selectedData.push(rowData);
        // });

        var selectedRow = table1.row({ selected: true }).node();
        var rowData = {};
        if (selectedRow) {
            var columnDefs = table1.settings().init().columns;
            var columnNames = columnDefs.map(function(col) {
                return col.data;
            });

            $(selectedRow).find('td').each(function(index) {
                var inputElement = $(this).find('input, select');
                if (inputElement.length > 0) {
                    rowData[columnNames[index]] = inputElement.val();
                } else {
                    rowData[columnNames[index]] = $(this).text();
                }
            });

            console.log(rowData);

        } else {
            console.log('No row selected');
        }


        // var data = table1.$('input, select').serialize();
        // console.log(data);
    });
});
</script>

</body>
</html>
