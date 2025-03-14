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
$stmt = $conn->prepare("SELECT fname, lname, rollno FROM students WHERE webmail=?");
$stmt->bind_param("s", $uname);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();
    $rollno = $student['rollno']; // Store roll number for later use
} else {
    echo "No student data found.";
    exit();
}

$stmt->close(); // Close statement but keep $conn open for later queries
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Claims</title>
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
            /* border: solid 1px; */
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
    </style>
</head>

<body>
    <div class="header">
        <div style="align-self: center; margin:5px; width: 100px;">
            <img style="height: 90px; width: 90px;" src="iitp_symbol.png">
        </div>

        <div style="width: 100%; text-align: center;">
            <h1 style="font-size: 35px; color: white; text-align:center">Student Fellowship Portal</h1>
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
            <div style="text-align: center;">
                <h2>Welcome <?php echo $student['fname'] . ' ' . $student['lname']; ?>!</h2>
                <h3>Roll Number : <?php echo $student['rollno'] ?></h3>
            </div>
            <div style="text-align: center; margin-top: 0px;">
                <h1>Previous Claims</h1>
            </div>

            <div style="margin-top: 20px;">

                <table style="width: 800px;">
                    <tr>
                        <th>SI No.</th>
                        <th>Claimed Amount</th>
                        <th>Claimed Month</th>
                        <th>Claimed Date</th>
                    </tr>
                    <?php
                    // Query to fetch all claims for this student's roll number ordered by most recent claimed_time
                    $claims_stmt = $conn->prepare("SELECT claimed_amount, claimed_month, claimed_time FROM students_claims WHERE rollno = ? ORDER BY claimed_time DESC");
                    $claims_stmt->bind_param("s", $rollno);
                    $claims_stmt->execute();
                    $claims_result = $claims_stmt->get_result();

                    $si_no = 1; // Serial number
                    while ($claim = $claims_result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $si_no++ . "</td>";
                        echo "<td>" . htmlspecialchars($claim['claimed_amount']) . "</td>";

                        // Format claimed_month to "Month Year" (e.g., October 2024)
                        $date = new DateTime($claim['claimed_month']);
                        echo "<td>" . $date->format('F Y') . "</td>";

                        echo "<td>" . htmlspecialchars($claim['claimed_time']) . "</td>";
                        echo "</tr>";
                    }

                    $claims_stmt->close(); // Close the statement after use
                    ?>
                </table>
            </div>
        </div>
    </div>
</body>

</html>