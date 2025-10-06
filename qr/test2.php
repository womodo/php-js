<?php
$keyword = $_POST["keyword"] ?? "";

// データ例（DBなどから取得してもOK）
$allData = [
    ["code" => "apple",  "name" => "リンゴ"],
    ["code" => "banana", "name" => "バナナ"],
    ["code" => "grape",  "name" => "ぶどう"],
    ["code" => "orange", "name" => "オレンジ"],
    ["code" => "melon",  "name" => "メロン"],
];

// キーワードでフィルタ（部分一致）
$result = [];
foreach ($allData as $item) {
    if ($keyword === "" || stripos($item["name"], $keyword) !== false || stripos($item["code"], $keyword) !== false) {
        $result[] = $item;
    }
}

// JSONとして返す
echo json_encode($result, JSON_UNESCAPED_UNICODE);
