<?php
// Start session to track logged-in user
session_start();

// Check if the user is logged in
if (!isset($_SESSION['logged_in_user'])) {
    // If not, redirect to login page
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

// Fetch student details using the session username
$uname = $_SESSION['logged_in_user'];
$stmt = $conn->prepare("SELECT fname, lname, application_sent, appr_by_fac_adv, appr_by_dept_offc, appr_by_hod, amount_appr, amount FROM students WHERE webmail=?");
$stmt->bind_param("s", $uname);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();
} else {
    echo "No student data found.";
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student</title>
    <link rel="icon" href="iitp_symbol.png" type="image/png">
    <style>
        div.header {
            display: flex;
            flex-direction: row;
            align-items: center;
            background-color: #1f94ca;
        }

        body {
            margin: 0;
        }

        ul {
            list-style-type: none;
            width: 150px;
            height: 500px;
            padding: 0;
            margin: 0;
            background-color: #0a5375;
            border-top: solid 1px #042f42;
        }

        li {
            padding: 8px 12px;
            text-align: center;
            background-color: #0a5375;
            font-size: large;
        }

        li a {
            color: rgb(255, 255, 255);
            text-decoration: none;
            display: block;
        }

        li:hover,
        li:active {
            padding: 8px 12px;
            text-align: center;
            background-color: #1192ce;
        }

        #main {
            display: flex;
            overflow: auto;
        }

        #status_table {
            display: flex;
            border: solid 1px;
            width: 100%;
            flex-direction: column;
            align-items: center;
        }

        table {
            border: solid black 1px;
            border-collapse: collapse;
            width: 500px;
        }

        tr:nth-child(even) {
            background-color: #88c7e4;
        }

        th,
        td {
            border: solid black 1px;
            text-align: center;
            padding: 10px;
        }
    </style>
</head>

<body>

    <div class="header">
        <div style="align-self: center; margin:5px; width: 100px;">
            <img style="height: 90px; width: 90px;" src="iitp_symbol.png">
        </div>

        <div style="width: 100%; margin-left: 100px; text-align: center;">
            <h1 style="font-size: 35px; color: white;">Student Fellowship Portal</h1>
        </div>
    </div>

    <div id="main">
        <div>
            <ul>
                <li><a href="student_profile.php">Profile</a></li>
                <li class="current"><a href="student.php">Status</a></li>
                <li><a href="stud_claims.php">Previous Claims</a></li>
                <li><a href="stud_logout.php">Logout</a></li>
                <hr>
            </ul>
        </div>

        <div id="status_table">
            <div>
                <h2>Welcome <?php echo $student['fname'] . ' ' . $student['lname']; ?>!</h2>
            </div>
            <div style="text-align: center; margin-top: 0px;">
                <h1>Application Status</h1>
            </div>

            <div style="margin-top: 20px;">
                <table style="width: 800px;">
                    <tr>
                        <th style="width:100px">SI No.</th>
                        <th>Action</th>
                        <th>Status</th>
                    </tr>
                    <!-- <tr>
                        <td style="width:100px">1.</td>
                        <td>Application Sent</td>
                        <td><?php echo ($student['application_sent'] == 'YES') ? 'Yes' : 'No'; ?></td>
                    </tr> -->
                    <tr>
                        <td style="width:100px">1.</td>
                        <td>Approved By Faculty Advisor/Supervisor</td>
                        <td><?php echo ($student['appr_by_fac_adv'] == 'YES') ? 'Yes' : 'No'; ?></td>
                    </tr>
                    <tr>
                        <td style="width:100px">2.</td>
                        <td>Approved By Department Office</td>
                        <td><?php echo ($student['appr_by_dept_offc'] == 'YES') ? 'Yes' : 'No'; ?></td>
                    </tr>
                    <tr>
                        <td style="width:100px">3.</td>
                        <td>Approved By HOD</td>
                        <td><?php echo ($student['appr_by_hod'] == 'YES') ? 'Yes' : 'No'; ?></td>
                    </tr>
                    <tr>
                        <td style="width:100px">4.</td>
                        <td>Amount Approval</td>
                        <td><?php echo ($student['amount_appr'] == 'YES') ? 'Yes' : 'No'; ?></td>
                    </tr>
                    <tr>
                        <td style="width:100px">5.</td>
                        <td>Amount</td>
                        <td><?php echo ($student['amount'] != NULL) ? $student['amount'] : 'NA'; ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>

</html>