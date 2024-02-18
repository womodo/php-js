<?php
$img_src = htmlspecialchars($_POST["img_src"]);
$window_title = htmlspecialchars($_POST["window_title"]);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$window_title?></title>
</head>
<style>
    body, html {
        margin: 0;
        padding: 0;
        height: 100%;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    img {
        max-height: 100%;
        max-width: 100%;
    }
</style>
<body>
    <img src="<?=$img_src?>">
</body>
</html>