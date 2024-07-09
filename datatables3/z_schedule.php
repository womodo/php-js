<?php
$year = 2024;
$month = 7;
$targetDays = ['月', '水'];
$targetDay = '毎日'; // '毎日', '不定期', または ['月', '水'] などの具体的な曜日配列

// 日本語の曜日を英語の曜日に変換するマッピング
$dayMapping = [
    '日' => 'Sunday',
    '月' => 'Monday',
    '火' => 'Tuesday',
    '水' => 'Wednesday',
    '木' => 'Thursday',
    '金' => 'Friday',
    '土' => 'Saturday'
];

// カレンダーデータ（例）
$calendar = [
    '2024-07-01' => ['holiday_flg' => 0],
    '2024-07-02' => ['holiday_flg' => 0],
    '2024-07-03' => ['holiday_flg' => 1],
    // その他の日付データを追加
];

// 特定の条件に応じた曜日セットを定義
if ($targetDay == '毎日' || $targetDay == '不定期') {
    $targetDays = ['月', '火', '水', '木', '金'];
}

// 月の最初の日と最後の日を取得
$firstDay = new DateTime("$year-$month-01");
$lastDay = new DateTime($firstDay->format('Y-m-t'));

// JSONデータを作成
$jsonData = [];

for ($day = clone $firstDay; $day <= $lastDay; $day->modify('+1 day')) {
    $dayNumber = $day->format('j');
    $dayName = $day->format('l');
    $dayInJapanese = array_search($dayName, $dayMapping);
    $dateString = $day->format('Y-m-d');
    
    // 休日フラグをチェック
    $isHoliday = isset($calendar[$dateString]) && $calendar[$dateString]['holiday_flg'] == 1;

    // 対象曜日かつ稼働日の場合にtrue
    $jsonData[$dayNumber] = in_array($dayInJapanese, $targetDays) && !$isHoliday;
}

// JSON形式で出力
echo json_encode($jsonData, JSON_PRETTY_PRINT);
?>
