<?php
$currentDateTime = date('Y-m-d H:i');
echo $currentDateTime;
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <input type="datetime-local" name="" id="" value="<?=$currentDateTime?>">
</body>
</html>