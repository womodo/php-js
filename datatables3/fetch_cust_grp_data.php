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

// Fetch data from m_odr_cust_grp table
$sql = "SELECT * FROM m_odr_cust_grp";
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
