<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "pardhu14225"; // Update with your MySQL password
$dbname = "fellowship_portal";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the roll number from the URL
$rollno = isset($_GET['rollno']) ? $_GET['rollno'] : null;

if ($rollno) {
    // Fetch the student details using the roll number
    $sql = "SELECT * FROM students WHERE rollno = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $rollno);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
    } else {
        echo "No student found with the given Roll Number.";
        exit();
    }
} else {
    echo "No Roll Number provided.";
    exit();
}

// Handle form submission (approve/reject student)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $approval_status = $_POST['approval_status'];
    $supervisor_remarks = $_POST['supervisor_remarks'];

    // Update the approval status and remarks in the database
    $update_sql = "UPDATE students SET appr_by_supervisor = ?, supervisor_remarks = ? WHERE rollno = ?";
    $stmt_update = $conn->prepare($update_sql);
    $stmt_update->bind_param("sss", $approval_status, $supervisor_remarks, $rollno);
    if ($stmt_update->execute()) {
        echo "Student approval status updated successfully!";
    } else {
        echo "Error updating the status: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details</title>
    <link rel="icon" href="images/iitp_symbol.png" type="image/png">
    <!-- <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            color: #1f94ca;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        input,
        textarea,
        select {
            margin-bottom: 20px;
            padding: 10px;
            font-size: 16px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        textarea {
            resize: vertical;
            height: 100px;
        }

        input[type="submit"] {
            background-color: #1f94ca;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 18px;
        }

        input[type="submit"]:hover {
            background-color: #0a5375;
        }

        .form-group {
            margin-bottom: 15px;
        }
    </style> -->

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .header {
            display: flex;
            align-items: center;
            background-color: #1f94ca;
            padding: 10px;
        }

        .header img {
            height: 70px;
            width: 70px;
        }

        .header h1 {
            color: white;
            margin-left: 20px;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .student-details {
            max-width: 800px;
            margin: auto;
            background-color: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .student-details h2 {
            text-align: center;
            color: #1f94ca;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f9f9f9;
        }

        .form-group select {
            padding: 8px;
            border-radius: 4px;
            background-color: #f1f1f1;
        }

        .logout {
            text-align: center;
            margin-top: 80px;
        }

        .logout a {
            padding: 10px 20px;
            background-color: #1f94ca;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        .logout a:hover {
            background-color: #0d7ba5;
        }

        .submit {
            margin-top: 30px;
        }

        .submit a {
            padding: 10px 30px;
            text-align: center;
            background-color: #0d8c2f;
            color: white;
            border-radius: 4px;
            text-decoration: none;
            border: 2px;
        }

        .submit a:hover {
            background-color: #0b6e25;
        }

        .form-group select,
        .form-group input[type="date"] {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 8px;
            width: 100%;
        }

        .form-group select:focus,
        .form-group input[type="date"]:focus {
            outline: none;
            border-color: #1f94ca;
        }
    </style>
</head>

<body>

    <div class="header">
        <img src="/images/iitp_symbol.png" alt="Logo">
        <h1>Student Fellowship Portal</h1>
    </div>
    <div class="container">
        <h1>Student Details</h1>
        <form method="POST">
            <div class="form-group">
                <label for="fname">First Name:</label>
                <input type="text" name="fname" id="fname" value="<?php echo $student['fname']; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="lname">Last Name:</label>
                <input type="text" name="lname" id="lname" value="<?php echo $student['lname']; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="rollno">Roll Number:</label>
                <input type="text" name="rollno" id="rollno" value="<?php echo $student['rollno']; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="year">Year:</label>
                <input type="text" name="year" id="year" value="<?php echo $student['year']; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="course">Course:</label>
                <input type="text" name="course" id="course" value="<?php echo $student['course']; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="department">Department:</label>
                <input type="text" name="department" id="department" value="<?php echo $student['department']; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="employee_name">Guide Name:</label>
                <input type="text" name="employee_name" id="employee_name" value="<?php echo $student['employee_name']; ?>" readonly>
            </div>

        </form>
        <form method="POST" action="supervisor_approve.php">
            <div class="form-group">
                <label for="approval_status">Approval Status:</label>
                <select name="approval_status" id="approval_status">
                    <option value="YES" <?php if ($student['appr_by_supervisor'] == 'YES') echo 'selected'; ?>>Approve</option>
                    <option value="REJECTED" <?php if ($student['appr_by_supervisor'] == 'REJECTED') echo 'selected'; ?>>Reject</option>
                </select>
            </div>
            <div class="form-group">
                <label for="supervisor_remarks">Remarks:</label>
                <textarea name="supervisor_remarks" id="supervisor_remarks" placeholder="Enter your remarks"><?php echo $student['supervisor_remarks']; ?></textarea>
            </div>
            <div class="form-group">
                <button type="submit" style="background-color: #1f94ca; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;" onclick="return confirm('Are you sure to submit?')">Submit</button>
            </div>
        </form>

        <div class="logout">
            <a href="supervisor.php">Back to Home</a>
        </div>
    </div>
</body>

</html>