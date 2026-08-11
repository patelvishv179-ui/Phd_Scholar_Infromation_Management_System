<?php
session_start();
require_once "Assets/Config/Connection.php";
require_once 'Assets/Helpers/flash_message.php';

if (!isset($_SESSION['form_data']) || !isset($_SESSION['otp'])) {
    setMsg("danger", "Unauthorized Access");
    header("Location: Registration.php");
    exit;
}

if (isset($_POST['resend_otp'])) {

    // email is given or not check
    $email = $_SESSION['form_data']['email'] ?? null;
    if (!$email) {
        setMsg("danger", "Email not found. Please register again.");
        header("Location: Registration.php");
        exit;
    }

    // Resend OTP Time Check Ensure OTP Resend only after every - 30 seconds
    if (isset($_SESSION['last_otp_time']) && time() - $_SESSION['last_otp_time'] < 30) {
        setMsg("danger", "Wait 30 seconds before resend");
        header("Location: Verify_OTP.php");
        exit;
    }

    // Update last OTP time to current time
    $_SESSION['last_otp_time'] = time();

    // 🔹 Send OTP
    require_once "Assets/Helpers/send_scholar_otp.php";

    if (sendScholarOTP($email)) {

        unset($_SESSION['otp_attempts']);

        setMsg("success", "New OTP sent to your email.");
    } else {

        setMsg("danger", "Failed to send OTP. Please try again.");
    }

    header("Location: Verify_OTP.php");
    exit;
}


