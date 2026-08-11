<?php

require_once "Assets/PHPMailer/Exception.php";
require_once "Assets/PHPMailer/PHPMailer.php";
require_once "Assets/PHPMailer/SMTP.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$config = require "Assets/Config/env.php";

function sendRejectMail($email, $name)
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
        $mail->Subject = 'Scholar Rejected';

        $mail->addEmbeddedImage(
            realpath(__DIR__ . '/../logo.png'),
            'logo'
        );

        $mail->Body = "
<div style='font-family: Arial, sans-serif; background:#f4f6f9; padding:20px;'>

    <div style='max-width:600px; margin:auto; border:1px solid #ddd; background:white; padding:30px; border-radius:10px; box-shadow:0 0 10px #ddd;'>

        <!-- HEADER -->
        <h2 style='color:#dc2626; text-align:center;'>
            <img src='cid:logo' style='height:70px; margin-bottom:10px;'><br>
            HNGU Research Portal
        </h2>

        <hr>

        <!-- TITLE -->
        <h3 style='color:#333; text-align:center;'>
            ❌ Application Rejected
        </h3>

        <!-- MESSAGE -->
        <p style='font-size:15px; color:#555;'>
            Dear <b>$name</b>,<br><br>

            We regret to inform you that your <b>Ph.D. scholar application</b> has been 
            <span style='color:#dc2626; font-weight:bold;'>rejected</span>.
        </p>

        <p style='font-size:15px; color:#555;'>
            This decision has been taken after careful review by the concerned authority.
        </p>

        <!-- NOTE -->
        <p style='font-size:14px; color:#555;'>
            You may contact the administration for further clarification or re-apply if applicable.
        </p>

        <p style='font-size:14px; color:#888; line-height:1.6;'>
    If you believe this decision was made in error, please contact our support team for assistance.<br><br>

   <b>Support Team:</b> support@hngu.edu.in <br>

    Thank you for your understanding.
</p>

        <hr>

        <!-- FOOTER -->
        <p style='font-size:13px; color:#888; text-align:center;'>
            © " . date('Y') . " HNGU Portal. All rights reserved.
        </p>

    </div>

</div>";

        $mail->send();
    } catch (Exception $e) {}
}

?>