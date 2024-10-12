<?php 
$servername = "localhost";
$username = "root";
$password = "";
$database = "pulsemind";

// connection to database
$connection = new mysqli($servername, $username, $password, $database);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Fetch the latest record
$sql = "SELECT * FROM esp32 ORDER BY testid DESC";
$result = $connection->query($sql);

if($row = $result->fetch_assoc()){
    echo json_encode($row);
}

$connection->close();
?>
