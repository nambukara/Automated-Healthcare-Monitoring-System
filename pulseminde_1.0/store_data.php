<?php
$servername = "localhost";
$username = "root";  
$password = "";      
$dbname = "pulsemind";

// connection to database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 
    $temp = $_POST['temperature'];
    $heartrate = $_POST['heart_rate'];
    $stressindex = $_POST['stress_index'];

    // create SQL query
    $stmt = $conn->prepare("INSERT INTO esp32 (temp, heartrate, stressindex) VALUES (?, ?, ?)");
    
    //
    $stmt->bind_param("ddd", $temp, $heartrate, $stressindex);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Data inserted successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
    
} else {
    echo "Invalid request method";
}
?>
