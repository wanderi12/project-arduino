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

// Initialize variables for error messages
$emailErrorMessage = "";
$passwordErrorMessage = "";

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input (sanitize and validate)
    $fullName = htmlspecialchars(trim($_POST["fullName"]));
    $email = htmlspecialchars(trim($_POST["email"]));
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];

    // Check if the email is already registered
    $checkEmailQuery = "SELECT * FROM users WHERE email = ?";
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
            $insertQuery = "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("sss", $fullName, $email, $hashedPassword);

            if ($stmt->execute()) {
                // Display success message
                echo "Registration successful! Redirecting to index.html...";
                // Redirect to index.html after 3 seconds
                header("refresh:3;url=index.html");
                exit;
            } else {
                echo "Error: Registration failed. Please try again.";
            }
        }
    }
}

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <style>
        /* Your styles remain unchanged */
    </style>
   
</head>
<body>
<div class="form-container">
    <h2>Registration Form</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="fullName">Full Name:</label><br>
        <input type="text" id="fullName" name="fullName" required><br>
        
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br>
        <span class="error-message"><?php echo $emailErrorMessage; ?></span><br>
        
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br>
        
        <label for="confirmPassword">Confirm Password:</label><br>
        <input type="password" id="confirmPassword" name="confirmPassword" required>
        <span style="color: red;"><?php echo $passwordErrorMessage; ?></span><br>
        
        <input type="submit" value="Register">
    </form>
</div>
</body>
</html>
