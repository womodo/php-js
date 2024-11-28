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

// POSTリクエストからデータを取得
$id = $_POST['id'];
$opt1 = $_POST['opt1'];
$opt2 = $_POST['opt2'];
$opt3 = $_POST['opt3'];

// データベースのレコードを更新
$sql = "UPDATE sample_table SET opt1 = :opt1, opt2 = :opt2, opt3 = :opt3 WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':opt1', $opt1, PDO::PARAM_INT);
$stmt->bindParam(':opt2', $opt2, PDO::PARAM_INT);
$stmt->bindParam(':opt3', $opt3, PDO::PARAM_INT);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);

if ($stmt->execute()) {
    echo "データが正常に更新されました。";
} else {
    echo "更新に失敗しました。";
}
?>
