<?php
$host = "localhost"; // Change if needed
$user = "root";      // Your MySQL username
$password = "pardhu14225";      // Your MySQL password
$dbname = "fellowship_portal";  // Database name

// Create a connection
$conn = new mysqli($host, $user, $password, $dbname, 3306);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connection successful!";
}
