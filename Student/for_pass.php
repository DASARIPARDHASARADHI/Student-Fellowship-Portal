<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "pardhu14225"; // Update your MySQL password
$dbname = "fellowship_portal";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $webmail = trim($_POST["uname"]); // Get input and sanitize

    // Check if webmail exists
    $sql = "SELECT * FROM students WHERE webmail = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $webmail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Webmail found, generate new password
        $new_password = bin2hex(random_bytes(4)); // 8-character random password
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT); // Hash password

        // Update new password in database
        $update_sql = "UPDATE students SET password = ? WHERE webmail = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ss", $hashed_password, $webmail);


        if ($update_stmt->execute()) {
            // Send email function
            function sendEmail($recipient, $mail_subject, $mail_body, $mail_attachment = null)
            {
                try {
                    $mail = new PHPMailer(true);

                    // SMTP Configuration
                    $mail->isSMTP();
                    $mail->Host = "smtp.office365.com";
                    $mail->SMTPAuth = true;
                    $mail->Username = "dasari_2101cs22@iitp.ac.in"; // Your IITP Email (Sender)
                    $mail->Password = ""; // Use App Password (Not normal password)
                    $mail->SMTPSecure = "tls";
                    $mail->Port = 587;

                    // Sender & Recipient
                    $mail->setFrom("dasari_2101cs22@iitp.ac.in", "IITP Notifications"); // Your Email as Sender
                    $mail->addAddress($recipient); // Receiver Email (Webmail)

                    // Email Content
                    $mail->isHTML(true);
                    $mail->Subject = $mail_subject;
                    $mail->Body = $mail_body;

                    // Attachments (Optional)
                    if ($mail_attachment) {
                        $mail->addAttachment($mail_attachment);
                    }

                    // Send Email
                    if ($mail->send()) {
                        echo "<script>
            alert('Check your webmail for New Password!');
            window.location.href = 'stud_login.php';
        </script>";
                    } else {
                        return "❌ Email failed: " . $mail->ErrorInfo;
                    }
                } catch (Exception $e) {
                    return "❌ Error: " . $e->getMessage();
                }
            }

            // Webmail variable (recipient's email)
            $recipient_email = $webmail; // Ensure $webmail contains the recipient's email

            // Email subject
            $email_subject = "Password Changed for Login on Student Fellowship Portal";

            // Email body (formatted HTML)
            $email_body = "
        <p>Here is your new password to Login</p>
        <p>Your new password: <strong>$new_password</strong></p><br>
        
        <p><i>With Regards</i></p>
        <p>Student Fellowship Portal</p>
        <p>IIT Patna, 801106</p>
        
        ";

            // Send the email
            echo sendEmail($recipient_email, $email_subject, $email_body);
        } else {

            echo "<script>alert('Error updating password. Please try again.')</script>";
        }
    } else {

        echo "<script>alert('Webmail not found. Please enter a registered webmail.')</script>";
    }

    $stmt->close();
    $conn->close();
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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

    <h2 style="color: white; font-size: 25px;">Forgot Password</h2>

    <div id="container">
        <form id="registrationForm" method="POST">
            <p>Don't worry. Resetting your password is easy, just tell us the webmail address you registered</p><br>
            <div class="form-group">

                <label for="uname">Registered Webmail </label>
                <input type="text" id="uname" name="uname" autofocus required size="35" placeholder="Webmail">
            </div>


            <div>
                <input type="submit" id="sub" value="Send">
            </div>


            <div>
                <p>Didn't Register Yet! <a href="stud_register.php"
                        style="text-decoration: none; font-weight: bold; color: #0d1eb4;" target="_blank">Register
                        Here</a></p>

            </div>


        </form>

    </div>


</body>

</html>
