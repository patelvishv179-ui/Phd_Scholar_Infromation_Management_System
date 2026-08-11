<?php
session_start();

require_once 'Assets/Helpers/flash_message.php';

if (!isset($_SESSION['regno'])) {
    setMsg("danger", "Please Register First Then Try Again!");
    header("Location: Registration.php");
    exit;
}

$regno = $_SESSION['regno'] ?? "N/A";
// clear session after use
unset($_SESSION['regno']);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Waiting Approval</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light d-flex align-items-center justify-content-center vh-100">

<?php include "Assets/Helpers/loader.php" ?>

<div class="card shadow-lg text-center p-4" style="width:100%; max-width:420px; border-radius:15px;">

    <!-- LOGO -->
    <img src="Assets/Logo.svg" class="d-block mx-auto mb-3" width="100">

    <!-- TITLE -->
    <h4 class="fw-semibold text-success">
        Registration Successful 🎉
    </h4>

    <!-- MESSAGE -->
    <p class="text-muted mt-3 mb-1">
        Email Verified Successfully.
    </p>

    <p class="fw-semibold text-dark">
        Registration No: <?= htmlspecialchars($regno) ?>
    </p>

    <p class="text-muted mt-3">
        Your registration request has been sent to the authority.<br>
    </p>

    <b><p class="text-muted">
    You will be notified via email once your account is approved.
    </p></b>

    <!-- BACK BUTTON -->
    <a href="Login.php"
       class="btn btn-primary mt-3"
       onclick="showLoader()">
        Go to Login
    </a>

</div>


    <!-- Loader -->
    <script src="Assets/Helpers/loader.js?v=<?= filemtime('Assets/Helpers/loader.js') ?>"></script>

</body>
</html>