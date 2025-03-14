<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function sendEmail($mail_subject, $mail_body, $mail_attachment = null)
{
    try {
        $mail = new PHPMailer(true);

        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = "smtp.office365.com";
        $mail->SMTPAuth = true;
        $mail->Username = "dasari_2101cs22@iitp.ac.in"; // Use this email for SMTP authentication
        $mail->Password = "Pardhu14225@s"; // Use the correct App Password
        $mail->SMTPSecure = "tls";
        $mail->Port = 587;

        // Sender & Recipient
        $mail->setFrom("dasari_2101cs22@iitp.ac.in", "IITP Notifications"); // Sender Email
        $mail->addAddress("dasari_2101cs22@iitp.ac.in"); // Recipient Email

        // Email Content
        $mail->isHTML(true);
        $mail->Subject = $mail_subject;
        $mail->Body = $mail_body;

        // Attachments
        if ($mail_attachment) {
            $mail->addAttachment($mail_attachment);
        }

        // Send Email
        if ($mail->send()) {
            return "✅ Email sent successfully to dasari_2101cs22@iitp.ac.in.";
        } else {
            return "❌ Email failed: " . $mail->ErrorInfo;
        }
    } catch (Exception $e) {
        return "❌ Error: " . $e->getMessage();
    }
}

// Test the function
echo sendEmail("Test Subject", "This is a test email.");
