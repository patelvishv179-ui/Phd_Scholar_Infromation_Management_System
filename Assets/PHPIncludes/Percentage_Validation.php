<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // do NOT redeclare $errors here
    $percentage = trim($_POST['percentage'] ?? '');

    if ($percentage === '') {
        $errors[] = "Percentage field is required.";
    }
    elseif (!preg_match("/^[0-9]+$/", $percentage)) {
        $errors[] = "Percentage must contain only numbers.";
    }
    elseif ($percentage < 0 || $percentage > 100) {
        $errors[] = "Percentage must be between 0 and 100.";
    }

}
?>
