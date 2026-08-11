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

// ================= UPDATE PASSWORD =================
if (isset($_POST['update_pass'])) {

    $pass  = trim($_POST['new_pass']);
    $cpass = trim($_POST['confirm_pass']);

    if ($pass == "" || $cpass == "") {
        setMsg("danger", "All fields are required");
        header("Location: Set_New_Password.php");
        exit;
    }

    if ($pass !== $cpass) {
        setMsg("danger", "Passwords do not match");
        header("Location: Set_New_Password.php");
        exit;
    }

    if (strlen($pass) < 6) {
        setMsg("danger", "Password must be at least 6 characters");
        header("Location: Set_New_Password.php");
        exit;
    }

    $email = $_SESSION['forgot_email'];
    $role  = $_SESSION['reset_role'];

    // 🔹 Table + Column
    if ($role == "admin") {
        $table = "Admin_Master";
        $email_col = "ADMIN_EMAIL";
        $pass_col  = "ADMIN_PASSWORD";
    } else {
        $table = "Scholar_Master";
        $email_col = "EMAIL";
        $pass_col  = "SCHOLAR_PASSWORD";
    }

    // 🔐 HASH PASSWORD (IMPORTANT)
    $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

    $stmt = $con->prepare("UPDATE $table SET $pass_col=? WHERE $email_col=?");
    $stmt->bind_param("ss", $hashed_pass, $email);

    if ($stmt->execute()) {

        // 🔥 CLEAR SESSION
        unset($_SESSION['forgot_email']);
        unset($_SESSION['reset_role']);

        setMsg("success", "Password updated successfully. Please login.");
        header("Location: ../../login.php");
        exit;
    } else {
        setMsg("danger", "Error updating password. Try again.");
        header("Location: Set_New_Password.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Set New Password</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- REMIX ICONS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

</head>

<body class="bg-light d-flex align-items-center justify-content-center vh-100">

    <?php include "../Helpers/loader.php"; ?>

    <div class="card shadow-lg border border-dark border-2 p-4"
        style="width:100%; max-width:420px; border-radius:15px;">

        <!-- MESSAGE -->
        <?php if (!empty($_SESSION['msg'])) {
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
        }
        ?>

        <!-- LOGO -->
        <img src="../Logo.svg" class="d-block mx-auto mb-3" width="70">

        <!-- TITLE -->
        <h4 class="text-center fw-bold mb-1">Set New Password</h4>

        <p class="text-center text-muted small mb-4">
            Enter your new password below
        </p>

        <!-- FORM -->
        <form method="POST">

            <!-- NEW PASSWORD -->
            <div class="mb-3 position-relative">
                <label class="form-label fw-semibold">New Password</label>

                <input type="password" name="new_pass" id="new_pass"
                    class="form-control pe-5"
                    placeholder="Enter new password" required>

                <i onclick="togglePassword('new_pass', this)"
                    class="ri-eye-off-line position-absolute end-0 me-3"
                    style="top: 38px; cursor:pointer;">
                </i>
            </div>


            <!-- CONFIRM PASSWORD -->
            <div class="mb-3 position-relative">
                <label class="form-label fw-semibold">Confirm Password</label>

                <input type="password" name="confirm_pass" id="confirm_pass"
                    class="form-control pe-5"
                    placeholder="Confirm password" required>

                <i onclick="togglePassword('confirm_pass', this)"
                    class="ri-eye-off-line position-absolute end-0 me-3"
                    style="top: 38px; cursor:pointer;">
                </i>
            </div>

            <!-- BUTTON -->
            <button type="submit" name="update_pass"
                class="btn btn-primary w-100 fw-semibold"
                onclick="showLoader()">
                Update Password
            </button>

        </form>

    </div>

    <script>
        function togglePassword(inputId, icon) {

            let input = document.getElementById(inputId);

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("ri-eye-off-line");
                icon.classList.add("ri-eye-line");
            } else {
                input.type = "password";
                icon.classList.remove("ri-eye-line");
                icon.classList.add("ri-eye-off-line");
            }
        }
    </script>

    <script src="../Helpers/loader.js"></script>

</body>

</html>