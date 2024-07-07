<?php
if (isset($_GET['month'])) {
    $month = $_GET['month'];
    $date = new DateTime($month . '-01');
    $daysInMonth = $date->format('t');

    // 日本語の曜日
    $weekDays = ['日', '月', '火', '水', '木', '金', '土'];

    // サンプルデータを生成（ここでは静的なデータを使用）
    $data = [];
    for ($i = 0; $i < 10; $i++) { // 10行のデータ
        $row = [];
        for ($j = 1; $j <= $daysInMonth; $j++) {
            $row[] = rand(1, 100); // ランダムなデータ
        }
        $data[] = $row;
    }

    // カラム情報を生成
    $columns = [];
    for ($i = 1; $i <= $daysInMonth; $i++) {
        $currentDate = new DateTime($month . '-' . $i);
        $dayOfWeek = $weekDays[$currentDate->format('w')];
        $columns[] = ["title" => $i, "day" => $dayOfWeek];
    }

    // JSONレスポンスを返す
    echo json_encode([
        'daysInMonth' => $daysInMonth,
        'data' => $data,
        'columns' => $columns
    ]);
}
?>
