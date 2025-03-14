<?php
session_start(); // Start the session

// Check if the user is logged in as a department officer
if (!isset($_SESSION['dept_offc_id'])) {
    // If not logged in, redirect to the login page or display an error message
    header("Location: dept_offc_login.php"); // Replace 'login.php' with the actual login page
    exit(); // Stop further execution
}

include("add_faculty_conn.php");
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Registration</title>
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

    <script>
        // Fetch departments dynamically on page load
        window.onload = function() {
            fetchDepartments();
        };

        // Function to fetch and populate the first dropdown (departments)
        function fetchDepartments() {
            fetch('add_faculty.php?departments=true')
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
    </script>

</head>

<body>
    <div class="heading-container">
        <img src="/images/iitp_symbol.png" alt="IITP Symbol">
        <h1 style="color: white;">Indian Institute of Technology Patna</h1>
    </div>

    <h2 style="color: white;">Faculty Registration</h2>

    <div id="container">
        <form id="registrationForm" action="add_faculty_conn.php" method="POST">
            <div class="form-group">
                <label for="name">Full Name: </label>
                <input type="text" id="name" name="name" autofocus required size="35" placeholder="Full Name">
            </div>
            <div class="form-group">
                <label for="firstDropdown">Department</label>
                <select id="firstDropdown" name="firstDropdown" onchange="enableAndPopulateSecondDropdown()" required>
                    <option value="">Select Department</option>
                </select>
            </div>
            <div class="form-group">
                <label for="department_code">Department Code: </label>
                <input type="text" id="department_code" name="department_code" placeholder="Department Code">
            </div>

            <div>
                <input type="submit" value="Add Faculty" onclick="return confirm('Are you sure to submit?')">
            </div>
        </form>

        <div id="result" class="result" style="display: none;"></div>
    </div>
</body>

</html>