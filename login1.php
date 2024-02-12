<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

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

// Initialize variables
$error = "";

// Process the login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input (sanitize to prevent SQL injection)
    $email = $conn->real_escape_string($_POST["email"]);
    $password = $_POST["password"];

    // Prepare and execute the SQL query to check user credentials
    $sql = "SELECT * FROM water WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result) {
            // Check if email exists
            if ($result->num_rows > 0) {
                // Email exists, fetch the user data
                $row = $result->fetch_assoc();

                // Verify password using password_verify
                if (password_verify($password, $row["password"])) {
                    // Password matches, redirect to new.html
                    header("Location: /project/new.html");
                    exit;
                } else {
                    // Password does not match
                    $error = "Incorrect password, please try again.";

                }
            } else {
                // Email does not exist
                $error = "Email not found, please try again.";
            }
        } else {
            // SQL query execution failed
            $error = "Error executing SQL query: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        // Statement preparation failed
        $error = "Error preparing SQL statement: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link rel="stylesheet" type="text/css" href="My.css">
</head>
<body>
    <header>
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-2"></div>
        
                <div class="col-lg-12 login-key">
                    <i class="fa fa-key" aria-hidden="true"></i>
                </div>

                <div class="col-lg-12 login-title">
                    ADMIN PANEL
                </div>
</header>
<main>
                <div class="col-lg-12 login-form">
                    <form action="login1.php" method="POST">
                        <div class="form-group">
                            <label class="form-control-label" id="username">USERNAME</label>
                            <input type="text" class="form-control" name="email">
                        </div>
                        <div class="form-group">
                            <label class="form-control-label" id="password">PASSWORD</label>
                            <input type="password" class="form-control" name="password">
                        </div>

                        <div class="col-lg-12 loginbttm">
                            <div class="col-lg-6 login-btm login-text">
                                <!-- Error Message -->
                            </div>
                            <div class="col-lg-6 login-btm login-buttons">
                                <button type="submit" class="btn btn-outline-primary" name="loginbtn">LOGIN</button>
                                <!-- Updated link to registerr.html -->
                                <a href="registerr.html" class="btn btn-outline-success"style="color:lightblue;,pointer:cursor;,cursorhover:yellow;">SIGN UP</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-3 col-md-2"></div>
        </div>
    </div>
     <?php echo $error; ?>
 </main>
 <footer>
        <p>&copy; 2024 Ryles Water Company. All rights reserved.</p>
    </footer>
</body>
</html>

