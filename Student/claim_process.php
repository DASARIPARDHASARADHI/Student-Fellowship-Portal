<?php
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

// Check if the user is logged in
if (!isset($_SESSION['logged_in_user'])) {
    header("Location: stud_login.php");
    exit();
}

$uname = $_SESSION['logged_in_user'];

// Get the form data
$claimed_month = $_POST['claimed_month'];
$claimed_amount = $_POST['claimed_amount'];
$claimed = $_POST['claimed'];

// Check if the user has already claimed for the selected month in the students table
$stmt = $conn->prepare("SELECT claimed_month FROM students WHERE username = ? AND claimed_month = ?");
$stmt->bind_param("ss", $uname, $claimed_month);
$stmt->execute();
$result = $stmt->get_result();

// If a claim already exists for this month, show an alert and prevent the new claim
if ($result->num_rows > 0) {
    echo "<script>alert('You have already claimed for this month.'); window.location.href = 'student_profile.php';</script>";
    exit();
}
$stmt->close();

// Update the student's claim information in the students table
$stmt = $conn->prepare("UPDATE students SET claimed_month = ?, claimed_amount = ?, claimed = ? WHERE username = ?");
$stmt->bind_param("ssss", $claimed_month, $claimed_amount, $claimed, $uname);

if ($stmt->execute()) {
    echo "<script>alert('Claim submitted successfully!'); window.location.href = 'student_profile.php';</script>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
