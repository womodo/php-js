<?php

$url = "xxx";

$data = [
    "id" => "abc123",
    "title" => "PHP通知",
    "message" => "curlを使わずにPower Automateを呼び出しました。",
    "link" => "http://www.google.co.jp"
];

$options = [
    'http' => [
        'method'  => 'POST',
        'header'  => "Content-Type: application/json\r\n",
        'content' => json_encode($data),
        'timeout' => 10,
        "ignore_errors" => true  // エラーメッセージを受け取る
    ]
];

$context  = stream_context_create($options);
$response = file_get_contents($url, false, $context);

// ステータスコードを取得
$statusLine = $http_response_header[0] ?? '';
preg_match('{HTTP/\S*\s(\d{3})}', $statusLine, $match);
$statusCode = $match[1] ?? 'N/A';

// JSONとして解析
$json = json_decode($response, true);

// 出力確認
echo "<pre>";
echo "HTTP Status: " . $statusCode . "\n";
echo "Raw Response:\n";
print_r($json);
echo "</pre>";
?>