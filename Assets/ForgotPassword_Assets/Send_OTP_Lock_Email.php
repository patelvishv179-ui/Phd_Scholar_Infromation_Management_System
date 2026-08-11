<?php

require_once(__DIR__ . "/../PHPMailer/Exception.php");
require_once(__DIR__ . "/../PHPMailer/PHPMailer.php");
require_once(__DIR__ . "/../PHPMailer/SMTP.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$config = require_once(__DIR__ . "/../Config/env.php");

function sendOTPLockMail($email)
{
    global $config;

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $config['MAIL_USER'];
        $mail->Password = $config['MAIL_PASS'];
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom($config['MAIL_USER'], 'HNGU Portal');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $mail->Subject = "Security Alert - OTP Blocked";

        $mail->addEmbeddedImage(
            realpath(__DIR__ . '/../logo.png'),
            'logo'
        );

        $mail->Body = "

<div style='margin:0; padding:0; background:#f4f6f9; width:100%;'>

<table width='100%' cellpadding='0' cellspacing='0' border='0' 
style='background:#f4f6f9; padding:20px 10px;'>

<tr>
<td align='center'>

<table width='100%' cellpadding='0' cellspacing='0' border='0'
style='max-width:600px; background:#ffffff; border-radius:12px;
overflow:hidden; font-family:Arial,sans-serif;'>

<tr>
<td style='padding:30px 20px; text-align:center;'>

<img src='cid:logo'
style='width:70px; max-width:100%; height:auto; display:block; margin:auto;'>

<h2 style='margin:15px 0 5px; color:#dc3545; font-size:28px;'>
HNGU Research Portal
</h2>

<p style='color:#666; font-size:14px; margin:0;'>
Security Notification
</p>

</td>
</tr>

<tr>
<td style='padding:0 20px;'>

<hr style='border:none; border-top:1px solid #eee;'>

<h3 style='color:#dc3545; font-size:24px; margin-top:25px;'>
OTP Verification Blocked 🔒
</h3>

<p style='font-size:16px; line-height:1.7; color:#444;'>
Too many incorrect OTP attempts were detected on your account.
</p>

<div style='text-align:center; margin:35px 0;'>

<span style='display:inline-block;
background:#dc3545;
color:#ffffff;
padding:14px 28px;
border-radius:8px;
font-size:16px;
font-weight:bold;'>

Blocked for 5 Minutes

</span>

</div>

<p style='font-size:15px; color:#444; line-height:1.6;'>
Please try again after 5 minutes.
</p>

<p style='font-size:14px; color:#888; line-height:1.6;'>
If this was not you, please secure your account immediately.
</p>

<hr style='border:none; border-top:1px solid #eee; margin-top:30px;'>

<p style='text-align:center; font-size:12px; color:#888;
padding-top:10px;'>

© " . date('Y') . " HNGU Portal

</p>

</td>
</tr>

</table>

</td>
</tr>

</table>

</div>

";

        $mail->send();

    } catch (Exception $e) {
        return false;
    }
}