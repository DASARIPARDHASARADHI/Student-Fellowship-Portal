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
    $hod_remarks = $_POST['hod_remarks'];

    // Update the approval status and remarks in the database
    $update_sql = "UPDATE students SET appr_by_hod = ?, hod_remarks = ? WHERE rollno = ?";
    $stmt_update = $conn->prepare($update_sql);
    $stmt_update->bind_param("sss", $approval_status, $hod_remarks, $rollno);
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
    <link rel="icon" href="/images/iitp_symbol.png" type="image/png">
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
                <label for="gender">Gender:</label>
                <input type="text" name="gender" id="gender" value="<?php echo $student['gender']; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="webmail">Roll Number:</label>
                <input type="text" name="webmail" id="webmail" value="<?php echo $student['webmail']; ?>" readonly>
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
            <div class="form-group">
                <label for="office_order">Office Order:</label>
                <input type="text" name="office_order" id="office_order" value="<?php echo $student['office_order']; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="doo">Date of Office Order:</label>
                <input type="text" name="doo" id="employee_name" value="<?php echo $student['doo']; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="enhancement">Enhancement:</label>
                <input type="text" name="enhancement" id="enhancement" value="<?php echo $student['enhancement']; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="doe">Date of Enhancement:</label>
                <input type="text" name="doe" id="doe" value="<?php echo $student['doe']; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="claimed_month">Claimed Date:</label>
                <input type="text" name="claimed_month" id="claimed_month" value="<?php
                                                                                    // Check if claimed_month is not empty
                                                                                    if (!empty($student['claimed_month'])) {
                                                                                        // Create a DateTime object from the date
                                                                                        $date = new DateTime($student['claimed_month']);

                                                                                        // Format the date to display the full month name and year
                                                                                        echo $date->format('j F Y'); // 'F' for full month name, 'Y' for 4-digit year
                                                                                    } else {
                                                                                        echo "No date provided";
                                                                                    }
                                                                                    ?>" readonly>
            </div>
            <div class="form-group">
                <label for="claimed_amount">Claimed Amount:</label>
                <input type="text" name="claimed_amount" id="claimed_amount" value="<?php echo $student['claimed_amount']; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="claimed_time">Claimed Time:</label>
                <input type="text" name="claimed_time" id="claimed_time" value="<?php echo $student['claimed_time']; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="absent_days">Number of Absent Days:</label>
                <input type="text" name="absent_days" id="absent_days" value="<?php echo $student['absent_days']; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="approved_amount">Approved Amount: </label>
                <input type="text" name="approved_amount" id="approved_amount" value="<?php echo $student['approved_amount']; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="dept_offc_remarks">Department Office Remarks:</label>
                <input type="text" name="dept_offc_remarks" id="dept_offc_remarks" value="<?php echo $student['dept_offc_remarks']; ?>" readonly>
            </div>
        </form>

        <h2 style="color:brown; text-align:center; margin-top:50px">Approval Submission</h2>
        <form action="hod_approve.php" method="post">
            <input type="hidden" name="rollno" value="<?php echo $student['rollno']; ?>">
            <div class="form-group">
                <label for="approval_status">Approval Status:</label>
                <select name="approval_status" id="approval_status">
                    <option value="YES" <?php if ($student['appr_by_hod'] == 'YES') echo 'selected'; ?>>Approve</option>
                    <option value="REJECTED" <?php if ($student['appr_by_hod'] == 'REJECTED') echo 'selected'; ?>>Reject</option>
                </select>
            </div>
            <div class="form-group">
                <label for="hod_remarks">Remarks:</label>
                <textarea name="hod_remarks" id="hod_remarks" placeholder="Enter your remarks" rows="5" cols="30"></textarea>
            </div>

            <!-- <button type="button" style="background-color: #1b8a0c; color: white; padding: 10px 28px; border: none; border-radius: 4px; cursor: pointer;" onclick="return confirm('Are you want to edit?')">Edit</button> -->

            <input type="submit" value="Submit" style="background-color: #1f94ca; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;" onclick="return confirm('Are you sure to submit?')">

        </form>

        <div class="logout">
            <a href="hod.php">Back to Home</a>
        </div>
    </div>
</body>

</html>