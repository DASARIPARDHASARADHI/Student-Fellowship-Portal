<?php
// Start the session
session_start();

// Database configuration
$servername = "localhost";
$username = "root";
$password = "pardhu14225";
$dbname = "fellowship_portal";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $uname = $_POST['uname'];
    $pwd = $_POST['pwd'];

    // Prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT password FROM students WHERE username=?");
    $stmt->bind_param("s", $uname);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Bind the result
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Verify the password
        if (password_verify($pwd, $hashed_password)) {
            // Store username in session to track login
            $_SESSION['logged_in_user'] = $uname;

            // Redirect to the student profile page
            header("Location: student.php");
            exit();
        } else {
            echo "<div class='result'>Invalid username or password. Please try again.</div>";
        }
    } else {
        echo "<div class='result'>Invalid username or password. Please try again.</div>";
    }

    $stmt->close();
}

$conn->close();
