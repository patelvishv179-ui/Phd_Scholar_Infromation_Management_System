<?php
$email = $_POST['email'] ?? '';
$email = trim($email);
$old['email'] = $email;

// Required
if ($email === '') {
    $errors['email'] = "Email address is required";
}
// Space check
elseif (preg_match('/\s/', $email)) {
    $errors['email'] = "Spaces are not allowed";
}
// Allowed characters: letters, numbers, dot, @
elseif (!preg_match('/^[A-Za-z0-9.@]+$/', $email)) {
    $errors['email'] = "Only letters, numbers, '.' and '@' are allowed";
}
// Email format check
elseif (!preg_match('/^[A-Za-z0-9.]+@[A-Za-z0-9.]+\.[A-Za-z]{2,}$/', $email)) {
    $errors['email'] = "Invalid email address format";
}
?>
