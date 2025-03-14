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

// Fetch the hod details using the session webmail
$webmail = $_SESSION['webmail'];
$hod_name = ""; // To store the hod's name
$department = ""; // To store the hod's department

// Get the hod's details (name and department)
$sql_hod = "SELECT name, department FROM employees WHERE webmail = ?";
$stmt_hod = $conn->prepare($sql_hod);
$stmt_hod->bind_param("s", $webmail);
$stmt_hod->execute();
$result_hod = $stmt_hod->get_result();

$hod_row = $result_hod->fetch_assoc();
$hod_name = $hod_row['name'];
$department = $hod_row['department'];

// Fetch data based on the selected filter (All, Pending, Approved, Rejected)
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

// Define the query based on the filter, hod's department, and name
switch ($filter) {
    case 'approved':
        $query = "SELECT * FROM students WHERE appr_by_hod = 'YES' AND appr_by_dept_offc = 'YES' AND claimed='YES' AND department = ?";
        break;
    case 'pending':
        $query = "SELECT * FROM students WHERE appr_by_hod = 'NO' AND appr_by_dept_offc = 'YES' AND claimed='YES' AND department = ?";
        break;
    case 'rejected':
        $query = "SELECT * FROM students WHERE appr_by_hod = 'REJECTED' AND appr_by_dept_offc = 'YES' AND claimed='YES' AND department = ?";
        break;
    case 'phd':
        $query = "SELECT * FROM students WHERE application_sent = 'YES' AND course = 'phd' AND appr_by_dept_offc = 'YES' AND claimed='YES' AND department = ?";
        break;
    case 'mtech':
        $query = "SELECT * FROM students WHERE application_sent = 'YES' AND course = 'mtech' AND appr_by_dept_offc = 'YES' AND claimed='YES' AND department = ?";
        break;
    default:
        $query = "SELECT * FROM students WHERE appr_by_dept_offc = 'YES' AND claimed='YES' AND department = ?";
}

// Fetch student data for the hod's department and guide name
$stmt_students = $conn->prepare($query);
$stmt_students->bind_param("s", $department);
$stmt_students->execute();
$result_students = $stmt_students->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOD Dashboard</title>
    <link rel="icon" href="/images/iitp_symbol.png" type="image/png">

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
                        <a href="hod.php?filter=phd">PhD</a>
                        <a href="hod.php?filter=mtech">MTech</a>
                    </div>
                </li>

                <li><a href="hod_login.php">Logout</a></li>
                <hr>
            </ul>
        </div>

        <div id="status_table">
            <div>
                <h2>Welcome <?php echo $hod_name; ?></h2>
            </div>
            <div style="text-align: center; margin-top: 0px;">
                <h1>Applications</h1>
            </div>
            <div>
                <ul class="status">
                    <li id="all"><a href="hod.php?filter=all">All</a></li>
                    <li id="pending"><a href="hod.php?filter=pending">Pending</a></li>
                    <li id="approved"><a href="hod.php?filter=approved">Approved</a></li>
                    <li id="rejected"><a href="hod.php?filter=rejected">Rejected</a></li>
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

            <!-- Conditional rendering based on the filter -->
            <?php if ($filter !== 'all') : ?>
                <div style="text-align: center; margin: 10px;">
                    <!-- Bulk Approval Controls -->
                    <input type="checkbox" id="select_all" style="margin-right: 10px;">
                    <select id="bulk_action">
                        <option value="">Bulk Action</option>
                        <option value="YES">Approve All</option>
                        <option value="REJECTED">Reject All</option>
                    </select>
                    <button onclick="submitBulkApproval()">Submit</button>
                </div>
            <?php endif; ?>

            <div style="overflow-x: auto; margin: 10px;">
                <form id="bulk_approval_form" action="hod_approve.php" method="post">
                    <table id="t4">
                        <tr>
                            <?php if ($filter !== 'all') : ?>
                                <th>Select</th>
                            <?php endif; ?>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Roll No</th>
                            <th>Year</th>
                            <th>Course</th>
                            <th>Department</th>
                            <th>Guide</th>
                            <th>Claimed Date</th>
                            <th>Claimed Amount</th>
                            <th>Approval</th>
                        </tr>
                        <?php while ($row = $result_students->fetch_assoc()) : ?>
                            <tr>
                                <?php if ($filter !== 'all') : ?>
                                    <td>
                                        <input type="checkbox" class="select_student" name="selected_students[]" value="<?php echo $row['rollno']; ?>">
                                    </td>
                                <?php endif; ?>
                                <td><?php echo $row['fname']; ?></td>
                                <td><?php echo $row['lname']; ?></td>
                                <td><?php echo $row['rollno']; ?></td>
                                <td><?php echo $row['year']; ?></td>
                                <td><?php echo $row['course']; ?></td>
                                <td><?php echo $row['department']; ?></td>
                                <td><?php echo $row['employee_name']; ?></td>
                                <td>
                                    <?php
                                    if (!empty($row['claimed_month'])) {
                                        $date = new DateTime($row['claimed_month']);
                                        echo $date->format('M Y');
                                    } else {
                                        echo "No date provided";
                                    }
                                    ?>
                                </td>
                                <td><?php echo $row['claimed_amount']; ?></td>
                                <td>
                                    <a id="stud_details" href="student_details.php?rollno=<?php echo $row['rollno']; ?>" target="_blank">View Full Details</a><br>

                                    <?php if ($filter === 'all') : ?>
                                        <input style="width:80px " type="text" value="<?php echo ($row['appr_by_hod'] == 'YES') ? 'Approved' : (($row['appr_by_hod'] == 'REJECTED') ? 'Rejected' : 'Pending'); ?>" readonly>
                                    <?php else : ?>
                                        <select name="approval_status[<?php echo $row['rollno']; ?>]">
                                            <option value="YES" <?php if ($row['appr_by_hod'] == 'YES') echo 'selected'; ?>>Approve</option>
                                            <option value="REJECTED" <?php if ($row['appr_by_hod'] == 'REJECTED') echo 'selected'; ?>>Reject</option>
                                        </select>
                                    <?php endif; ?>

                                    <textarea name="hod_remarks[<?php echo $row['rollno']; ?>]" placeholder="Enter your remarks" <?php echo ($filter === 'all') ? 'readonly' : ''; ?>><?php echo htmlspecialchars($row['hod_offc_remarks']); ?></textarea>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </table>
                </form>
            </div>

            <script>
                // Select all students
                document.getElementById('select_all')?.addEventListener('change', function() {
                    const checkboxes = document.querySelectorAll('.select_student');
                    checkboxes.forEach(checkbox => checkbox.checked = this.checked);
                });

                // Handle bulk action dropdown
                document.getElementById('bulk_action')?.addEventListener('change', function() {
                    const approvalStatus = this.value;
                    document.querySelectorAll('.select_student:checked').forEach(checkbox => {
                        const rollno = checkbox.value;
                        document.querySelector(`[name="approval_status[${rollno}]"]`).value = approvalStatus;
                    });
                });

                // Submit bulk approval form
                function submitBulkApproval() {
                    // Filter out unchecked entries
                    document.querySelectorAll('.select_student:not(:checked)').forEach(checkbox => {
                        checkbox.closest('tr').querySelectorAll('select, textarea').forEach(input => input.disabled = true);
                    });
                    document.getElementById('bulk_approval_form').submit();
                }
            </script>
        </div>
    </div>

</body>

</html>