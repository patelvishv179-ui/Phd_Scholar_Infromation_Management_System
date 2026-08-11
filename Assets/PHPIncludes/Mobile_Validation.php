<?php
$mobile = $_POST['mobile_number'] ?? '';
$mobile = trim($mobile);
$old['mobile_number'] = $mobile;
if ($mobile === '') {
    $errors['mobile_number'] = "Mobile number is required";
}
elseif (preg_match('/\s/', $mobile)) {
    $errors['mobile_number'] = "Spaces are not allowed";
}
elseif (preg_match('/[A-Za-z]/', $mobile)) {
    $errors['mobile_number'] = "Alphabets are not allowed";
}
elseif (!ctype_digit($mobile)) {
    $errors['mobile_number'] = "Special characters are not allowed";
}
elseif (strlen($mobile) !== 10) {
    $errors['mobile_number'] = "Mobile number must be exactly 10 digits";
}
?>