// If form not submitted
if (!isset($_POST['otp'])) {
?>
    <!DOCTYPE html>
    <html>

    <head>
        <title>Verify OTP</title>

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

        <!-- 🔥 Custom Style -->
        <style>
            .otp-box {
                width: 45px;
                height: 50px;
                font-size: 18px;
                border: 1px solid #050708;
            }
        </style>

    </head>

    <body class="bg-light d-flex align-items-center justify-content-center vh-100">

         <?php include "Assets/Helpers/loader.php"; ?> 

        <div class="card shadow-lg border border-dark border-2 p-4" style="width:100%; max-width:420px; border-radius:15px;">

         <?php
if (isset($_SESSION['msg'])) {
    echo $_SESSION['msg'];
}
?>

            <!-- LOGO -->
            <img src="Assets/Logo.svg" class="d-block mx-auto mb-3" width="70">

            <!-- TITLE -->
            <h4 class="text-center fw-bold mb-1">Email Verification</h4>

            <p class="text-center text-muted small mb-4">
                Enter the 6-digit OTP sent to your email
            </p>

            <!-- FORM -->
            <form method="POST">

                <!-- OTP INPUT -->
                <div class="d-flex justify-content-between mb-4">

                    <input type="text" name="otp[]" maxlength="1" class="form-control text-center fw-bold otp-box" required>
                    <input type="text" name="otp[]" maxlength="1" class="form-control text-center fw-bold otp-box" required>
                    <input type="text" name="otp[]" maxlength="1" class="form-control text-center fw-bold otp-box" required>
                    <input type="text" name="otp[]" maxlength="1" class="form-control text-center fw-bold otp-box" required>
                    <input type="text" name="otp[]" maxlength="1" class="form-control text-center fw-bold otp-box" required>
                    <input type="text" name="otp[]" maxlength="1" class="form-control text-center fw-bold otp-box" required>

                </div>

                <!-- VERIFY BUTTON -->
                <button type="submit" name="verify_otp" class="btn btn-primary w-100 mb-2" onclick="showLoader()">
                    Verify OTP
                </button>

                <!-- RESEND -->
                <div class="text-center">
                    <button type="submit" name="resend_otp" formnovalidate class="btn btn-link text-decoration-none" onclick="showLoader()">
                        Resend OTP
                    </button>
                </div>

            </form>

        </div>


        <!-- OTP Input Script For Easy Paste and Move Foraward and Backward -->
        <!-- Tab thi agad Jay Key thi nai + Back space thi pachhad ave + Paste Support -->
        <script>
            const inputs = document.querySelectorAll('input[name="otp[]"]');

            // Auto move next
            inputs.forEach((input, i) => {
                input.addEventListener('input', () => {
                    if (input.value && i < inputs.length - 1) {
                        inputs[i + 1].focus();
                    }
                });

                // Backspace → previous
                input.addEventListener('keydown', (e) => {
                    if (e.key === "Backspace" && !input.value && i > 0) {
                        inputs[i - 1].focus();
                    }
                });

                // Paste support
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
        <script src="Assets/Helpers/loader.js?v=<?= filemtime('Assets/Helpers/loader.js') ?>"></script>

        <!-- OTP Message Destroy -->
    <?php
unset($_SESSION['msg']);
?>

    </body>

    </html>
<?php
    exit;
}

if (isset($_POST['verify_otp']) && isset($_POST['otp'])) {
    // ==============================
    // OTP VERIFY LOGIC (Same as yours)
    // ==============================

    $user_otp = trim(implode('', $_POST['otp']));

    if (in_array('', $_POST['otp'])) {
        setMsg("danger", "Please enter complete OTP");
        header("Location: Verify_OTP.php");
        exit;
    }

    if (!ctype_digit($user_otp) || strlen($user_otp) != 6) {
        setMsg("danger", "❌ Invalid OTP format");
        header("Location: Verify_OTP.php");
        exit;
    }

    if (!isset($_SESSION['otp'])) {
        setMsg("danger", "Session expired. Please register again.");
        header("Location: Registration.php");
        exit;
    }

    if (time() > $_SESSION['otp_expiry']) {
        setMsg("danger", "OTP expired. Please register again.");
        header("Location: Registration.php");
        exit;
    }

    $email = $_SESSION['form_data']['email'];

    $check = $con->prepare("SELECT * FROM register_otp_attempts WHERE EMAIL=?");
    $check->bind_param("s", $email);
    $check->execute();
    $row = $check->get_result()->fetch_assoc();

    if ($row && $row['ATTEMPTS'] >= 3 && (time() - $row['LAST_ATTEMPT_TIME']) < 300) {

        setMsg("danger", "Too many attempts. Try after 5 minutes.");
        header("Location: Registration.php");
        exit;
    }

    if ($user_otp != $_SESSION['otp']) {

        $now = time();

        $check = $con->prepare("SELECT * FROM register_otp_attempts WHERE EMAIL=?");
        $check->bind_param("s", $email);
        $check->execute();
        $row = $check->get_result()->fetch_assoc();

        if ($row) {

            $attempts = $row['ATTEMPTS'] + 1;

            $upd = $con->prepare("
            UPDATE register_otp_attempts 
            SET ATTEMPTS=?, LAST_ATTEMPT_TIME=?
            WHERE EMAIL=?
        ");
            $upd->bind_param("iis", $attempts, $now, $email);
            $upd->execute();
        } else {
            $attempts = 1;

            $ins = $con->prepare("
            INSERT INTO register_otp_attempts (EMAIL, ATTEMPTS, LAST_ATTEMPT_TIME)
            VALUES (?,1,?)
        ");
            $ins->bind_param("si", $email, $now);
            $ins->execute();
        }

        setMsg("danger", "Incorrect OTP ($attempts/3)");
        header("Location: Verify_OTP.php");
        exit;
    }

    // INSERT
    $data = $_SESSION['form_data'];
    $password = password_hash($data['password'], PASSWORD_DEFAULT);

    $stmt = $con->prepare(
        "INSERT INTO scholar_master
    (SCHOLAR_REGISTRATION_NUMBER,
     REGISTRATION_DATE,
     SCHOLAR_NAME,
     MOBILE,
     EMAIL,
     SCHOLAR_PASSWORD,
     FACULTY_ID,
     SUBJECT_ID)
     VALUES (?,?,?,?,?,?,?,?)"
    );

    $stmt->bind_param(
        "ssssssii",
        $data['regno'],
        $data['registration_date'],
        $data['scholar_name'],
        $data['mobile_number'],
        $data['email'],
        $password,
        $data['faculty'],
        $data['subject']
    );

    if ($stmt->execute()) {

        $del = $con->prepare("DELETE FROM register_otp_attempts WHERE EMAIL=?");
        $del->bind_param("s", $email);
        $del->execute();

        unset($_SESSION['otp']);
        unset($_SESSION['otp_expiry']);
        unset($_SESSION['form_data']);

        $_SESSION['regno'] = $data['regno'];
        header("Location: Scholar_Waiting.php");
        exit;
    } else {
        setMsg("danger", "Registration Failed");
        header("Location: Registration.php");
        exit;
    }
} else {
    header("Location: Verify_OTP.php");
    exit;
}
?>