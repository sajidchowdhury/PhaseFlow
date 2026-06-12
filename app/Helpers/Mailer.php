<?php

namespace App\Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    public static function send($to, $name, $subject, $body)
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';           // Change if using other provider
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['MAIL_USERNAME'] ?? '';   // Add to .env
            $mail->Password   = $_ENV['MAIL_PASSWORD'] ?? '';   // App password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom($_ENV['MAIL_FROM'] ?? 'no-reply@phaseflow.com', 'PhaseFlow CRM');
            $mail->addAddress($to, $name);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;

             if($mail->send()) {
                return true;
            } else {
               return false;
            }
        } catch (Exception $e) {
            error_log("Mailer Error: " . $mail->ErrorInfo);
            return false;
        }
    }
}