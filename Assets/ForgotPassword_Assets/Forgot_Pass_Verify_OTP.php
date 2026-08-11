<?php
session_start();

require_once "../Config/Connection.php";
require_once "../Helpers/flash_message.php";

// ================= CHECK SESSION =================
if (!isset($_SESSION['forgot_email']) || !isset($_SESSION['reset_role'])) {
    setMsg("danger", "Session expired. Please try again.");
    header("Location: Forgot_Password.php");
    exit;
}

//Key for User
$userKey = $_SESSION['forgot_email'];

// ================= RESEND OTP =================
if (isset($_POST['resend_otp'])) {

    $email = $_SESSION['forgot_email'] ?? null;

    if (!$email) {
        setMsg("danger", "Email not found. Please try again.");
        header("Location: Forgot_Password.php");
        exit;
    }

    // 30 sec limit
    if (isset($_SESSION['last_otp_time']) && time() - $_SESSION['last_otp_time'] < 30) {
        setMsg("danger", "Wait 30 seconds before resend.");
        header("Location: Forgot_Pass_Verify_OTP.php");
        exit;
    }

    $_SESSION['last_otp_time'] = time();

    require "Send_Forgot_OTP.php";

    if (sendForgotOTP($email)) {
    
        setMsg("success", "New OTP sent successfully");
        header("Location: Forgot_Pass_Verify_OTP.php");
        exit;
    }
}

// ================= VERIFY OTP =================
if (isset($_POST['verify_otp'])) {

    $otp = trim(implode('', $_POST['otp'] ?? []));

    if (in_array('', $_POST['otp'])) {
        setMsg("danger", "Please enter complete OTP");
        header("Location: Forgot_Pass_Verify_OTP.php");
        exit;
    }

    if (!ctype_digit($otp) || strlen($otp) != 6) {
        setMsg("danger", "❌ Invalid OTP format");
        header("Location: Forgot_Pass_Verify_OTP.php");
        exit;
    }

    if (!isset($_SESSION['forgot_otp'])) {
        setMsg("danger", "Session expired. Try again.");
        header("Location: Forgot_Password.php");
        exit;
    }

    if (time() > $_SESSION['forgot_otp_expiry']) {
        setMsg("danger", "OTP expired. Please request again.");
        header("Location: Forgot_Password.php");
        exit;
    }

   $email = $_SESSION['forgot_email'];

// fetch attempts
$check = $con->prepare("SELECT * FROM otp_attempts WHERE EMAIL=?");
$check->bind_param("s", $email);
$check->execute();
$data = $check->get_result()->fetch_assoc();

if ($data && $data['ATTEMPTS'] >= 3 && (time() - $data['LAST_ATTEMPT_TIME']) < 300) {

    setMsg("danger", "Too many attempts. Try after 5 minutes.");
    header("Location: Forgot_Password.php");
    exit;
}

if ($otp != $_SESSION['forgot_otp']) {

    $check = $con->prepare("SELECT * FROM otp_attempts WHERE EMAIL=?");
    $check->bind_param("s", $email);
    $check->execute();
    $row = $check->get_result()->fetch_assoc();

    if ($row) {

        $attempts = $row['ATTEMPTS'] + 1;
        $now = time();

        $upd = $con->prepare("
            UPDATE otp_attempts 
            SET ATTEMPTS=?, LAST_ATTEMPT_TIME=?
            WHERE EMAIL=?
        ");
        $upd->bind_param("iis", $attempts, $now, $email);
        $upd->execute();

        // 🔥 send email on 3rd attempt
        if ($attempts >= 3) {
            require __DIR__ . "/Send_OTP_Lock_Email.php";
            sendOTPLockMail($email);
        }

    } else {

        $now = time();

        $ins = $con->prepare("
            INSERT INTO otp_attempts (EMAIL, ATTEMPTS, LAST_ATTEMPT_TIME)
            VALUES (?,1,?)
        ");
        $ins->bind_param("si", $email, $now);
        $ins->execute();
    }

    setMsg("danger", "Incorrect OTP ($attempts/3)");
    header("Location: Forgot_Pass_Verify_OTP.php");
    exit;
}

    if ($otp == $_SESSION['forgot_otp']) {

        $email = $_SESSION['forgot_email'];

        mysqli_query($con, "DELETE FROM otp_attempts WHERE EMAIL='$email'");

        // Existing
        unset($_SESSION['forgot_otp']);
        unset($_SESSION['forgot_otp_expiry']);

        setMsg("success", "OTP verified successfully");
        header("Location: Set_New_Password.php");
        exit;
    }

}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Verify OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Script file for cancle button on alert -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .otp-box {
            width: 45px;
            height: 50px;
            font-size: 18px;
            border: 1px solid #050708;
        }
    </style>

<body class="bg-light d-flex align-items-center justify-content-center vh-100">

    <?php include "../Helpers/loader.php"; ?>

    <div class="card shadow-lg border border-dark border-2 p-4"
        style="width:100%; max-width:420px; border-radius:15px;">

        <?php if (!empty($_SESSION['msg'])) {
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
        }
        ?>

        <!-- LOGO -->
        <img src="../Logo.svg" class="d-block mx-auto mb-3" width="70">

        <!-- TITLE -->
        <h4 class="text-center fw-bold mb-1">Verify OTP</h4>

        <p class="text-center text-muted small mb-4">
            Enter the 6-digit OTP sent to your email
        </p>

        <!-- FORM -->
        <form method="POST">

            <!-- OTP INPUT -->
            <div class="d-flex justify-content-between mb-4">

                <?php for ($i = 0; $i < 6; $i++) { ?>
                    <input type="text" name="otp[]" maxlength="1"
                        class="form-control text-center fw-bold otp-box" required>
                <?php } ?>

            </div>

            <!-- VERIFY BUTTON -->
            <button type="submit" name="verify_otp"
                class="btn btn-primary w-100 mb-2"
                onclick="showLoader()">
                Verify OTP
            </button>

            <!-- RESEND -->
            <div class="text-center">
                <button type="submit" name="resend_otp"
                    formnovalidate
                    class="btn btn-link text-decoration-none"
                    onclick="showLoader()">
                    Resend OTP
                </button>
            </div>

        </form>

    </div>

    <!-- OTP BOX STYLE -->
    <style>
        .otp-box {
            width: 45px;
            height: 50px;
            font-size: 18px;
            border: 1px solid #050708;
        }
    </style>

    <!-- OTP SCRIPT -->
    <script>
        const inputs = document.querySelectorAll('input[name="otp[]"]');

        inputs.forEach((input, i) => {

            input.addEventListener('input', () => {
                if (input.value && i < inputs.length - 1) {
                    inputs[i + 1].focus();
                }
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === "Backspace" && !input.value && i > 0) {
                    inputs[i - 1].focus();
                }
            });

            input.addEventListener('paste', (e) => {
                let data = e.clipboardData.getData('text').trim();

                if (data.length === inputs.length) {
                    e.preventDefault();
                    inputs.forEach((box, index) => {
                        box.value = data[index];
                    });
                }
            });

        });
    </script>

    <!-- Loader -->
    
    <!-- Loader -->
    <script src="../Helpers/loader.js?v=<?= filemtime('../Helpers/loader.js') ?>"></script>

    <!-- Script File for Alert DisAppear -->

    <script>
        setTimeout(() => {
            let alert = document.querySelector('.alert');
            if (alert) {
                alert.style.display = 'none';
            }
        }, 1000);
    </script>

</body>

</head>

</html>