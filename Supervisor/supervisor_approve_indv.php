<?php


// Database configuration
$servername = "localhost";
$username = "root";
$password = "pardhu14225"; // Update to your MySQL password
$dbname = "fellowship_portal";


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form inputs
    $rollno = $_POST['rollno'];
    $approval_status = $_POST['approval_status'];
    $supervisor_remarks = $_POST['supervisor_remarks'];

    // Prepare the SQL update statement
    $stmt = $conn->prepare("UPDATE students SET appr_by_supervisor = ?, supervisor_remarks = ?, supervisor_approv_time = NOW() WHERE rollno = ?");
    $stmt->bind_param("sss", $approval_status, $supervisor_remarks, $rollno);

    // Execute the query
    if ($stmt->execute()) {
        echo "<script>
                alert('Approval status updated successfully.');
                window.location.href = 'supervisor.php';
              </script>";
    } else {
        echo "<script>
                alert('Error updating approval. Please try again.');
                window.location.href = 'supervisor.php';
              </script>";
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
