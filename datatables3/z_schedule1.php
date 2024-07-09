<?php
$year = 2024;
$month = 7;
$targetDays = ['月', '水'];
$targetDay = '不定期'; // '毎日', '不定期', または ['月', '水'] などの具体的な曜日配列

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
    $dayNumber = $day->format('d');
    $dayName = $day->format('l');
    $dayInJapanese = array_search($dayName, $dayMapping);
    $jsonData[$dayNumber] = in_array($dayInJapanese, $targetDays);
}

// JSON形式で出力
echo json_encode($jsonData, JSON_PRETTY_PRINT);
?>
