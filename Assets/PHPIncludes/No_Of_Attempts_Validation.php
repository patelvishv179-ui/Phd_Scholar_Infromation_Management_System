<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // do NOT redeclare $errors here
    $attempts = trim($_POST['no_of_attempts'] ?? '');

    if ($attempts === '') {
        $errors[] = "No. of Attempts is required.";
    }
    elseif (!preg_match("/^[0-9]+$/", $attempts)) {
        $errors[] = "No. of Attempts must contain only numbers.";
    }
    elseif ($attempts < 0) {
        $errors[] = "No. of Attempts cannot be negative.";
    }

}
?>
