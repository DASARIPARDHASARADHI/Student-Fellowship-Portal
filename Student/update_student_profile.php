<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
session_start();

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['logged_in_user'])) {
    header("Location: stud_login.php");
    exit();
}

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

// Get webmail and updated data from POST request
$webmail = $_POST['webmail'] ?? null;
if (!$webmail) {
    die("Webmail is required.");
}

$update_fields = [];
$update_values = [];
$update_types = ""; // For bind_param types

// Function to add fields to update query if they are set
function addToUpdate(&$update_fields, &$update_values, &$update_types, $field, $value, $type = "s")
{
    if (!empty($value) || $value === "0") { // Prevents updating empty fields
        $update_fields[] = "$field = ?";
        $update_values[] = $value;
        $update_types .= $type;
    }
}

// Populate fields only if they are set in $_POST
addToUpdate($update_fields, $update_values, $update_types, 'rollno', $_POST['rollno'] ?? null); // Roll Number
addToUpdate($update_fields, $update_values, $update_types, 'fname', $_POST['fname'] ?? null);
addToUpdate($update_fields, $update_values, $update_types, 'lname', $_POST['lname'] ?? null);
addToUpdate($update_fields, $update_values, $update_types, 'gender', $_POST['gender'] ?? null);
addToUpdate($update_fields, $update_values, $update_types, 'course', $_POST['course'] ?? null);
addToUpdate($update_fields, $update_values, $update_types, 'department', $_POST['firstDropdown'] ?? null);
addToUpdate($update_fields, $update_values, $update_types, 'employee_name', $_POST['secondDropdown'] ?? null);
addToUpdate($update_fields, $update_values, $update_types, 'account', $_POST['account'] ?? null);
addToUpdate($update_fields, $update_values, $update_types, 'bank', $_POST['bank'] ?? null);
addToUpdate($update_fields, $update_values, $update_types, 'ifsc', $_POST['ifsc'] ?? null);
addToUpdate($update_fields, $update_values, $update_types, 'doj', $_POST['doj'] ?? null);
addToUpdate($update_fields, $update_values, $update_types, 'office_order', $_POST['office_order'] ?? null);
addToUpdate($update_fields, $update_values, $update_types, 'doo', $_POST['doo'] ?? null);
addToUpdate($update_fields, $update_values, $update_types, 'enhancement', $_POST['enhancement'] ?? null);
addToUpdate($update_fields, $update_values, $update_types, 'doe', $_POST['doe'] ?? null);
addToUpdate($update_fields, $update_values, $update_types, 'remarks', $_POST['remarks'] ?? null);

// Check if password fields are provided and match
if (!empty($_POST['pwd']) && !empty($_POST['cnf_pwd']) && $_POST['pwd'] === $_POST['cnf_pwd']) {
    $password = password_hash($_POST['pwd'], PASSWORD_BCRYPT);
    addToUpdate($update_fields, $update_values, $update_types, 'password', $password);
}

// Construct the dynamic SQL update query
if (!empty($update_fields)) {
    $sql = "UPDATE students SET " . implode(', ', $update_fields) . " WHERE webmail = ?";
    $update_values[] = $webmail; // Add webmail to the end for the WHERE clause
    $update_types .= "s"; // Assuming webmail is a string

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param($update_types, ...$update_values);

    if ($stmt->execute()) {
        echo "Profile updated successfully.<br>";
        echo "<a href='student_profile.php'>Go to Student Profile</a>";
        exit();
    } else {
        echo "Error updating profile: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "No fields to update.";
}

$conn->close();
