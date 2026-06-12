<?php

namespace App\Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    public static function send($toEmail, $toName, $subject, $htmlBody)
    {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';           // Change this
            $mail->SMTPAuth   = true;
            $mail->Username   = 'mohiturrahamanchowdhury@gmail.com';     // Your email
            $mail->Password   = 'S@JID!@#$';        // Gmail App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Recipients
            $mail->setFrom('noreply@phaseflow.com', 'PhaseFlow CRM');
            $mail->addAddress($toEmail, $toName);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $htmlBody;
            $mail->AltBody = strip_tags($htmlBody);

            $mail->send();
            return true;

        } catch (Exception $e) {
            // Log error in production
            error_log("Mailer Error: " . $mail->ErrorInfo);
            return false;
        }
    }
}