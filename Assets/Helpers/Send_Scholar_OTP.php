<?php

require_once "Assets/PHPMailer/Exception.php";
require_once "Assets/PHPMailer/PHPMailer.php";
require_once "Assets/PHPMailer/SMTP.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$config = require_once "Assets/Config/env.php";

function sendScholarOTP($email)
{
    global $config;

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // 🔹 Generate OTP
    $otp = rand(100000, 999999);

    // 🔹 Store in session
    $_SESSION['otp'] = $otp;
    $_SESSION['otp_email'] = $email;
    $_SESSION['otp_expiry'] = time() + 600; // 10 min

    // 🔹 Mail setup
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
        $mail->CharSet = 'UTF-8'; //Icon support
        $mail->Subject = "OTP Verification - HNGU Portal";

        $mail->addEmbeddedImage(
    realpath(__DIR__ . '/../logo.png'),
    'logo'
);
        // 🔹 SIMPLE EMAIL BODY
        $mail->Body = "

<div style='margin:0; padding:0; background:#f4f6f9; width:100%;'>

<table width='100%' cellpadding='0' cellspacing='0' border='0'
style='background:#f4f6f9; padding:20px 10px;'>

<tr>
<td align='center'>

<table width='100%' cellpadding='0' cellspacing='0' border='0'
style='max-width:600px;
background:#ffffff;
border:1px solid #dcdcdc;
border-radius:12px;
overflow:hidden;
font-family:Arial,sans-serif;'>

<!-- HEADER -->
<tr>
<td style='padding:30px 20px 20px; text-align:center;'>

<img src='cid:logo'
style='width:70px;
max-width:100%;
height:auto;
display:block;
margin:auto auto 15px;'>

<h2 style='margin:0;
color:#2563eb;
font-size:28px;
font-weight:bold;'>

HNGU Research Portal

</h2>

</td>
</tr>

<!-- CONTENT -->
<tr>
<td style='padding:0 25px 30px;'>

<hr style='border:none; border-top:1px solid #eeeeee; margin-bottom:25px;'>

<h3 style='margin-top:0;
color:#333333;
font-size:24px;'>

Email Verification

</h3>

<p style='font-size:15px;
line-height:1.8;
color:#555555;'>

Dear User,<br><br>

Use the following One-Time Password (OTP)
to verify your email address:

</p>

<!-- OTP BOX -->
<div style='text-align:center; margin:35px 0;'>

<span style='display:inline-block;
background:#2563eb;
color:#ffffff;
padding:16px 32px;
font-size:30px;
font-weight:bold;
letter-spacing:6px;
border-radius:10px;'>

$otp

</span>

</div>

<p style='font-size:15px;
line-height:1.7;
color:#555555;'>

This OTP is valid for
<b>10 minutes</b>.
Please do not share this code with anyone.

</p>

<p style='font-size:14px;
line-height:1.6;
color:#888888;'>

If you did not request this,
please ignore this email.

</p>

<hr style='border:none;
border-top:1px solid #eeeeee;
margin-top:30px;'>

<p style='font-size:12px;
color:#888888;
text-align:center;
margin-top:20px;'>

© " . date('Y') . " HNGU Portal.
All rights reserved.

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

        return true;

    } catch (Exception $e) {
        return false;
    }
}