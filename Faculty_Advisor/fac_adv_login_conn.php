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
    $employee_id = $conn->real_escape_string($_POST['employee_id']);
    $pwd = $conn->real_escape_string($_POST['pwd']); // No need to hash since it's plain text

    // Fetch employee details from the database
    $sql = "SELECT * FROM employees WHERE employee_id = ? AND fac_adv = 'YES'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Directly compare the password (since it's stored as plain text)
        if ($pwd === $row['password']) {
            // Password is correct, start session and redirect to fac_adv.php
            session_start();
            $_SESSION['employee_id'] = $row['employee_id'];
            $_SESSION['name'] = $row['name']; // Assuming the employee name is stored
            header("Location: fac_adv.php");
            exit();
        } else {
            echo "<p>Invalid Password. Please try again.</p>";
        }
    } else {
        echo "<p>Invalid Employee ID or you are not authorized as a Faculty Advisor.</p>";
    }
}

// Close the connection
$conn->close();
