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

// Start the session
session_start();

// Fetch the supervisor details using the session employee_id
$employee_id = $_SESSION['employee_id'];
$supervisor_name = ""; // To store the supervisor's name
$department = ""; // To store the supervisor's department

// Get the supervisor's details (name and department)
$sql_supervisor = "SELECT name, department FROM employees WHERE employee_id = ?";
$stmt_supervisor = $conn->prepare($sql_supervisor);
$stmt_supervisor->bind_param("s", $employee_id);
$stmt_supervisor->execute();
$result_supervisor = $stmt_supervisor->get_result();

$supervisor_row = $result_supervisor->fetch_assoc();
$supervisor_name = $supervisor_row['name'];
$department = $supervisor_row['department'];

// Fetch data based on the selected filter (All, Pending, Approved, Rejected)
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

// Define the query based on the filter, supervisor's department, and name
switch ($filter) {
    case 'approved':
        $query = "SELECT * FROM students WHERE appr_by_supervisor = 'YES' AND office_order='NO' AND department = ? AND employee_name = ?";
        break;
    case 'pending':
        $query = "SELECT * FROM students WHERE appr_by_supervisor = 'NO' AND office_order='NO' AND department = ? AND employee_name = ?";
        break;
    case 'rejected':
        $query = "SELECT * FROM students WHERE appr_by_supervisor = 'REJECTED' AND office_order='NO' AND department = ? AND employee_name = ?";
        break;
    case 'phd':
        $query = "SELECT * FROM students WHERE application_sent = 'YES' AND course = 'phd' AND office_order='NO' AND department = ? AND employee_name = ?";
        break;
    case 'mtech':
        $query = "SELECT * FROM students WHERE application_sent = 'YES' AND course = 'mtech' AND office_order='NO' AND department = ? AND employee_name = ?";
        break;
    default:
        $query = "SELECT * FROM students WHERE office_order='NO' AND department = ? AND employee_name = ?";
}

// Fetch student data for the supervisor's department and guide name
$stmt_students = $conn->prepare($query);
$stmt_students->bind_param("ss", $department, $supervisor_name);
$stmt_students->execute();
$result_students = $stmt_students->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervisor Dashboard</title>
    <link rel="icon" href="images/iitp_symbol.png" type="image/png">

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

        ul.navbar {
            list-style-type: none;
            width: 150px;
            height: 900px;
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

        /* Dropdown container */
        .dropdown {
            position: relative;
            display: block;
            text-align: center;

        }

        /* Dropdown content (hidden by default) */
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #0a5375;
            min-width: 150px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        /* Links inside the dropdown */
        .dropdown-content a {
            color: white;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        /* Change color of dropdown links on hover */
        .dropdown-content a:hover {
            background-color: #1192ce;
        }

        /* Show the dropdown menu on hover */
        .dropdown:hover .dropdown-content {
            display: block;
        }

        /* Navbar styles */
        #main {
            display: flex;
            overflow: auto;

        }

        #status_table {
            display: flex;
            margin-left: 10px;
            width: 100%;
            flex-direction: column;
            align-items: center;
        }

        table {
            border: solid black 1px;
            border-collapse: collapse;
            width: 100%;
            max-width: 1200px;

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

        ul.status {
            list-style-type: none;
            padding: 0;
            margin: 0;
            background-color: #0a5375;
        }

        ul.status li {
            display: inline-block;
            cursor: pointer;
        }

        ul.status li a {
            color: white;
            text-decoration: none;
            padding: 10px;
        }

        ul.status li.active {
            background-color: #1f94ca;
        }

        #stud_details {
            display: inline-block;
            text-decoration: none;
            color: white;
            padding: 6px 12px;
            background-color: #1f94ca;

            margin-bottom: 10px;

        }
    </style>
</head>

<body>

    <div class="header">
        <div style="align-self: center; margin:5px; width: 100px;">
            <img style="height: 90px; width: 90px;" src="/images/iitp_symbol.png">
        </div>
        <div style="width: 100%; margin-left: 100px; text-align: center;">
            <h1 style="font-size: 35px; color: white;">Student Fellowship Portal</h1>
        </div>
    </div>

    <div class="header">
        <!-- Omitted for brevity -->
    </div>

    <div id="main">
        <div>
            <ul class="navbar">
                <li><a href="/start.php">Profile</a></li>

                <!-- Dropdown for Students -->
                <li class="dropdown">
                    <a href="javascript:void(0)">Students</a>
                    <div class="dropdown-content">
                        <a href="supervisor.php?filter=phd">PhD</a>
                        <a href="supervisor.php?filter=mtech">MTech</a>
                    </div>
                </li>

                <li><a href="/start.php">Logout</a></li>
                <hr>
            </ul>
        </div>

        <div id="status_table">
            <div>
                <h2>Welcome <?php echo $supervisor_name; ?></h2>
            </div>
            <div style="text-align: center; margin-top: 0px;">
                <h1>Applications</h1>
            </div>
            <div>
                <ul class="status">
                    <li id="all"><a href="supervisor.php?filter=all">All</a></li>
                    <li id="pending"><a href="supervisor.php?filter=pending">Pending</a></li>
                    <li id="approved"><a href="supervisor.php?filter=approved">Approved</a></li>
                    <li id="rejected"><a href="supervisor.php?filter=rejected">Rejected</a></li>
                </ul>

                <script>
                    const currentFilter = "<?php echo $filter; ?>";
                    const statusLinks = document.querySelectorAll('ul.status li');
                    statusLinks.forEach(link => {
                        const filterId = link.id;
                        if (filterId === currentFilter) {
                            link.classList.add('active');
                        }
                    });
                </script>
            </div>

            <div style="overflow-x: auto; margin: 10px;">
                <table id="t4">
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Roll No</th>
                        <th>Year</th>
                        <th>Course</th>
                        <th>Department</th>
                        <th>Guide</th>
                        <th>Approval</th>
                    </tr>
                    <?php while ($row = $result_students->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['fname']; ?></td>
                            <td><?php echo $row['lname']; ?></td>
                            <td><?php echo $row['rollno']; ?></td>
                            <td><?php echo $row['year']; ?></td>
                            <td><?php echo $row['course']; ?></td>
                            <td><?php echo $row['department']; ?></td>
                            <td><?php echo $row['employee_name']; ?></td>
                            <td>
                                <a id="stud_details" href="student_details.php?rollno=<?php echo $row['rollno']; ?>">View Full Details</a>
                                <form action="supervisor_approve.php" method="post">
                                    <input type="hidden" name="rollno" value="<?php echo $row['rollno']; ?>">
                                    <select name="approval_status">
                                        <option value="YES" <?php if ($row['appr_by_supervisor'] == 'YES') echo 'selected'; ?>>Approve</option>
                                        <option value="REJECTED" <?php if ($row['appr_by_supervisor'] == 'REJECTED') echo 'selected'; ?>>Reject</option>
                                    </select>
                                    <br>
                                    <textarea name="supervisor_remarks" placeholder="Enter your remarks"></textarea>
                                    <input type="submit" value="Submit">
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>

</body>

</html>