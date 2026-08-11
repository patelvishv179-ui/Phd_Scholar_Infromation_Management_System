<?php

// ===============================
// Get values safely
// ===============================
$faculty = filter_input(INPUT_POST, 'faculty', FILTER_SANITIZE_NUMBER_INT);
$subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_NUMBER_INT);

// Trim
$faculty = trim($faculty ?? '');
$subject = trim($subject ?? '');

// ===============================
// Store old values
// ===============================
$old['faculty'] = $faculty;
$old['subject'] = $subject;

// ===============================
// Validation
// ===============================

// Faculty required
if ($faculty === '') {
    $errors['faculty'] = "Please select faculty";
}

// Subject required (only if faculty selected)
elseif ($subject === '') {
    $errors['subject'] = "Please select subject";
}

// Invalid numeric protection
elseif (!ctype_digit($faculty) || !ctype_digit($subject)) {
    $errors['faculty'] = "Invalid selection";
}

?>
