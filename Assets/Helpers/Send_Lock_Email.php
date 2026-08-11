<?php

require_once "Assets/PHPMailer/Exception.php";
require_once "Assets/PHPMailer/PHPMailer.php";
require_once "Assets/PHPMailer/SMTP.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$config = require_once "Assets/Config/env.php";

function sendLockMail($email)
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

        $mail->Subject = "Security Alert - Account Locked";

        // Logo embed
        $mail->addEmbeddedImage(
            realpath(__DIR__ . '/../logo.png'),
            'logo'
        );

        // 🔥 SAME DESIGN STYLE AS OTP MAIL
        $mail->Body = "
<div style='font-family: Arial, sans-serif; background:#f4f6f9; padding:20px;'>

    <div style='max-width:600px; margin:auto; border:1px solid #131212; background:white; padding:30px; border-radius:10px; box-shadow:0 0 10px #ddd;'>

        <h2 style='color:#dc3545; text-align:center;'>
            <img src='cid:logo' style='height:70px; margin-bottom:10px;'><br>
            HNGU Research Portal
        </h2>

        <hr>

        <h3 style='color:#dc3545;'>Account Locked 🔒</h3>

        <p style='font-size:15px; color:#555;'>
            Dear User,<br><br>
            We detected multiple unsuccessful login attempts on your account.
        </p>

        <div style='text-align:center; margin:25px 0;'>
            <span style='display:inline-block; background:#dc3545; color:white; 
                         padding:12px 25px; font-size:18px; font-weight:bold; 
                         border-radius:8px;'>
                Your Account is Locked for 10 Minutes
            </span>
        </div>

        <p style='font-size:14px; color:#555;'>
            For your security, your account has been temporarily locked.<br><br>
            You can try logging in again after <b>10 minutes</b>.
        </p>

        <p style='font-size:14px; color:#555;'>
            If this was not you, we strongly recommend changing your password immediately.
        </p>

        <p style='font-size:14px; color:#888;'>
            If you need help, please contact support.
        </p>

        <hr>

        <p style='font-size:13px; color:#888; text-align:center;'>
            © " . date('Y') . " HNGU Portal. All rights reserved.
        </p>

    </div>
</div>";

        $mail->send();
        return true;

    } catch (Exception $e) {
        return false;
    }
}