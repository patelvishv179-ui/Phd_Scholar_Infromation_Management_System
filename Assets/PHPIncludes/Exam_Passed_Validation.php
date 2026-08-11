<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $exam_passed = trim($_POST['exam_passed'] ?? '');

    if ($exam_passed === "") {
        $errors[] = "Exam Passed field is required.";
    }
    elseif (!preg_match("/^[A-Za-z\s]+$/", $exam_passed)) {
        $errors[] = "Exam Passed can contain only letters and spaces.";
    }

}
?>
