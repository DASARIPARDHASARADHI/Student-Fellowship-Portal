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
    $dept_offc_remarks = $_POST['dept_offc_remarks'];
    $absent_days = $_POST['absent_days'];
    $approved_amount = $_POST['approved_amount'];

    // Prepare the SQL update statement
    $stmt = $conn->prepare("UPDATE students SET absent_days = ?, approved_amount = ?, appr_by_dept_offc = ?, dept_offc_remarks = ?, dept_offc_approv_time = NOW() WHERE rollno = ?");
    $stmt->bind_param("sssss", $absent_days, $approved_amount, $approval_status, $dept_offc_remarks, $rollno);

    // Execute the query
    if ($stmt->execute()) {
        echo "<script>
                alert('Approval status updated successfully.');
                window.location.href = 'dept_offc.php';
              </script>";
    } else {
        echo "<script>
                alert('Error updating approval. Please try again.');
                window.location.href = 'dept_offc.php';
              </script>";
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
