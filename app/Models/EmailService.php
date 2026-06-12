<?php

namespace App\Models;

class EmailService
{
    public function sendVerificationCode(string $email, string $name, string $code): bool
    {
        $subject = 'Verify Your Email Address - PhaseFlow';

        $htmlBody = '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Email Verification</title>
        </head>
        <body style="margin:0;padding:0;background-color:#f4f6f9;font-family:Arial,sans-serif;">

            <table width="100%" cellpadding="0" cellspacing="0" style="padding:30px 15px;background-color:#f4f6f9;">
                <tr>
                    <td align="center">

                        <table width="600" cellpadding="0" cellspacing="0"
                               style="background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,0.08);">

                            <!-- Header -->
                            <tr>
                                <td align="center"
                                    style="background:#0d9488;padding:30px;color:#ffffff;">
                                    <h1 style="margin:0;font-size:28px;">
                                        PhaseFlow
                                    </h1>
                                    <p style="margin:10px 0 0;font-size:14px;">
                                        Smart Business Management Platform
                                    </p>
                                </td>
                            </tr>

                            <!-- Content -->
                            <tr>
                                <td style="padding:40px 35px;">

                                    <h2 style="margin-top:0;color:#111827;">
                                        Hello ' . htmlspecialchars($name) . ',
                                    </h2>

                                    <p style="font-size:16px;color:#4b5563;line-height:1.8;">
                                        Thank you for registering with PhaseFlow.
                                        To complete your account setup, please verify your email address using the code below:
                                    </p>

                                    <div style="
                                        background:#0d9488;
                                        color:#ffffff;
                                        font-size:38px;
                                        font-weight:bold;
                                        text-align:center;
                                        padding:18px;
                                        border-radius:10px;
                                        letter-spacing:10px;
                                        margin:30px 0;">
                                        ' . $code . '
                                    </div>

                                    <p style="font-size:15px;color:#4b5563;line-height:1.8;">
                                        This verification code will expire in
                                        <strong>15 minutes</strong>.
                                    </p>

                                    <p style="font-size:15px;color:#4b5563;line-height:1.8;">
                                        If you did not create an account, you can safely ignore this email.
                                    </p>

                                </td>
                            </tr>

                            <!-- Footer -->
                            <tr>
                                <td align="center"
                                    style="padding:25px;background:#f8fafc;color:#6b7280;font-size:13px;">
                                    © ' . date('Y') . ' PhaseFlow. All rights reserved.
                                </td>
                            </tr>

                        </table>

                    </td>
                </tr>
            </table>

        </body>
        </html>';

        return \App\Helpers\Mailer::send(
            $email,
            $name,
            $subject,
            $htmlBody
        );
    }
}
