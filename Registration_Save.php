<?php

session_start();
$errors = [];
$old = [];

require_once "Assets/Config/Connection.php";
require "Assets/Helpers/flash_message.php";

// <=== Registration Date Validation + Get Value =======> //
require_once "./Assets/PHPIncludes/Registration_Date_Validation.php";
$registration_date = filter_input(INPUT_POST, 'registration_date', FILTER_SANITIZE_SPECIAL_CHARS);

// <=== Scholar Name Validation + Get Value =======> //
require_once "./Assets/PHPIncludes/Scholar_Name_Validation.php";
$scholar_name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');

// <=== Mobile Validation + Get Value =======> //
require_once "./Assets/PHPIncludes/Mobile_Validation.php";
$mobile_number = htmlspecialchars($mobile, ENT_QUOTES, 'UTF-8');

// <=== Email Validation + Get Value =======> //
require_once "./Assets/PHPIncludes/Email_Validation.php";
$email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');

// <=== Password Validation + Get Value =======> //
require_once "./Assets/PHPIncludes/Password_Validation.php";

// <=== Confirm Password Validation =======> //
require_once "./Assets/PHPIncludes/Confirm_Password_Validation.php";

// <=== Faculty and subjedct Validation + Get Value =======> //
require_once "./Assets/PHPIncludes/Faculty_Subject_Validation.php";
$faculty = filter_input(INPUT_POST, 'faculty', FILTER_SANITIZE_NUMBER_INT);
$subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_NUMBER_INT);

// ================================
// DUPLICATE CHECK
// ================================

// Check Email
$chkEmail = $con->prepare(
    "SELECT 1 FROM scholar_master WHERE EMAIL=? LIMIT 1"
);
$chkEmail->bind_param("s", $email);
$chkEmail->execute();
$chkEmail->store_result();

if ($chkEmail->num_rows > 0) {
    $errors['email'] = "Email already registered";
}
$chkEmail->close();

// Check Mobile
$chkMobile = $con->prepare(
    "SELECT 1 FROM scholar_master WHERE MOBILE=? LIMIT 1"
);
$chkMobile->bind_param("s", $mobile_number);
$chkMobile->execute();
$chkMobile->store_result();

if ($chkMobile->num_rows > 0) {
    $errors['mobile_number'] = "Mobile number already registered";
}
$chkMobile->close();

// Check Scholar Name
$chkName = $con->prepare(
    "SELECT 1 FROM scholar_master WHERE SCHOLAR_NAME=? LIMIT 1"
);
$chkName->bind_param("s", $scholar_name);
$chkName->execute();
$chkName->store_result();

if ($chkName->num_rows > 0) {
    $errors['scholar_name'] = "Scholar name already exists";
}
$chkName->close();


if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    $_SESSION['old'] = $old;
    header("Location: Registration.php");
    exit;
}

/* SUCCESS */
unset($_SESSION['errors'], $_SESSION['old']);
$password = password_hash($password_raw, PASSWORD_DEFAULT);


/* Get subject name (for code) */
$stmt1 = $con->prepare(
    "SELECT subject_name 
     FROM subject_available 
     WHERE subject_id=?"
);
$stmt1->bind_param("i", $subject);
$stmt1->execute();
$res1 = $stmt1->get_result()->fetch_assoc();
$sub4 = strtoupper(substr($res1['subject_name'], 0, 4));
$stmt1->close();

//Registration number generation

$year = date("Y");
/* Get last registration number for same SUBJECT + YEAR */
$pattern = "PHD" . $sub4 . $year . "%";

$stmt2 = $con->prepare(
    "SELECT SCHOLAR_REGISTRATION_NUMBER
     FROM scholar_master
     WHERE SCHOLAR_REGISTRATION_NUMBER LIKE ?
     ORDER BY SCHOLAR_REGISTRATION_NUMBER DESC
     LIMIT 1"
);

$stmt2->bind_param("s", $pattern);
$stmt2->execute();
$r2 = $stmt2->get_result();

if ($r2->num_rows > 0) {
    $last = $r2->fetch_assoc();

    // Extract numeric part after year
    $num = intval(substr($last['SCHOLAR_REGISTRATION_NUMBER'], strlen("PHD" . $sub4 . $year)));

    $next = $num + 1;
} else {
    $next = 1; // First entry
}

$stmt2->close();

/* Final Registration Number */
$regno = "PHD" . $sub4 . $year . $next;


// Store form data in session
$_SESSION['form_data'] = [
    'regno' => $regno,
    'registration_date' => $registration_date,
    'scholar_name' => $scholar_name,
    'mobile_number' => $mobile_number,
    'email' => $email,
    'password' => $password_raw,
    'faculty' => $faculty,
    'subject' => $subject
];

$_SESSION['email'] = $_SESSION['form_data']['email'];

$check = $con->prepare("SELECT * FROM register_otp_attempts WHERE EMAIL=?");
$check->bind_param("s", $email);
$check->execute();
$row = $check->get_result()->fetch_assoc();

if ($row && $row['ATTEMPTS'] >= 3 && (time() - $row['LAST_ATTEMPT_TIME']) < 300) {

    $remaining = 300 - (time() - $row['LAST_ATTEMPT_TIME']);
    $minutes = ceil($remaining / 60);

    $_SESSION['old'] = [
        'role' => '',
        'login_id' => $email
    ];

    setMsg("danger", "Too many attempts. Try after $minutes minutes.");
    header("Location: Registration.php");
    exit;
}

require_once 'Assets/Helpers/Send_Scholar_OTP.php';

if (sendScholarOTP($email)) {

    setMsg("success", "OTP sent successfully to your email.");

} else {

    setMsg("danger", "Unable to send OTP.");

    header("Location: Registration.php");
    exit;
}

header("Location: Verify_OTP.php");
exit;