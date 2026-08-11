<?php

require_once "Assets/PHPMailer/Exception.php";
require_once "Assets/PHPMailer/PHPMailer.php";
require_once "Assets/PHPMailer/SMTP.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$config = require "Assets/Config/env.php";

function sendApprovalMail($email, $name)
{
    global $config;

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $config['MAIL_USER'];
        $mail->Password = $config['MAIL_PASS'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom($config['MAIL_USER'], 'HNGU Portal');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = "Scholar Approved - HNGU Portal";

        $mail->addEmbeddedImage(
            realpath(__DIR__ . '/../logo.png'),
            'logo'
        );

       $mail->Body = "
<div style='font-family: Arial, sans-serif; background:#f4f6f9; padding:20px;'>

    <div style='max-width:600px; margin:auto; border:1px solid #ddd; background:white; padding:30px; border-radius:10px; box-shadow:0 0 10px #ddd;'>

        <!-- HEADER -->
        <h2 style='color:#2563eb; text-align:center;'>
            <img src='cid:logo' style='height:70px; margin-bottom:10px;'><br>
            HNGU Research Portal
        </h2>

        <hr>

        <!-- TITLE -->
        <h3 style='color:#333; text-align:center;'>
            🎉 Scholar Approval Notification
        </h3>

        <!-- MESSAGE -->
        <p style='font-size:15px; color:#555;'>
            Dear <b>$name</b>,<br><br>

            We are pleased to inform you that your registration as a 
            <b>Ph.D. Scholar</b> has been <span style='color:green; font-weight:bold;'>approved successfully</span>.
        </p>

        <p style='font-size:15px; color:#555;'>
            You can now login to the portal and complete your profile to continue your academic journey.
        </p>

        <!-- BUTTON -->
        <div style='text-align:center; margin:25px 0;'>
            <a href='http://local/BACKUP/login.php'
               style='display:inline-block; background:#2563eb; color:white; 
                      padding:12px 25px; font-size:16px; font-weight:bold; 
                      text-decoration:none; border-radius:8px;'>
                Login Now
            </a>
        </div>

        <!-- NOTE -->
        <p style='font-size:14px; color:#555;'>
            Please complete your profile to access all features of the portal.
        </p>

        <p style='font-size:14px; color:#888;'>
            Thank You !
        </p>

        <hr>

        <!-- FOOTER -->
        <p style='font-size:13px; color:#888; text-align:center;'>
            © " . date('Y') . " HNGU Portal. All rights reserved.
        </p>

    </div>

</div>";

        $mail->send();
        return true;

    } catch (Exception $e) {
        echo $mail->ErrorInfo; // debug
        return false;
    }
}

?>