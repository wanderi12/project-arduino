<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "ryles_water_company";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to safely handle file uploads
function uploadFile($file, $uploadDir)
{
    $target_file = $uploadDir . basename($file["name"]);

    // Check if the directory exists, create it if not
    if (!file_exists($uploadDir) && !is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Move the uploaded file
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return $target_file;
    } else {
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // File upload directory (use absolute path)
    $uploadDir = __DIR__ . '/uploads/';

    // File paths in the database
    $id_passport_path = uploadFile($_FILES["idFile"], $uploadDir);
    $pin_certificate_path = uploadFile($_FILES["pinCertificate"], $uploadDir);
    $lease_agreement_path = uploadFile($_FILES["leaseAgreement"], $uploadDir);

    // Insert data into the database
    $sql = "INSERT INTO `applications` (`id_passport_path`, `pin_certificate_path`, `lease_agreement_path`) 
            VALUES ('$id_passport_path', '$pin_certificate_path', '$lease_agreement_path')";

    if ($conn->query($sql) === TRUE) {
        echo "Application submitted successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
