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

// Check if it's an AJAX request to get department names
if (isset($_GET['departments'])) {
    $query = "SELECT DISTINCT department FROM employees ORDER BY department ASC";
    $result = $conn->query($query);
    $departments = [];

    while ($row = $result->fetch_assoc()) {
        $departments[] = $row['department'];
    }
    echo json_encode($departments);
    exit();
}

// Function to generate a random password
function generatePassword($length = 8)
{
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
    $password = substr(str_shuffle($chars), 0, $length);
    return $password;
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $department = $conn->real_escape_string($_POST['firstDropdown']);
    $department_code = $conn->real_escape_string($_POST['department_code']);

    // Generate employee ID
    $id_query = "SELECT employee_id FROM employees WHERE department = '$department' ORDER BY employee_id DESC LIMIT 1";
    $id_result = $conn->query($id_query);

    if ($id_result->num_rows > 0) {
        $last_id = $id_result->fetch_assoc()['employee_id'];
        $numeric_part = (int)substr($last_id, -3);
        $new_id = $department_code . str_pad($numeric_part + 1, 3, '0', STR_PAD_LEFT);
    } else {
        $new_id = $department_code . "001";
    }

    // Generate email and password
    $email = strtolower(str_replace(' ', '', $name)) . '@iitp.ac.in';
    $password = generatePassword();
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert data into the database
    $sql = "INSERT INTO employees (name, department, department_code, employee_id, email, password)
            VALUES ('$name', '$department', '$department_code', '$new_id', '$email', '$hashed_password')";

    if ($conn->query($sql) === TRUE) {
        echo "<h2>Registration successful!</h2>";
        echo "<p><strong>Employee ID:</strong> $new_id</p>";
        echo "<p><strong>Email:</strong> $email</p>";
        echo "<p><strong>Password:</strong> $password</p>";
        echo "<a href='dept_offc_login.php' target='_self'>Back To Login</a>"; // Updated to target the same tab
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close connection
$conn->close();
