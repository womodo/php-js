<?php
$dbname = "phpjs";
$servername = "localhost";
$username = "root";
$password = "zaq12wsx";
$dsn = "mysql:dbname=".$dbname.";host=".$servername;
$dbh = new PDO($dsn, $username, $password);

$data = [];
$target = htmlspecialchars($_POST["target"]);

function querySql($sql) {
    global $dbh;
    $stmt = $dbh->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $data = [];
    foreach ($result as $rows) {
        foreach ($rows as $key => $value) {
            if ($value === 0) {
                $value = 0;
            } elseif ($value == null) {
                $value = "";
            }
            $row[$key] = $value;
        }
        $data[] = $row;
    }
    return $data;
}

if ($target == "GET") {
    $imgType = htmlspecialchars($_POST["IMG_TYPE"]);
    $sql = "SELECT FILE_PATH FROM m_image ";
    $sql.= "WHERE IMG_TYPE = '$imgType' ";
    $sql.= "ORDER BY ID ";
    $data = querySql($sql);
}

if ($target == "INSERT") {
    $imgType = htmlspecialchars($_POST["IMG_TYPE"]);
    $filePathList = htmlspecialchars($_POST["FILE_PATH_LIST"]);

    foreach (explode(",", $filePathList) as $filePath) {
        $sql = "INSERT INTO m_image (IMG_TYPE,FILE_PATH) ";
        $sql.= "VALUES ('$imgType','$filePath') ";
        $dbh->exec($sql);
    }
}

if ($target == "DELETE") {
    $imgType = htmlspecialchars($_POST["IMG_TYPE"]);
    $filePath = htmlspecialchars($_POST["FILE_PATH"]);

    $sql = "DELETE FROM m_image ";
    $sql.= "WHERE IMG_TYPE = '$imgType' ";
    $sql.= "AND FILE_PATH = '$filePath' ";
    $dbh->exec($sql);
}

$dbh = null;
echo json_encode($data);
?>