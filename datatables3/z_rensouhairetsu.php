<?php
// データの定義
$list = [
    [
        "CUST_CD" => "A101",
        "TYPE"    => "XXX",
        "CNT_NEW" => 1,
        "CNT_UPD" => 2
    ],
    [
        "CUST_CD" => "A101",
        "TYPE"    => "XXX",
        "CNT_NEW" => 4,
        "CNT_UPD" => 5
    ],
    [
        "CUST_CD" => "B202",
        "TYPE"    => "YYY",
        "CNT_NEW" => 3,
        "CNT_UPD" => 1
    ],
    [
        "CUST_CD" => "A101",
        "TYPE"    => "XXX",
        "CNT_NEW" => 2,
        "CNT_UPD" => 3
    ],
    [
        "CUST_CD" => "B202",
        "TYPE"    => "YYY",
        "CNT_NEW" => 1,
        "CNT_UPD" => 4
    ]
];

// 集計用の結果配列
$result = [];

// データの集計
foreach ($list as $item) {
    $key = json_encode([
        "CUST_CD" => $item["CUST_CD"],
        "TYPE" => $item["TYPE"]
    ]);
    
    if (!isset($result[$key])) {
        $result[$key] = [
            "CUST_CD" => $item["CUST_CD"],
            "TYPE" => $item["TYPE"],
            "CNT_NEW" => 0,
            "CNT_UPD" => 0
        ];
    }
    
    $result[$key]["CNT_NEW"] += $item["CNT_NEW"];
    $result[$key]["CNT_UPD"] += $item["CNT_UPD"];
}

// 結果の表示
echo "<pre>";
print_r(array_values($result));
echo "</pre>";

?>
