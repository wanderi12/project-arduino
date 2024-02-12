<?php
// Database connection details
$host = "localhost";
$username = "root";
$password = "";
$database = "ryle";

// Create a connection to the database
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables for error messages
$emailErrorMessage = "";
$passwordErrorMessage = "";

// Process the registration form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input
    $fullName = $_POST["Name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];

    // Validate and sanitize user input
    $fullName = trim($fullName);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    // Check if the email is already registered
    $checkEmailQuery = "SELECT * FROM water WHERE email = ?";
    $stmt = $conn->prepare($checkEmailQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $emailErrorMessage = "Email is already registered.";
    } else {
        // Check if passwords match
        if ($password !== $confirmPassword) {
            $passwordErrorMessage = "Passwords do not match.";
        } else {
            // Hash the password for security
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Prepare and execute the SQL query to insert data into the users table
            $insertQuery = "INSERT INTO water (name, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("sss", $fullName, $email, $hashedPassword);

            if ($stmt->execute()) {
                // Redirect to success page
                header("Location: login1.php");
                exit();
            } else {
                // Log the error
                error_log("Error: " . $stmt->error);

                // Display a user-friendly message
                echo "Registration failed. Please try again later.";
            }
        }
    }
}

// Close the database connection
$conn->close();
?>
