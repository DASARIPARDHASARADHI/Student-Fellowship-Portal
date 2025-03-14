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

// Check if the form is submitted and validate necessary fields
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['selected_students'], $_POST['approval_status'], $_POST['dept_offc_remarks'])) {
    $selected_students = $_POST['selected_students']; // Array of selected roll numbers
    $approval_statuses = $_POST['approval_status']; // Array of approval statuses keyed by roll number
    $remarks = $_POST['dept_offc_remarks']; // Array of remarks keyed by roll number
    $approval_time = date("Y-m-d H:i:s"); // Capture the current time as approval time

    // Prepare the SQL statement for updating each selected student
    $sql = "UPDATE students SET 
            appr_by_dept_offc = ?, 
            dept_offc_remarks = ?, 
            dept_offc_approv_time = ? 
            WHERE rollno = ?";

    $stmt = $conn->prepare($sql);

    // Check if the statement prepared successfully
    if ($stmt) {
        // Loop through selected students and apply updates
        foreach ($selected_students as $rollno) {
            $approval = $approval_statuses[$rollno] ?? ''; // Use empty string as fallback
            $remark = $remarks[$rollno] ?? ''; // Use empty string as fallback

            // Bind and execute statement for each selected student
            $stmt->bind_param("ssss", $approval, $remark, $approval_time, $rollno);
            if (!$stmt->execute()) {
                echo "Error updating record for Roll No $rollno: " . $stmt->error . "<br>";
            }
        }

        echo "Selected changes updated successfully!";
    } else {
        echo "Error preparing statement: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "No students selected or missing required fields.";
}

// Close connection
$conn->close();
