<?php
$array1 = [
    'key1' => [1, 2, 3],
    'key2' => [4, 5, 6],
    'key3' => [10, 11, 12],
];

$array2 = [
    'key1' => [1, 2, 3],
    'key2' => [4, 5, 7],
    'key4' => [20, 21, 22],
];

// 配列の差分を取得する関数
function compare_arrays($array1, $array2) {
    $differences = [];

    // array1 を基準に比較
    foreach ($array1 as $key => $value) {
        // array2にキーが存在するかチェック
        if (array_key_exists($key, $array2)) {
            // キーが存在する場合、配列の内容を比較
            if ($value !== $array2[$key]) {
                $differences[$key] = [
                    'array1' => $value,
                    'array2' => $array2[$key]
                ];
            }
        } else {
            // array2にキーが存在しない場合
            $differences[$key] = [
                'array1' => $value,
                'array2' => null
            ];
        }
    }

    // array2 にしか存在しないキーをチェック
    foreach ($array2 as $key => $value) {
        if (!array_key_exists($key, $array1)) {
            $differences[$key] = [
                'array1' => null,
                'array2' => $value
            ];
        }
    }

    return $differences;
}

// 配列を比較して結果を出力
$differences = compare_arrays($array1, $array2);
print_r($differences);
?>
