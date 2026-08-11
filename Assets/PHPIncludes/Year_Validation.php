<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // do NOT redeclare $errors here
    $year = trim($_POST['year_of_passing'] ?? '');

    if ($year === '') {
        $errors[] = "Year field is required.";
    }
    elseif (!preg_match("/^[0-9]{4}$/", $year)) {
        $errors[] = "Year must contain exactly 4 digits.";
    }
    elseif ($year > date("Y")) {
        $errors[] = "Year cannot be in the future.";
    }

}
?>
