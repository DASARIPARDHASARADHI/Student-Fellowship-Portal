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

// Check if it's an AJAX request to get department or employee names
if (isset($_GET['departments'])) {
    // Fetch unique department names for the first dropdown
    $query = "SELECT DISTINCT department FROM employees ORDER BY department ASC";
    $result = $conn->query($query);
    $departments = [];

    while ($row = $result->fetch_assoc()) {
        $departments[] = $row['department'];
    }
    echo json_encode($departments);
    exit();
}

if (isset($_GET['department'])) {
    // Fetch employee names based on department
    $department = $conn->real_escape_string($_GET['department']);
    $query = "SELECT name FROM employees WHERE department = ? ORDER BY name ASC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $department);
    $stmt->execute();
    $result = $stmt->get_result();
    $employees = [];

    while ($row = $result->fetch_assoc()) {
        $employees[] = $row['name'];
    }
    echo json_encode($employees);
    exit();
}

// Check if form is submitted using POST request for registration
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $fname = $conn->real_escape_string($_POST['fname']);
    $lname = $conn->real_escape_string($_POST['lname']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $rollno = $conn->real_escape_string($_POST['rollno']);
    $webmail = $conn->real_escape_string($_POST['webmail']);
    $year = $conn->real_escape_string($_POST['year']);
    $course = $conn->real_escape_string($_POST['course']);
    $department = $conn->real_escape_string($_POST['firstDropdown']);  // Department from first dropdown
    $employee_name = $conn->real_escape_string($_POST['secondDropdown']);  // Employee from second dropdown
    $account = $conn->real_escape_string($_POST['account']);
    $bank = $conn->real_escape_string($_POST['bank']);
    $ifsc = $conn->real_escape_string($_POST['ifsc']);
    $doj = $conn->real_escape_string($_POST['doj']);
    $remarks = $conn->real_escape_string($_POST['remarks']);
    $office_order = $conn->real_escape_string($_POST['office_order']);
    $doo = $conn->real_escape_string($_POST['doo']);
    $enhancement = $conn->real_escape_string($_POST['enhancement']);
    $doe = $conn->real_escape_string($_POST['doe']);

    // Extract username from webmail (part before '@')
    $username = explode('@', $webmail)[0];

    // Hash the password for security
    $pwd = password_hash($_POST['pwd'], PASSWORD_BCRYPT);

    // SQL query to insert data
    $sql = "INSERT INTO students (fname, lname, gender, rollno, webmail, year, course, department, employee_name, account, bank, ifsc, doj, username, password, remarks, office_order, doo, enhancement, doe)
            VALUES ('$fname', '$lname', '$gender', '$rollno', '$webmail', '$year', '$course', '$department', '$employee_name', '$account', '$bank', '$ifsc', '$doj', '$username', '$pwd', '$remarks', '$office_order', '$doo', '$enhancement', '$doe')";

    // Execute query and check if successful
    if ($conn->query($sql) === TRUE) {
        // Display the generated username to the user
        echo "<h2>Registration successful!</h2>";
        echo "<p>Your username is: <strong>$username</strong></p>";
        echo "<a href='stud_login.php'>Login Here</a>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close connection
$conn->close();
