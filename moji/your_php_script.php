<?php

// データのサンプル
$data = array(
    "message" => "こんにちは",
    "status" => "成功",
    "name" => "山田太郎",
);

header('Content-Type: application/json; charset=Shift_JIS');

// JSONエンコード
$json = json_encode($data, JSON_UNESCAPED_UNICODE);
echo $json;
?>
