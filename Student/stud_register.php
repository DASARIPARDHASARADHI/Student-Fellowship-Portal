<?php

include("stud_register_conn.php")

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <link rel="icon" href="iitp_symbol.png" type="image/png">
    <style>
        body {
            background-color: #1f94ca;
            background-repeat: no-repeat;
            margin: 5px;
            padding: 5px;
        }

        h1,
        h2 {
            text-align: center;
        }

        h1 {
            font-size: 40px;
        }

        * {
            box-sizing: border-box;
        }

        input,
        select,
        textarea {
            padding: 10px 12px;
            margin: 6px 0;
            flex: 1;
        }

        input:focus {
            border: 2px solid #222020;
        }

        label {
            padding: 3px 6px;
            display: inline-block;
            width: 150px;
            text-align: left;
        }

        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .form-group.full-width {
            flex-direction: column;
            align-items: flex-start;
        }

        .form-group.full-width label {
            width: auto;
            margin-bottom: 5px;
        }

        #container {
            width: 600px;
            border: solid #0d86be 3px;
            border-radius: 5px;
            background-color: #a0cee4;
            padding: 20px 40px;
            margin: 0 auto;
            box-shadow: 2px 2px 9px 8px #045b83;
        }

        input[type="submit"] {
            padding: 10px 20px;
            border: solid rgb(7, 160, 65) 2px;
            text-align: center;
            cursor: pointer;
            background-color: rgb(7, 160, 65);
            color: white;
        }

        input[type="submit"]:hover,
        input[type="submit"]:active {
            border: solid rgb(3, 90, 36) 2px;
            background-color: rgb(3, 90, 36);
            color: white;
        }

        .heading-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            margin-bottom: 20px;
        }

        .heading-container img {
            width: 100px;
            height: 100px;
        }

        .result {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #0d86be;
            border-radius: 5px;
            background-color: #e0f7fa;
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

            // Initially disable "Approval of Enhancement", "Date of Enhancement", and "Date of Office Order"
            document.getElementById('enhancement').disabled = true;
            document.getElementById('doe').disabled = true;
            document.getElementById('doo').disabled = true;

            // Enable "Approval of Enhancement" if Course == Phd
            document.getElementById('course').addEventListener('change', function() {
                const course = this.value;
                const enhancementMenu = document.getElementById('enhancement');
                if (course === 'phd') {
                    enhancementMenu.disabled = false;
                } else {
                    enhancementMenu.disabled = true;
                    document.getElementById('doe').disabled = true; // Disable Date of Enhancement if course is not Phd
                }
            });

            // Enable "Date of Enhancement" based on "Approval of Enhancement" selection
            document.getElementById('enhancement').addEventListener('change', function() {
                const enhancementApproval = this.value;
                const doeField = document.getElementById('doe');
                if (enhancementApproval === 'YES') {
                    doeField.disabled = false;
                } else {
                    doeField.disabled = true;
                }
            });

            // Enable "Date of Office Order" based on "Approval of Office Order" selection
            document.getElementById('office_order').addEventListener('change', function() {
                const officeOrderApproval = this.value;
                const dooField = document.getElementById('doo');
                if (officeOrderApproval === 'YES') {
                    dooField.disabled = false;
                } else {
                    dooField.disabled = true;
                }
            });
        }

        // Call the function when the window loads
        window.onload = initializeFormFeatures;
    </script>

</head>

<body>

    <div class="heading-container">
        <img src="iitp_symbol.png" alt="IITP Symbol">
        <h1 style="color: white;">Student Fellowship Portal</h1>
    </div>

    <h2 style="color: white;">Student Registration</h2>

    <div id="container">
        <form id="registrationForm" action="stud_register_conn.php" method="POST">
            <div class="form-group">
                <label for="fname">First Name </label>
                <input type="text" id="fname" name="fname" autofocus required size="35" placeholder="First Name">
            </div>

            <div class="form-group">
                <label for="lname">Last Name </label>
                <input type="text" id="lname" name="lname" required placeholder="Last Name">
            </div>

            <div class="form-group">
                <label for="gender">Gender </label>
                <select id="gender" name="gender" required>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="others">Others</option>
                </select>
            </div>

            <div class="form-group">
                <label for="rollno">Roll Number </label>
                <input type="text" id="rollno" name="rollno" maxlength="8" pattern="[0-9]{4}[A-Z]{2}[0-9]{2}"
                    title="Ex: 2401CS01 (use upper case letters)" required placeholder="Roll Number">
            </div>

            <div class="form-group">
                <label for="webmail">College Webmail </label>
                <input type="email" id="webmail" name="webmail" size="30" required placeholder="Webmail">
            </div>

            <div class="form-group">
                <label for="year">Year </label>
                <select id="year" name="year" required>
                    <option value="First Year" selected>First Year</option>

                </select>
            </div>


            <div class="form-group">
                <label for="course">Course </label>
                <select id="course" name="course" required>
                    <option value="mtech">M.Tech</option>
                    <option value="phd">Phd</option>
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
                <input type="text" id="account" name="account" required>
            </div>

            <div class="form-group">
                <label for="bank">Bank Name </label>
                <input type="text" id="bank" name="bank" required>
            </div>

            <div class="form-group">
                <label for="ifsc">IFSC Code </label>
                <input type="text" id="ifsc" name="ifsc" required>
            </div>

            <div class="form-group">
                <label for="doj">Date of Joining </label>
                <input type="date" id="doj" name="doj" min="2024-06-01" required title="Select date">
            </div>

            <div class="form-group">
                <label for="office_order">Approval of Office Order </label>
                <select id="office_order" name="office_order" required>
                    <option value="YES">Yes</option>
                    <option value="NO" selected>No</option>
                </select>
            </div>

            <div class="form-group">
                <label for="doo">Date of Office Order</label>
                <input type="date" id="doo" name="doo" required title="Select date">
            </div>

            <div class="form-group">
                <label for="enhancement">Approval of Enhancement </label>
                <select id="enhancement" name="enhancement" required>
                    <option value="YES">Yes</option>
                    <option value="NO" selected>No</option>
                </select>
            </div>


            <div class="form-group">
                <label for="doe">Date of Enhancement</label>
                <input type="date" id="doe" name="doe" required title="Select date">
            </div>

            <!--Password-->
            <div class="form-group">
                <label for="pwd">Password </label>
                <input type="password" name="pwd" id="pwd" required>
            </div>

            <div style="margin-left: 150px; margin-top: -10px;">
                <input type="checkbox" onclick="togglePassword()"> Show Password
            </div>

            <!-- Confirm Password-->
            <div class="form-group">
                <label for="cnf_pwd">Confirm Password </label>
                <input type="password" name="cnf_pwd" id="cnf_pwd" required>
            </div>

            <div style="margin-left: 150px; margin-top: -10px;">
                <input type="checkbox" onclick="toggleConfirmPassword()"> Show Confirm Password
            </div>

            <div class="form-group full-width">
                <label for="remarks">Remarks </label>
                <textarea id="remarks" name="remarks" rows="5" cols="45"></textarea>
            </div>

            <div>
                <input type="submit" value="Submit" onclick="return confirm('Are you sure to submit?')">
            </div>
        </form>

        <div>
            <p>Already Registered! <a href="stud_login.php"
                    style="text-decoration: none; font-weight: bold; color: #0d1eb4;" target="_blank">Login
                    Here</a></p>
        </div>

        <div id="result" class="result" style="display: none;"></div>
    </div>

    <script>
        document.getElementById('registrationForm').addEventListener('submit', function(event) {
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
</body>

</html>