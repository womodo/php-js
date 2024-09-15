<?php
// データベース接続
$host = 'localhost';
$db = 'phpjs';
$user = 'root';
$pass = 'zaq12wsx';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("データベース接続に失敗しました: " . $e->getMessage());
}

// DataTablesがサーバーサイドで要求するデータを取得
$stmt = $pdo->prepare("SELECT id, item, opt1, opt2, opt3 FROM sample_table");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// DataTables用にJSON形式にエンコードして返す
echo json_encode(['data' => $rows]);
?>