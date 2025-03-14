<?php


// Database configuration
$servername = "localhost";
$username = "root";
$password = "pardhu14225"; // Update to your MySQL password
$dbname = "fellowship_portal";

session_start();
if (!isset($_SESSION['fac_adv_id'])) {
    echo "<script>
            alert('Please log in.');
            window.location.href = 'fac_adv_login.php';
          </script>";
    exit();
}

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
    $fac_adv_remarks = $_POST['fac_adv_remarks'];

    // $sql = "UPDATE students SET 
    // appr_by_fac_adv = ?, 
    // fac_adv_remarks = ?, 
    // fac_adv_approv_time = ? 
    // WHERE rollno = ?";

    // Prepare the SQL update statement
    $stmt = $conn->prepare("UPDATE students SET appr_by_fac_adv = ?, fac_adv_remarks = ?, fac_adv_approv_time = NOW() WHERE rollno = ?");
    $stmt->bind_param("sss", $approval_status, $fac_adv_remarks, $rollno);

    // Execute the query
    if ($stmt->execute()) {
        echo "<script>
                alert('Approval status updated successfully.');
                window.location.href = 'fac_adv.php';
              </script>";
    } else {
        echo "<script>
                alert('Error updating approval. Please try again.');
                window.location.href = 'fac_adv.php';
              </script>";
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
