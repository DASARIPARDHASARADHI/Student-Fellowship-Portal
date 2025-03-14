<?php
// session_start(); // Start the session

// // Check if the user is logged in as a department officer
// if (!isset($_SESSION['employee_id'])) {
//     // If not logged in, redirect to the login page or display an error message
//     header("Location: supervisor_login.php"); // Replace 'login.php' with the actual login page
//     exit(); // Stop further execution
// }

include("change_password_conn.php")

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervisor Change Password</title>
    <link rel="icon" href="/images/iitp_symbol.png" type="image/png">
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

    <!-- For Checking the password is same or not with confirm password -->
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

</head>

<body>

    <div class="heading-container">
        <img src="/images/iitp_symbol.png" alt="IITP Symbol">
        <h1 style="color: white;">Student Fellowship Portal</h1>
    </div>

    <h2 style="color: white;">Supervisor Change Password</h2>

    <div id="container">
        <form id="registrationForm" action="change_password_conn.php" method="POST">

            <!--Password-->
            <div class="form-group">
                <label for="pwd">New Password </label>
                <input type="password" name="pwd" id="pwd" required>
            </div>

            <div style="margin-left: 150px; margin-top: -10px;">
                <input type="checkbox" onclick="togglePassword()"> Show Password
            </div>

            <!-- Confirm Password-->
            <div class="form-group">
                <label for="cnf_pwd">Confirm New Password </label>
                <input type="password" name="cnf_pwd" id="cnf_pwd" required>
            </div>

            <div style="margin-left: 150px; margin-top: -10px;">
                <input type="checkbox" onclick="toggleConfirmPassword()"> Show Confirm Password
            </div>

            <div>
                <input type="submit" value="Submit" onclick="return confirm('Are you sure to submit?')">
            </div>
        </form>



        <div id="result" class="result" style="display: none;"></div>
    </div>


</body>

</html>