<?php

require_once "../PHPMailer/Exception.php";
require_once "../PHPMailer/PHPMailer.php";
require_once "../PHPMailer/SMTP.php";   

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$config = require_once "../Config/env.php";

function sendForgotOTP($email)
{
    global $config;

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // 🔹 Generate OTP
    $otp = rand(100000, 999999);

    // 🔹 Store in session
    $_SESSION['forgot_otp'] = $otp;
    $_SESSION['forgot_email'] = $email;
    $_SESSION['forgot_otp_expiry'] = time() + 600; // 10 min

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
        $mail->CharSet = 'UTF-8';

        $mail->Subject = "Password Reset OTP - HNGU Portal";

        // Logo
        $mail->addEmbeddedImage(
            realpath(__DIR__ . '/../logo.png'),
            'logo'
        );

        // 🔹 Email Body
        $mail->Body = "
<div style='font-family: Arial, sans-serif; background:#f4f6f9; padding:20px;'>

    <div style='max-width:600px; margin:auto; border:1px solid #ddd; background:white; padding:30px; border-radius:10px;'>

        <h2 style='color:#2563eb; text-align:center;'>
            <img src='cid:logo' style='height:70px;'><br>
            HNGU Research Portal
        </h2>

        <hr>

        <h3>Password Reset OTP</h3>

        <p>
            Use the following OTP to reset your password:
        </p>

        <div style='text-align:center; margin:25px 0;'>
            <span style='background:#2563eb; color:white; padding:15px 30px; font-size:28px; font-weight:bold; letter-spacing:5px; border-radius:8px;'>
                $otp
            </span>
        </div>

        <p>This OTP is valid for <b>10 minutes</b>.</p>

        <p style='color:#888;'>If you did not request this, ignore this email.</p>

        <hr>

        <p style='font-size:13px; text-align:center; color:#888;'>
            © " . date('Y') . " HNGU Portal
        </p>

    </div>
</div>";

        $mail->send();

        return true;

    } catch (Exception $e) {
        return false;
    }
}