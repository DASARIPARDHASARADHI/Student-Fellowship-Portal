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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $webmail = $conn->real_escape_string($_POST['webmail']);
    $pwd = $_POST['pwd']; // Plain text password entered by the user

    // Fetch employee details from the database
    $sql = "SELECT * FROM employees WHERE webmail = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $webmail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stored_password = $row['password'];

        // Check if the stored password is hashed or in plain text
        if (password_verify($pwd, $stored_password) || $pwd === $stored_password) {
            // Password is correct, start session and redirect to supervisor.php
            session_start();
            $_SESSION['webmail'] = $row['webmail'];
            $_SESSION['name'] = $row['name']; // Assuming the employee name is stored
            header("Location: supervisor.php");
            exit();
        } else {

            $error_message = "Invalid username or password. Please try again.";
        }
    } else {
        $error_message = "Invalid username or password. Please try again.";
    }
}

// Close the connection
$conn->close();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Login</title>
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
</head>

<body>

    <div class="heading-container">
        <img src="/images/iitp_symbol.png" alt="IITP Symbol">
        <h1 style="color: white;">Student Fellowship Portal</h1>
    </div>

    <h2 style="color: white; font-size: 25px;">Employee Login</h2>

    <div id="container">
        <form id="registrationForm" action="supervisor_login.php" method="POST">
            <div class="form-group">
                <label for="webmail">Username </label>
                <input type="text" id="webmail" name="webmail" autofocus required size="35" placeholder="Username">
            </div>

            <div class="form-group">
                <label for="pwd">Password </label>
                <input type="password" id="pwd" name="pwd" required placeholder="Password">
            </div>



            <div>
                <input type="submit" value="Login">
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