<?php

session_start();

require_once "../Config/Connection.php";
require "Send_Forgot_OTP.php";
require_once __DIR__ . "/../Helpers/flash_message.php";

if (isset($_POST['send_otp'])) {

    $email_input = trim($_POST['email']);
    $role  = $_POST['role'];

    $_SESSION['old'] = $_POST;

    // ================= EMPTY CHECK =================
    if ($email_input == "" || $role == "") {
        setMsg("danger", "All Fields are Required !");
        header("Location: Forgot_Password.php");
        exit;
    }

    // ================= FIND USER =================
    if ($role == "admin") {
        $table = "Admin_Master";
        $column = "ADMIN_EMAIL";
    } else {
        $table = "Scholar_Master";
        $column = "EMAIL";
    }

    $stmt = $con->prepare("SELECT * FROM $table WHERE $column=?");
    $stmt->bind_param("s", $email_input);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows == 0) {
        setMsg("danger", "User Not Found !");
        header("Location: Forgot_Password.php");
        exit;
    }

    $forgdata = $res->fetch_assoc();
    $email = $forgdata[$column]; // 🔥 actual DB email

    // ================= BLOCK CHECK (IMPORTANT) =================
    $res2 = mysqli_query($con, "SELECT * FROM otp_attempts WHERE EMAIL='$email'");
    $data = mysqli_fetch_assoc($res2);

    if ($data && $data['ATTEMPTS'] >= 3 && time() - $data['LAST_ATTEMPT_TIME'] < 300) {
        setMsg("danger", "Too many attempts. Try after 5 minutes.");
        header("Location: Forgot_Password.php");
        exit;
    }

    // ================= SEND OTP =================
    if (sendForgotOTP($email)) {

        unset($_SESSION['old']);

        $_SESSION['forgot_email'] = $email;
        $_SESSION['reset_role'] = $role;

        setMsg("success", "OTP sent successfully");
        header("Location: Forgot_Pass_Verify_OTP.php");
        exit;
    }
}
?>
<html>

<body>

    <head>
        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- REMIX ICONS CDN -->
        <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
        <!-- Script file for cancle button on alert -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </head>

    <div class="container d-flex justify-content-center align-items-center vh-100">

        <div class="card shadow border-0" style="width:100%; max-width:420px; border-radius:12px;">

            <div class="card-body pt-5 pb-5 ps-4 pe-4 border border-dark rounded border-2">

                <!-- LOGO -->
                <img src="../Logo.svg" class="d-block mx-auto mb-3" width="70">

                <h4 class="text-center mb-4 fw-bold text-primary">
                    Forgot Password
                </h4>

                <form method="POST">

                    <!-- Message -->

                    <?php if (!empty($_SESSION['msg'])) {
                        echo $_SESSION['msg'];
                        unset($_SESSION['msg']);
                    }
                    ?>

                    <!-- ROLE -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Select Role</label>
                        <select name="role" class="form-select">
                            <option value="">-- Select Role --</option>

                            <option value="scholar"
                                <?= (($_SESSION['old']['role'] ?? '') == 'scholar') ? 'selected' : '' ?>>
                                Scholar
                            </option>

                            <option value="admin"
                                <?= (($_SESSION['old']['role'] ?? '') == 'admin') ? 'selected' : '' ?>>
                                Admin
                            </option>
                        </select>

                    </div>

                    <!-- EMAIL -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email Address</label>
                        <input type="email" name="email" class="form-control"
                            value="<?= $_SESSION['old']['email'] ?? '' ?>"
                            placeholder="Enter your email">
                    </div>

                    <!-- BUTTON -->
                    <button type="submit" name="send_otp"
                        class="btn btn-primary w-100 fw-semibold">
                        Send OTP
                    </button>

                </form>

                <!-- BACK TO LOGIN -->
                <p class="text-center mt-3 mb-0 small">
                    Remember your password?
                    <a href="/BACKUP/login.php" class="text-primary fw-semibold">
                        Login
                    </a>
                </p>

            </div>

        </div>

    </div>

    <!-- Script File for Alert DisAppear -->

    <script>
        setTimeout(() => {
            let alert = document.querySelector('.alert');
            if (alert) {
                alert.style.display = 'none';
            }
        }, 2000);
    </script>

</body>

</html>