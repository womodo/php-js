<?php
// テストデータ
$startTime = '2024-05-01 8:15:00';
$endTime = '2024-05-01 17:10:00';
$breaks = [
    ['start' => '10:00:00', 'end' => '10:10:00'],
    ['start' => '12:00:00', 'end' => '12:45:00'],
    ['start' => '15:00:00', 'end' => '15:10:00'],
    ['start' => '23:45:00', 'end' => '00:15:00'],
    ['start' => '04:00:00', 'end' => '04:10:00'],
];

function calculateWorkingHours($startTime, $endTime, $breaks) {
    // 開始時間と終了時間をDateTimeオブジェクトに変換
    $start = new DateTime($startTime);
    $end = new DateTime($endTime);

    // 総作業時間の計算
    $totalWorkDuration = $start->diff($end);
    $totalWorkSeconds = ($totalWorkDuration->days * 24 * 3600) + ($totalWorkDuration->h * 3600) + ($totalWorkDuration->i * 60) + $totalWorkDuration->s;

    // 休憩時間の合計を秒単位で計算
    $totalBreakSeconds = 0;
    
    foreach ($breaks as $break) {
        $breakStart = new DateTime($start->format('Y-m-d') . ' ' . $break['start']);
        $breakEnd = new DateTime($start->format('Y-m-d') . ' ' . $break['end']);

        // 休憩時間が日付をまたぐ場合
        if ($breakEnd < $breakStart) {
            $breakEnd->modify('+1 day');
        }

        if ($breakStart >= $start && $breakEnd <= $end) {
            // 休憩時間が作業時間内の間に完全に収まっている場合
            $breakDuration = $breakStart->diff($breakEnd);
            $totalBreakSeconds += ($breakDuration->days * 24 * 3600) + ($breakDuration->h * 3600) + ($breakDuration->i * 60) + $breakDuration->s;
        } else if ($breakStart < $end && $breakEnd > $start) {
            // 休憩時間の一部が労働時間にかかる場合
            $effectiveBreakStart = $breakStart < $start ? $start : $breakStart;
            $effectiveBreakEnd = $breakEnd > $end ? $end : $breakEnd;
            $breakDuration = $effectiveBreakStart->diff($effectiveBreakEnd);
            $totalBreakSeconds += ($breakDuration->days * 24 * 3600) + ($breakDuration->h * 3600) + ($breakDuration->i * 60) + $breakDuration->s;
        }
    }

    // 実作業時間を秒単位で計算
    $actualWorkSeconds = $totalWorkSeconds - $totalBreakSeconds;

    // 結果を返す
    return floor($actualWorkSeconds / 60);
}

// 実作業時間を計算して表示
$workingHours = calculateWorkingHours($startTime, $endTime, $breaks);
echo "実作業時間：" . $workingHours . "分";
?>
