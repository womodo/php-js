<?php
// サンプルデータ。実際は DB から取得して JSON 返すなどに置き換え可能
$data = [
    ["Origin" => "USA", "Cylinders" => 4, "num_cars" => 10],
    ["Origin" => "USA", "Cylinders" => 6, "num_cars" => 5],
    ["Origin" => "Europe", "Cylinders" => 4, "num_cars" => 7],
    ["Origin" => "Europe", "Cylinders" => 4, "num_cars" => 7],
    ["Origin" => "Europe", "Cylinders" => 6, "num_cars" => 3],
    ["Origin" => "Japan", "Cylinders" => 4, "num_cars" => 12],
    ["Origin" => "Japan", "Cylinders" => 6, "num_cars" => 4],
    ["Origin" => "Japan", "Cylinders" => 6, "num_cars" => 4],
    ["Origin" => "Japan", "Cylinders" => 6, "num_cars" => 4],
    ["Origin" => "Japan", "Cylinders" => "J105-123456-RM", "num_cars" => 4],
    ["Origin" => "不良現象：巣", "Cylinders" => "J105-123456-RM", "num_cars" => 4]
];

header('Content-Type: application/json');
echo json_encode($data);
?>