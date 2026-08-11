<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // do NOT redeclare $errors here
    $subject_offered = trim($_POST['subject_offered'] ?? '');

    if ($subject_offered === '') {
        $errors[] = "Subject Offered field is required.";
    }
    elseif (!preg_match("/^[A-Za-z\s]+$/", $subject_offered)) {
        $errors[] = "Subject Offered can contain only letters and spaces.";
    }

}
?>
