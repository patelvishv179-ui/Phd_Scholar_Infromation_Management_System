<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // do NOT redeclare $errors here
    $school_college = trim($_POST['school_college'] ?? '');

    if ($school_college === "") {
        $errors[] = "School / College field is required.";
    }
    elseif (!preg_match("/^[A-Za-z\s]+$/", $school_college)) {
        $errors[] = "School / College can contain only letters and spaces.";
    }

}
?>
