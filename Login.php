<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- REMIX ICONS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <!-- Script file for cancle button on alert -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Fevicon -->
    <link rel="shortcut icon" href="Assets/ICON.ico"  type="image/x-icon">

</head>

<body class="bg-light d-flex align-items-center justify-content-center vh-100">

    <?php include "Assets/Helpers/loader.php"; ?>

    <div class="card shadow p-4 border border-dark border-2" style="width: 100%; max-width: 450px;">

        <!-- LOGO -->
        <img src="Assets/Logo.svg" class="d-block mx-auto mb-3" width="100">

        <h5 class="text-center mb-4 fw-semibold">PHD Users Login</h5>

        <?php
        echo $_SESSION['msg'] ?? '';
        unset($_SESSION['msg']);
        ?>

        <form method="POST" action="login_process.php" onsubmit="return validateForm()">

            <!-- ROLE -->
            <label class="form-label">Select Role</label>
            <select name="role" id="role" class="form-select mb-4">
               <option value="" disabled <?= !isset($_SESSION['old']['role']) ? 'selected' : '' ?>>
    -- Select Role --
</option>

<option value="scholar" <?= (($_SESSION['old']['role'] ?? '') == 'scholar') ? 'selected' : '' ?>>
    Scholar
</option>

<option value="admin" <?= (($_SESSION['old']['role'] ?? '') == 'admin') ? 'selected' : '' ?>>
    Admin
</option>
            </select>

            <!-- EMAIL OR MOBILE -->
            <label class="form-label">Email / Mobile</label>
            <input type="text" name="login_id" id="login_id"
                value="<?= $_SESSION['old']['login_id'] ?? '' ?>"
                class="form-control mb-4"
                placeholder="Enter Email or Mobile">

            <!-- PASSWORD -->
            <label class="form-label">Password</label>

            <div class="position-relative mb-3">

                <input type="password" name="password" id="password"
                    class="form-control pe-5"
                    placeholder="Enter Password">

                <i onclick="togglePassword()" id="eyeIcon"
                    class="ri-eye-off-line position-absolute top-50 end-0 translate-middle-y me-3 cursor-pointer">
                </i>

            </div>

            <!-- BUTTON -->
            <button type="submit"
                class="btn btn-primary w-100"
                onclick="showLoader()">
                Login
            </button>

            <!-- REGISTER LINK -->
            <p class="text-center mt-4 small">
                New Scholar?
                <a href="Registration.php" class="text-primary fw-semibold" onclick="showLoader()">Register</a>
                |
                <a href="Assets/ForgotPassword_Assets/Forgot_Password.php" class="text-primary fw-semibold text-decoration-none">
                    Forgot Password?
                </a>
            </p>

        </form>
    </div>


    <!-- 🔥 SIMPLE JS VALIDATION -->
    <script>
        function validateForm() {
            let role = document.getElementById("role").value;
            let login = document.getElementById("login_id").value.trim();
            let pass = document.getElementById("password").value.trim();

            if (!role || !login || !pass) {
                alert("All fields are required");
                hideLoader(); // 🔥 important
                return false;
            }

            showLoader();
            return true;
        }


        function togglePassword() {
            let pass = document.getElementById("password");
            let icon = document.getElementById("eyeIcon");

            if (pass.type === "password") {
                pass.type = "text";
                icon.classList.remove("ri-eye-off-line");
                icon.classList.add("ri-eye-line");
            } else {
                pass.type = "password";
                icon.classList.remove("ri-eye-line");
                icon.classList.add("ri-eye-off-line");
            }
        }
    </script>

        <!-- Script File for Alert DisAppear -->

    <script>
        setTimeout(() => {
            let alert = document.querySelector('.alert');
            if (alert) {
                alert.style.display = 'none';
            }
        }, 2000);
    </script>

    <!-- Loader -->
    <script src="Assets/Helpers/loader.js?v=<?= filemtime('Assets/Helpers/loader.js') ?>"></script>

</body>

</html>