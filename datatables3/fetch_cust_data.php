<?php
// Replace with your database connection details
$servername = "localhost";
$dbname = "test";
$username = "root";
$password = "zaq12wsx";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$CUST_GRP_CD = isset($_POST["CUST_GRP_CD"]) ? $_POST["CUST_GRP_CD"] : '';
$ODR_TYPE = isset($_POST["ODR_TYPE"]) ? $_POST["ODR_TYPE"] : '';


// Fetch data from m_odr_cust table
$sql = "SELECT * FROM m_odr_cust WHERE 0 = 0 ";
if ($CUST_GRP_CD && $ODR_TYPE) {
    $sql.= "AND CUST_GRP_CD = '".$CUST_GRP_CD."' ";
    $sql.= "AND ODR_TYPE = '".$ODR_TYPE."' ";
}
$result = $conn->query($sql);

$data = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data["data"][] = $row;
    }
}

$conn->close();

// Return JSON response
header('Content-Type: application/json');
echo json_encode($data);
?>
