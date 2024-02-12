<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "profile";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process form submission and insert data into the database
    $userName = $_POST["user-name"];
    $userEmail = $_POST["user-email"];
    $userContact = $_POST["user-contact"];
    $userAddress = $_POST["user-address"];

    // Insert data into the database
    $sql = "INSERT INTO `user_profile` (`name`, `email`, `contact`, `address`) 
            VALUES ('$userName', '$userEmail', '$userContact', '$userAddress')";

    if ($conn->query($sql) !== TRUE) {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch the most recent data from the database
$sql = "SELECT * FROM `user_profile` ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="new.css">
    <title>Ryles Water Company</title>
</head>
<body>
    <header>
        <h1>Ryles Water Company</h1>
        <nav>
            <ul>
                <li><a href="applicationnew.html">Application</a></li>
                <li><a href="paymentnew.html">Payment</a></li>
                <li><a href="guide.html">Guide</a></li>
                <li><a href="profilenew.php">Profile</a></li>
            </ul>
        </nav>
    </header>

    <div class="profile-container">
        <div class="profile-header">
            <h1>User Profile</h1>
        </div>

        <!-- Display the most recent fetched data from the database -->
        <?php
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo "<div class='profile-content'>";
            echo "<div class='profile-picture'>";
            $profilePicture = isset($row["profile_picture"]) ? $row["profile_picture"] : 'default-profile-pic.jpg';
            echo "<img id='user-pic' src='uploads/$profilePicture' alt='User Profile Picture' height='100px' width='100px'>";
            echo "</div>";

            echo "<div class='user-details'>";
            echo "<p><strong>Name:</strong> " . $row["name"] . "</p>";
            echo "<p><strong>Email:</strong> " . $row["email"] . "</p>";
            echo "<p><strong>Contact:</strong> " . $row["contact"] . "</p>";
            echo "<p><strong>Address:</strong> " . $row["address"] . "</p>";
            echo "</div>";

            echo "</div>";
        } else {
            echo "<p>No user profiles found.</p>";
        }
        ?>
    </div>

    <script>
        function previewImage(input) {
            const userPic = document.getElementById('user-pic');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    userPic.src = e.target.result;
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

    <footer>
        <p>&copy; 2024 Ryles Water Company. All rights reserved.</p>
    </footer>
</body>
</html>

<?php
$conn->close();
?>
