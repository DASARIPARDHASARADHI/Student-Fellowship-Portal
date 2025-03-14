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
    <title>Student Profile Edit</title>
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
        // Fetch departments dynamically on page load
        window.onload = function() {
            fetchDepartments();
        };

        // Function to fetch and populate the first dropdown (departments)
        function fetchDepartments() {
            fetch('stud_register.php?departments=true')
                .then(response => response.json())
                .then(departments => {
                    const firstDropdown = document.getElementById("firstDropdown");
                    firstDropdown.innerHTML = '<option value="">Select Department</option>';
                    departments.forEach(department => {
                        const option = document.createElement("option");
                        option.value = department;
                        option.text = department;
                        firstDropdown.add(option);
                    });
                });
        }

        // Function to fetch and populate the second dropdown (employee names)
        function fetchEmployees(department) {
            fetch(`stud_register.php?department=${encodeURIComponent(department)}`)
                .then(response => response.json())
                .then(employees => {
                    const secondDropdown = document.getElementById("secondDropdown");
                    secondDropdown.innerHTML = '<option value="">Select Employee</option>';
                    employees.forEach(employee => {
                        const option = document.createElement("option");
                        option.value = employee;
                        option.text = employee;
                        secondDropdown.add(option);
                    });
                    secondDropdown.disabled = false; // Enable the second dropdown
                });
        }

        // Enable and populate second dropdown based on selected department
        function enableAndPopulateSecondDropdown() {
            const department = document.getElementById("firstDropdown").value;
            if (department) {
                fetchEmployees(department);
            } else {
                const secondDropdown = document.getElementById("secondDropdown");
                secondDropdown.disabled = true; // Disable if no valid department is selected
                secondDropdown.innerHTML = '<option value="">--Select Employee--</option>'; // Clear options
            }
        }
    </script>

    <script>
        function initializeFormFeatures() {
            fetchDepartments();

            // Get elements
            const enhancementMenu = document.getElementById('enhancement');
            const doeField = document.getElementById('doe');
            const dooField = document.getElementById('doo');
            const officeOrderField = document.getElementById('office_order');
            const courseField = document.getElementById('course');

            // Enable enhancement dropdown if course is PhD
            if (courseField.value === 'phd') {
                enhancementMenu.disabled = false;
            }

            // Enable Date of Enhancement if Enhancement is YES
            if (enhancementMenu.value === 'YES') {
                doeField.disabled = false;
            }

            // Enable Date of Office Order if Approval of Office Order is YES
            if (officeOrderField.value === 'YES') {
                dooField.disabled = false;
            }

            // Add event listeners for dropdown changes
            courseField.addEventListener('change', function() {
                enhancementMenu.disabled = (this.value !== 'phd');
                if (this.value !== 'phd') {
                    doeField.disabled = true; // Disable if not PhD
                }
            });

            enhancementMenu.addEventListener('change', function() {
                doeField.disabled = (this.value !== 'YES');
            });

            officeOrderField.addEventListener('change', function() {
                dooField.disabled = (this.value !== 'YES');
            });
        }

        // Ensure the function runs on window load
        window.onload = initializeFormFeatures;
    </script>

    <script>
        document.getElementById('profileEditForm').addEventListener('submit', function(event) {
            // Get passwords
            const password = document.getElementById('pwd').value;
            const confirmPassword = document.getElementById('cnf_pwd').value;

            // Check if passwords match
            if (password !== confirmPassword) {
                event.preventDefault(); // Stop form submission if passwords don't match
                alert('Password and Confirm Password should be the same!');
                return;
            }

            // Allow form submission to PHP if passwords match
            // Displaying the result after the form is processed server-side is not required anymore,
            // but if you want to display some info, you can still add that after the form submits.
        });


        // Function to toggle password visibility
        function togglePassword() {
            var pwdField = document.getElementById("pwd");
            if (pwdField.type === "password") {
                pwdField.type = "text";
            } else {
                pwdField.type = "password";
            }
        }

        // Function to toggle confirm password visibility
        function toggleConfirmPassword() {
            var cnfPwdField = document.getElementById("cnf_pwd");
            if (cnfPwdField.type === "password") {
                cnfPwdField.type = "text";
            } else {
                cnfPwdField.type = "password";
            }
        }
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

            <h2 style="color:brown; text-align:left; margin-top:25px">Student Details (Edit)</h2>

            <!-- Edit Details Form -->
            <form id="profileEditForm" action="update_student_profile.php" method="POST">
                <!-- Add hidden field to pass the user ID -->

                <div class="form-group">
                    <label for="fname">First Name </label>
                    <input type="text" id="fname" name="fname" autofocus required size="35" placeholder="First Name" value="<?php echo $student['fname'] ?>">
                </div>

                <div class="form-group">
                    <label for="lname">Last Name </label>
                    <input type="text" id="lname" name="lname" required placeholder="Last Name" value="<?php echo $student['lname']; ?>">
                </div>

                <!-- <div class="form-group">
                    <label for="gender">Gender </label>
                    <select id="gender" name="gender" required>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="others">Others</option>
                    </select>
                </div> -->

                <div class="form-group">
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender" required>
                        <option value="Male" <?php echo ($student['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo ($student['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                        <option value="Others" <?php echo ($student['gender'] == 'Others') ? 'selected' : ''; ?>>Others</option>
                    </select>
                </div>


                <div class="form-group">
                    <label for="rollno">Roll Number </label>
                    <input type="text" id="rollno" name="rollno" maxlength="8" pattern="[0-9]{4}[A-Z]{2}[0-9]{2}"
                        title="Ex: 2401CS01 (use upper case letters)" required placeholder="Roll Number" value="<?php echo $student["rollno"]; ?>">
                </div>

                <div class="form-group">
                    <label for="webmail">College Webmail </label>
                    <input type="email" id="webmail" name="webmail" size="30" required placeholder="Webmail" value="<?php echo $student["webmail"] ?>">
                </div>




                <div class="form-group">
                    <label for="course">Course </label>
                    <select id="course" name="course" required>
                        <option value="mtech" <?php echo ($student['course'] == 'mtech') ? 'selected' : ''; ?>>M.Tech</option>
                        <option value="phd" <?php echo ($student['course'] == 'phd') ? 'selected' : ''; ?>>Phd</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="firstDropdown">Department</label>
                    <select id="firstDropdown" name="firstDropdown" onchange="enableAndPopulateSecondDropdown()" required>

                        <option value="">Select Department</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="secondDropdown">Guide Name</label>
                    <select id="secondDropdown" name="secondDropdown" disabled required>
                        <option value="">Select Employee</option>
                    </select>
                </div>


                <div class="form-group">
                    <label for="account">Bank Account Number </label>
                    <input type="text" id="account" name="account" required value="<?php echo $student["account"] ?>">
                </div>

                <div class="form-group">
                    <label for="bank">Bank Name </label>
                    <input type="text" id="bank" name="bank" required value=<?php echo $student["bank"] ?>>
                </div>

                <div class="form-group">
                    <label for="ifsc">IFSC Code </label>
                    <input type="text" id="ifsc" name="ifsc" required value=<?php echo $student["ifsc"] ?>>
                </div>

                <div class="form-group">
                    <label for="doj">Date of Joining </label>
                    <input type="date" id="doj" name="doj" min="2024-06-01" required title="Select date" value="<?php echo $student["doj"] ?>">
                </div>

                <div class="form-group">
                    <label for="office_order">Approval of Office Order </label>
                    <select id="office_order" name="office_order" required>
                        <option value="YES" <?php echo ($student['office_order'] == 'YES') ? 'selected' : ''; ?>>Yes</option>
                        <option value="NO" <?php echo ($student['office_order'] == 'NO') ? 'selected' : ''; ?>>No</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="doo">Date of Office Order</label>
                    <input type="date" id="doo" name="doo" required title="Select date" value="<?php echo $student["doo"] ?>">
                </div>

                <div class="form-group">
                    <label for="enhancement">Approval of Enhancement </label>
                    <select id="enhancement" name="enhancement" required>
                        <option value="YES" <?php echo ($student['enhancement'] == 'YES') ? 'selected' : ''; ?>>Yes</option>
                        <option value="NO" <?php echo ($student['enhancement'] == 'NO') ? 'selected' : ''; ?>>No</option>
                    </select>
                </div>


                <div class="form-group">
                    <label for="doe">Date of Enhancement</label>
                    <input type="date" id="doe" name="doe" required title="Select date" value=<?php echo $student["doe"] ?>>
                </div>

                <!--Password-->
                <div class="form-group">
                    <label for="pwd">Change Password (Enter the new password, if you want to change) </label>
                    <input type="password" name="pwd" id="pwd">
                </div>

                <div style="margin-top: 3px; margin-bottom: 20px;">
                    <input type="checkbox" onclick="togglePassword()"> Show Password
                </div>

                <!-- Confirm New Password-->
                <div class="form-group">
                    <label for="cnf_pwd">Confirm New Password </label>
                    <input type="password" name="cnf_pwd" id="cnf_pwd">
                </div>

                <div style=" margin-top: 3px; margin-bottom: 20px;">
                    <input type="checkbox" onclick="toggleConfirmPassword()"> Show Confirm Password
                </div>

                <div class="form-group full-width">
                    <label for="remarks">Remarks </label>
                    <textarea id="remarks" name="remarks" rows="5" cols="45"></textarea>
                </div>

                <!-- Claim Form -->

                <div class="form-group">
                    <button type="submit" style="background-color: #07823a; color: white; padding: 10px; border: none; border-radius: 4px; cursor: pointer" onclick="return confirm('Are you sure to submit?')">Save the Changes</button>
                </div>
            </form>

            <div class="logout">
                <a href="stud_profile.php">Back to Profile</a>
            </div>
        </div>
    </div>

</body>

</html>