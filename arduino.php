<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "arduino_data";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $flowRate = $_POST['flow_rate'];

    // Insert data into the database
    $sql = "INSERT INTO sensor_data (sensor_value) VALUES ('$flowRate')";

    if ($conn->query($sql) === TRUE) {
        echo "Data inserted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
