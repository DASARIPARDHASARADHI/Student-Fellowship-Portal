<?php
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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rollno = $conn->real_escape_string($_POST['rollno']); // Use rollno instead of student_id
    $approve = $conn->real_escape_string($_POST['approval_status']);
    $remarks = $conn->real_escape_string($_POST['fac_adv_remarks']);
    $approval_time = date("Y-m-d H:i:s"); // Capture the current time as approval time

    // Update the approval status, remarks, and approval time in the students table
    $sql = "UPDATE students 
            SET appr_by_fac_adv = '$approve', 
                fac_adv_remarks = '$remarks', 
                fac_adv_approv_time = '$approval_time' 
            WHERE rollno = '$rollno'"; // Use rollno for identification

    if ($conn->query($sql) === TRUE) {
        echo "Approval status, remarks, and approval time updated successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Close connection
$conn->close();
