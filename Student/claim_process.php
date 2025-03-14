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

$uname = $_SESSION['logged_in_user']; // Assuming logged-in user username is stored in the session

// Get the form data and sanitize
$claimed_month = $conn->real_escape_string($_POST['claimed_month']);
$claimed_amount = $conn->real_escape_string($_POST['claimed_amount']);
$claimed = $conn->real_escape_string($_POST['claimed']);

// Extract month and year from the claimed_month input
$claimed_month_year = date('Y-m', strtotime($claimed_month));

// Get the roll number based on the username (from the same session)
$stmt = $conn->prepare("SELECT rollno FROM students WHERE webmail = ?");
$stmt->bind_param("s", $uname);
$stmt->execute();
$stmt->bind_result($rollno);
$stmt->fetch();
$stmt->close();

// Check if the user has already claimed for the selected month in the students table
$stmt = $conn->prepare("SELECT claimed_month FROM students WHERE rollno = ? AND DATE_FORMAT(claimed_month, '%Y-%m') = ?");
$stmt->bind_param("ss", $rollno, $claimed_month_year);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<script>alert('You have already claimed for this month.'); window.location.href = 'student_profile.php';</script>";
    exit();
}
$stmt->close();

// Check if the user has already submitted a claim in the students_claims table
$stmt = $conn->prepare("SELECT claimed_month FROM students_claims WHERE rollno = ? AND DATE_FORMAT(claimed_month, '%Y-%m') = ?");
$stmt->bind_param("ss", $rollno, $claimed_month_year);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<script>alert('You have already submitted a claim for this month.'); window.location.href = 'student_profile.php';</script>";
    exit();
}
$stmt->close();

// Update the student's claim information in the students table using NOW() for claimed_time
$stmt = $conn->prepare("UPDATE students SET claimed_month = ?, claimed_amount = ?, claimed = ?, claimed_time = NOW() WHERE rollno = ?");
$stmt->bind_param("ssss", $claimed_month, $claimed_amount, $claimed, $rollno);

if ($stmt->execute()) {
    // Retrieve the student's fname and lname for insertion into students_claims table
    $stmt2 = $conn->prepare("SELECT fname, lname FROM students WHERE rollno = ?");
    $stmt2->bind_param("s", $rollno);
    $stmt2->execute();
    $stmt2->bind_result($fname, $lname);
    $stmt2->fetch();
    $stmt2->close();

    // Insert claim data into the students_claims table using NOW() for claimed_time
    $stmt3 = $conn->prepare("INSERT INTO students_claims (fname, lname, rollno, claimed_month, claimed_amount, claimed_time) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt3->bind_param("sssss", $fname, $lname, $rollno, $claimed_month, $claimed_amount);

    if ($stmt3->execute()) {
        echo "<script>alert('Claim submitted successfully!'); window.location.href = 'student.php';</script>";
    } else {
        echo "Error in students_claims: " . $stmt3->error;
    }
    $stmt3->close();
} else {
    echo "Error in students: " . $stmt->error;
}

$conn->close();
