<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$webmail = "dasari_2101cs22@iitp.ac.in";


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
            return "❌ Email failed: " . $mail->ErrorInfo;
        }
    } catch (Exception $e) {
        return "❌ Error: " . $e->getMessage();
    }
}

// Webmail variable (recipient's email)
$recipient_email = $webmail; // Ensure $webmail contains the recipient's email

// Email subject
$email_subject = "Registration Successful! on Student Fellowship Portal";

// Email body (formatted HTML)
$email_body = "
    <p>Here is your user credentials to Login<p>
    <p>Your username is: <strong>$webmail</strong></p><br>

    <p><i>With Regards</i></p>
    <p>Student Fellowship Portal</p>
    <p>IIT Patna, 801106</p>
    
";

// Send the email
echo sendEmail($recipient_email, $email_subject, $email_body);
