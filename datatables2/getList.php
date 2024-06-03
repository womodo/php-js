<?php
// データベース接続設定
$host = 'localhost'; // ホスト名
$dbname = 'phpjs'; // データベース名
$user = 'root'; // ユーザー名
$pass = 'zaq12wsx'; // パスワード

// PDOインスタンスの作成
$dbh = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// データ取得クエリ
$sql = "SELECT * FROM employees";
$stmt = $dbh->query($sql);

// 結果の取得
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
$List["employees"] = $employees;

// JSONに変換
$json_data = json_encode($List, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

// JSONデータの表示
header('Content-Type: application/json');
echo $json_data;
?>