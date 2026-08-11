<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // do NOT redeclare $errors here
    $university_board = trim($_POST['university_board'] ?? '');

    if ($university_board === "") {
        $errors[] = "University / Board field is required.";
    }
    elseif (!preg_match("/^[A-Za-z\s]+$/", $university_board)) {
        $errors[] = "University / Board can contain only letters and spaces.";
    }

}
?>
