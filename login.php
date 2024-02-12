<?php
// Database connection details
$host = "localhost";
$username = "root";
$password = "";
$database = "restaurant";

// Create a connection to the database
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables to store login status and error message
$loginStatus = "";
$error = "";

// Process the login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Prepare and execute the SQL query to check if email exists and password matches
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Email exists, fetch the user data
        $row = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $row["password"])) {
            // Password matches, login successful
            $loginStatus = "Login successful!";
            // Redirect to book.html after 3 seconds
            header("refresh:3;url=book.html");
            exit; // Ensure that subsequent code is not executed after the redirect
        } else {
            // Password does not match
            $error = "Incorrect password, please try again.";
        }
    } else {
        // Email does not exist
        $error = "Email not found, please try again.";
    }
}

// Close the database connection
$conn->close();
?>
