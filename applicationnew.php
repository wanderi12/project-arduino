<?php
error_reporting(E_ALL);

// Replace these values with your MySQL database credentials
$servername = "localhost";
$username = "root";
$password = "";
$database = "applicationnew";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $idFile = $_FILES["idFile"]["name"];
    $pinCertificate = $_FILES["pinCertificate"]["name"];
    $leaseAgreement = $_FILES["leaseAgreement"]["name"];

    // Move uploaded files to a desired directory
    $uploadDirectory = "htdocs/project/";
    move_uploaded_file($_FILES["idFile"]["tmp_name"], $uploadDirectory . $idFile);
    move_uploaded_file($_FILES["pinCertificate"]["tmp_name"], $uploadDirectory . $pinCertificate);
    move_uploaded_file($_FILES["leaseAgreement"]["tmp_name"], $uploadDirectory . $leaseAgreement);

    // Insert data into the table
    $sql = "INSERT INTO apptb (idFile, pinCertificate, leaseAgreement) VALUES ('$idFile', '$pinCertificate', '$leaseAgreement')";

    if ($conn->query($sql) === TRUE) {
        echo "Data inserted successfully.";
    } else {
        echo "Error inserting data: " . $conn->error;
    }
}

// Close the connection
$conn->close();
?>
