<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Document</title>
<!-- Bootstrap 5 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<!-- DataTables + Bootstrap 5 -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css">
<!-- DataTables FixedColumns (※Bootstrap 5 を使うとBorderが消える) -->
<link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/5.0.1/css/fixedColumns.dataTables.min.css">
<!-- DataTables SearchPanes + Bootstrap 5 -->
<link rel="stylesheet" href="https://cdn.datatables.net/searchpanes/2.3.1/css/searchPanes.bootstrap5.min.css">
<!-- DataTables Select + Bootstrap 5 -->
<link rel="stylesheet" href="https://cdn.datatables.net/select/2.0.3/css/select.bootstrap5.min.css">
<style>
    th, td {
        white-space: nowrap;
    }
    #example_wrapper {
        width: 1000px;
        margin: 0 auto;
    }
</style>
<!-- Bootstrap 5 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- DataTables + Bootstrap 5 -->
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>
<!-- DataTables FixedColumns -->
<script src="https://cdn.datatables.net/fixedcolumns/5.0.1/js/dataTables.fixedColumns.min.js"></script>
<!-- DataTables SearchPanes + Bootstrap 5 -->
<script src="https://cdn.datatables.net/searchpanes/2.3.1/js/dataTables.searchPanes.js"></script>
<script src="https://cdn.datatables.net/searchpanes/2.3.1/js/searchPanes.bootstrap5.min.js"></script>
<!-- DataTables Select + Bootstrap 5 -->
<script src="https://cdn.datatables.net/select/2.0.3/js/dataTables.select.js"></script>
<script src="https://cdn.datatables.net/select/2.0.3/js/select.bootstrap5.min.js"></script>

<script>
$(function() {
    var table = new DataTable('#example', {
        fixedColumns: {
            start: 2
        },
        paging: false,
        scrollCollapse: true,
        scrollY: '500px',
        scrollX: true,

        ajax: {
            url: 'data.json',
        },
        dataSrc: 'data',
        columns: [
            {                           // 0
                className: 'dt-control',
                orderable: false,
                data: null,
                defaultContent: '',
            },
            { data: 'id' },             // 1
            { data: 'name' },           // 2
            { data: 'position' },       // 3
            { data: 'office' },         // 4
            { data: 'salary' },         // 5
            { data: 'start_date' },     // 6
            { data: 'extn' },           // 7
        ],
        order: [
            [1, 'asc']
        ],


        layout: {
            top1: {
                searchPanes: {
                    layout: 'columns-3',
                    cascadePanes: true,
                    viewTotal: true,
                }
            }
        },
        
        columnDefs: [
            {
                searchPanes: {
                    show: true,
                    header: '名前',
                    // viewCount: false,
                    orderable: false,
                    // collapse: false,
                },
                targets: [2],
            },
            {
                searchPanes: {
                    show: true,
                },
                targets: [3],
            },
            {
                searchPanes: {
                    show: true
                },
                targets: [4],
            },
            // {
            //     searchPanes: {
            //         show: true
            //     },
            //     targets: [5],
            // },
        ],


        // language: {
        //     url: '//cdn.datatables.net/plug-ins/2.0.8/i18n/ja.json',
        // },
    });

    table.on('click', 'td.dt-control', function(e) {
        var tr = e.target.closest('tr');
        var row = table.row(tr);
        if (row.child.isShown()) {
            row.child.hide();
        } else {
            row.child(format(row.data())).show();
        }
    });

    function format(d) {
        return (
            '<table class="table table-bordered table-sm table-secondary" style="margin:10px; width:98%;">'+
            '<tr>' +
                '<th style="width:150px;">Full Name:</th>' +
                '<td>' + d.name + '</td>' +
            '</tr>' +
            '<tr>' +
                '<th>Extension number:</th>' +
                '<td>' + d.extn + '</td>' +
            '</tr>' +

            '</table>'
        );
    }
});
</script>
</head>
<body>
    <div class="container mt-3">
        <div class="row">
            <div class="col-12">
                <table id="example" class="table table-bordered table-hover table-sm nowrap" style="width:100%;">
                    <thead>
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Office</th>
                            <th>Salary</th>
                            <th>Start Date</th>
                            <th>Extn</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</body>
</html>