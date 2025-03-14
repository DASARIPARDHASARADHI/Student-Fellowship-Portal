<?php

// include("stud_login_conn.php")

// Start the session
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $uname = $_POST['uname'];
    $pwd = $_POST['pwd'];

    // Prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT password FROM students WHERE webmail=?");
    $stmt->bind_param("s", $uname);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Bind the result
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Verify the password
        if (password_verify($pwd, $hashed_password)) {
            // Store username in session to track login
            $_SESSION['logged_in_user'] = $uname;

            // Redirect to the student profile page - <div class='result' >Invalid username or password. Please try again.</div>
            header("Location: student.php");
            exit();
        } else {

            $error_message = "Invalid username or password. Please try again.";
        }
    } else {
        $error_message = "Invalid username or password. Please try again.";
    }

    $stmt->close();
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
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
</head>

<body>

    <div class="heading-container">
        <img src="iitp_symbol.png" alt="IITP Symbol">
        <h1 style="color: white;">Student Fellowship Portal</h1>
    </div>

    <h2 style="color: white; font-size: 25px;">Student Login</h2>

    <div id="container">
        <form id="registrationForm" method="POST">
            <div class="form-group">
                <label for="uname">Username </label>
                <input type="text" id="uname" name="uname" autofocus required size="35" placeholder="Username">
            </div>

            <div class="form-group">
                <label for="pwd">Password </label>
                <input type="password" id="pwd" name="pwd" required placeholder="Password">
            </div>
            <div>
                <a href="for_pass.php"
                    style="text-decoration: none; font-weight: bold; color:rgb(199, 43, 32);" target="_blank">Forgot Password?</a><br><br>

            </div>



            <div>
                <input type="submit" id="sub" value="Login">
            </div>
            <div>
                <p>Didn't Register Yet! <a href="stud_register.php"
                        style="text-decoration: none; font-weight: bold; color: #0d1eb4;" target="_blank">Register
                        Here</a></p>

            </div>

            <div id="error-message" class="err" style="display: none; color: red; font-weight: bold;"></div>

            <script>
                // Get the error message from PHP (only if login failed)
                let errorMessage = <?php echo json_encode($error_message); ?>;

                // Reference to the error message div
                let errorDiv = document.getElementById("error-message");

                // Display error message only if login failed
                if (errorMessage && errorMessage !== "") {
                    errorDiv.innerHTML = errorMessage;
                    errorDiv.style.display = "block";

                    // Set a timer to hide the message after 3 seconds (3000ms)
                    setTimeout(function() {
                        errorDiv.style.display = "none";
                    }, 3000);
                }
            </script>


        </form>

    </div>


</body>

</html>