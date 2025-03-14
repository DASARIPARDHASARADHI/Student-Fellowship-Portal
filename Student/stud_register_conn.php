<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';


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



// Check if it's an AJAX request to get department or employee names
if (isset($_GET['departments'])) {
    // Fetch unique department names for the first dropdown
    $query = "SELECT DISTINCT department FROM employees ORDER BY department ASC";
    $result = $conn->query($query);
    $departments = [];

    while ($row = $result->fetch_assoc()) {
        $departments[] = $row['department'];
    }
    echo json_encode($departments);
    exit();
}

if (isset($_GET['department'])) {
    // Fetch employee names based on department
    $department = $conn->real_escape_string($_GET['department']);
    $query = "SELECT name FROM employees WHERE department = ? ORDER BY name ASC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $department);
    $stmt->execute();
    $result = $stmt->get_result();
    $employees = [];

    while ($row = $result->fetch_assoc()) {
        $employees[] = $row['name'];
    }
    echo json_encode($employees);
    exit();
}

// Check if form is submitted using POST request for registration
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize inputs
    $fname = $conn->real_escape_string($_POST['fname']);
    $lname = $conn->real_escape_string($_POST['lname']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $rollno = $conn->real_escape_string($_POST['rollno']);
    $webmail = $conn->real_escape_string($_POST['webmail']);
    $year = $conn->real_escape_string($_POST['year']);
    $course = $conn->real_escape_string($_POST['course']);
    $department = $conn->real_escape_string($_POST['firstDropdown']);  // Department from first dropdown
    $employee_name = $conn->real_escape_string($_POST['secondDropdown']);  // Employee from second dropdown
    $account = $conn->real_escape_string($_POST['account']);
    $bank = $conn->real_escape_string($_POST['bank']);
    $ifsc = $conn->real_escape_string($_POST['ifsc']);
    $doj = $conn->real_escape_string($_POST['doj']);
    $remarks = $conn->real_escape_string($_POST['remarks']);
    $office_order = $conn->real_escape_string($_POST['office_order']);
    $pwd_not_hash = $conn->real_escape_string($_POST['pwd']);

    // Allow empty values for doo, doe, and enhancement fields
    $doo = isset($_POST['doo']) ? $conn->real_escape_string($_POST['doo']) : '';
    $enhancement = isset($_POST['enhancement']) ? $conn->real_escape_string($_POST['enhancement']) : '';
    $doe = isset($_POST['doe']) ? $conn->real_escape_string($_POST['doe']) : '';

    // Extract username from webmail (part before '@')
    // $username = explode('@', $webmail)[0];
    // $username = $webmail;

    // Hash the password for security
    $pwd = password_hash($_POST['pwd'], PASSWORD_BCRYPT);

    // SQL query to insert data
    $sql = "INSERT INTO students (fname, lname, gender, rollno, webmail, year, course, department, employee_name, account, bank, ifsc, doj, password, remarks, office_order, doo, enhancement, doe)
            VALUES ('$fname', '$lname', '$gender', '$rollno', '$webmail', '$year', '$course', '$department', '$employee_name', '$account', '$bank', '$ifsc', '$doj', '$pwd', '$remarks', '$office_order', '$doo', '$enhancement', '$doe')";

    // Execute query and check if successful
    // Execute query and check if successful
    if ($conn->query($sql) === TRUE) {
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
                $mail->Password = "Pardhu14225@s"; // Use App Password (Not normal password)
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
                    echo "<script>alert('Registered successfully! Check your email for login credentials'); window.location.href = 'stud_login.php';</script>";
                } else {
                    echo "<script>alert('Email Failed')</script>";
                }
            } catch (Exception $e) {
                echo "<script>alert('Error')</script>" . $e->getMessage();
            }
        }

        // Webmail variable (recipient's email)
        $recipient_email = $webmail; // Ensure $webmail contains the recipient's email

        // Email subject
        $email_subject = "Registration Successful! on Student Fellowship Portal";

        // Email body (formatted HTML)
        $email_body = "
    <p>Here is your user credentials to Login<p>
    <p>Your username is: <strong>$webmail</strong></p>
    <p>Your password is: <strong>$pwd_not_hash</strong></p><br>

    <p><i>With Regards</i></p>
    <p>Student Fellowship Portal</p>
    <p>IIT Patna, 801106</p>
    
";

        // Send the email
        echo sendEmail($recipient_email, $email_subject, $email_body);
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close connection
$conn->close();
