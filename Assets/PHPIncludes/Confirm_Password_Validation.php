<?php
$pass  = trim($_POST['password'] ?? '');
$cpass = trim($_POST['cpassword'] ?? '');

if ($cpass === '') {
    $errors['cpassword'] = "Confirm Password is required";
}
elseif ($cpass !== $pass) {
    $errors['cpassword'] = "Passwords do not match";
}
?>