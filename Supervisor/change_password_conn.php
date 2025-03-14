<?php

session_start();

// Database configuration
$servername = "localhost";
$username = "root";
$password = "pardhu14225"; // Update to your MySQL password
$dbname = "fellowship_portal";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted using POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure the user is logged in
    if (!isset($_SESSION['employee_id'])) {
        echo "You need to be logged in to change your password.";
        exit();
    }

    // Get the user ID from session
    $employee_id = $_SESSION['employee_id'];

    // Hash the new password for security
    $pwd = password_hash($_POST['pwd'], PASSWORD_BCRYPT);

    // SQL query to update the password for the logged-in user
    $sql = "UPDATE employees SET password='$pwd' WHERE id='$employee_id'";

    // Execute query and check if successful
    if ($conn->query($sql) === TRUE) {
        echo "<h2>Password updated successfully!</h2>";
        echo "<a href='supervisor_login.php'>Login Here</a>";
    } else {
        echo "Error updating password: " . $conn->error;
    }
}

// Close connection
$conn->close();
