<?php
// Start the session to track login
session_start();

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

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['logged_in_user'])) {
    header("Location: stud_login.php");
    exit();
}

// Fetch student details based on logged-in username
$uname = $_SESSION['logged_in_user'];

$stmt = $conn->prepare("SELECT * FROM students WHERE webmail = ?");
$stmt->bind_param("s", $uname);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <link rel="icon" href="iitp_symbol.png" type="image/png">
    <style>
        body {
            /* font-family: Arial, sans-serif; */
            margin: 0;
            padding: 0;
            background-color: #dde8eb;
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
            padding: 20px;
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

        .claim {
            margin-top: 30px;
        }

        .claim a {
            padding: 10px 30px;
            text-align: center;
            background-color: #0d8c2f;
            color: white;
            border-radius: 4px;
            text-decoration: none;
            border: 2px;
        }

        .claim a:hover {
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
    <script>
        function updateClaimAmount() {
            var course = "<?php echo $student['course']; ?>";
            var enhancement = "<?php echo $student['enhancement']; ?>";
            var claimedAmountSelect = document.getElementById("claimed_amount");

            claimedAmountSelect.innerHTML = "";

            if (course === 'phd' && enhancement === 'YES') {
                claimedAmountSelect.innerHTML = `
                    <option value="42000">Rs. 42000</option>
                `;
            } else if (course === 'mtech') {
                claimedAmountSelect.innerHTML = `
                    <option value="12000">Rs. 12000</option>
                `;
            } else {
                claimedAmountSelect.innerHTML = `
                    <option value="37000">Rs. 37000</option>
                `;
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            updateClaimAmount(); // Call this function to set the claimed amount on page load
        });
    </script>
</head>

<body>

    <div class="header">
        <img src="iitp_symbol.png" alt="Logo">
        <h1>Student Fellowship Portal</h1>

    </div>

    <div class="container">
        <div class="student-details">
            <h2>Welcome, <?php echo $student['fname'] . ' ' . $student['lname']; ?>!</h2>
            <div class="logout" style="display:inline-block; margin:auto; margin-bottom: 30px;">
                <a href="student_profile_edit.php" style="background-color: #07823a;" target="_blank">Edit Details</a>
            </div>

            <!-- Readonly Details Form -->
            <form>
                <div class="form-group">
                    <label for="rollno">Roll Number</label>
                    <input type="text" id="rollno" value="<?php echo $student['rollno']; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="webmail">Webmail</label>
                    <input type="text" id="webmail" value="<?php echo $student['webmail']; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="year">Year</label>
                    <input type="text" id="year" value="<?php echo $student['year']; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="course">Course</label>
                    <input type="text" id="course" value="<?php echo $student['course']; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="department">Department</label>
                    <input type="text" id="department" value="<?php echo $student['department']; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="employee_name">Supervisor</label>
                    <input type="text" id="employee_name" value="<?php echo $student['employee_name']; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="account">Bank Account</label>
                    <input type="text" id="account" value="<?php echo $student['account']; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="bank">Bank</label>
                    <input type="text" id="bank" value="<?php echo $student['bank']; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="ifsc">IFSC Code</label>
                    <input type="text" id="ifsc" value="<?php echo $student['ifsc']; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="doj">Date of Joining</label>
                    <input type="text" id="doj" value="<?php echo $student['doj']; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="office_order">Office Order</label>
                    <input type="text" id="office_order" value="<?php echo $student['office_order']; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="doo">Date of Approval of Office Order</label>
                    <input type="text" id="doo" value="<?php echo $student['doo']; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="enhancement">Enhancement</label>
                    <input type="text" id="enhancement" value="<?php echo $student['enhancement']; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="doe">Date of Approval of Enhancement</label>
                    <input type="text" id="doe" value="<?php echo $student['doe']; ?>" readonly>
                </div>
            </form>

            <!-- Claim Form -->
            <form action="claim_process.php" method="POST">
                <h2 style="color:brown">Claim The Fellowship</h2>

                <div class="form-group">
                    <input type="hidden" id="rollno" value="<?php echo $student['rollno']; ?>">
                    <label for="claimed_month">Claimed Month (With Full Date)</label>
                    <input type="date" id="claimed_month" name="claimed_month" required>


                    <script>
                        let des = "You cannot claim now";

                        let c = document.getElementById("claimed_month");

                        function now() {
                            const date = new Date();
                            const year = date.getFullYear();
                            const month = String(date.getMonth() + 1).padStart(2, '0'); // Ensure two digits
                            const day = String(date.getDate()).padStart(2, '0'); // Ensure two digits

                            let c = document.getElementById("claimed_month");

                            if (day > 20) {
                                c.readOnly = true;
                                c.type = "text";

                                return `You cannot claim now`;
                                // c.innerHTML = "You cannot claim now";
                            }

                        }

                        document.getElementById("claimed_month").value = now();
                    </script>

                </div>
                <div class="form-group">
                    <label for="claimed_amount">Claimed Amount</label>
                    <select id="claimed_amount" name="claimed_amount" required>
                        <option value="">--Select Amount--</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="claimed">Claimed</label>
                    <select id="claimed" name="claimed" required>

                        <option value="YES" selected>YES</option>

                    </select>
                </div>

                <div class="form-group">
                    <label for="absent_days">Absent Days</label>
                    <input type="text" id="absent_days" value="<?php echo $student['absent_days']; ?>" disabled>
                </div>

                <div class="form-group">
                    <label for="approved_amount">Approved Amount</label>
                    <input type="text" id="approved_amount" name="approved_amount" disabled value="<?php echo $student["approved_amount"] ?>">
                </div>

                <div class="form-group" id="btn">
                    <button type="submit" style="background-color: #07823a; color: white; padding: 10px; border: none; border-radius: 4px; cursor: pointer" onclick="return confirm('Are you sure to submit claim ?')">Submit Claim</button>
                </div>

                <script>
                    if (document.getElementById("claimed_month").value == `${des}`) {
                        let btn = document.getElementById("btn");
                        btn.style.cssText = "display:none";
                    } else {}
                </script>

                <!-- llll -->
            </form>

            <div class="logout">
                <a href="student.php">Back to Home</a>
            </div>
        </div>
    </div>

</body>

</html>