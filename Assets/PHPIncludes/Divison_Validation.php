<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // do NOT redeclare $errors here
    $division = trim($_POST['division'] ?? '');

    if ($division === '') {
        $errors[] = "Division field is required.";
    }
    elseif (!preg_match("/^[A-Za-z\s]+$/", $division)) {
        $errors[] = "Division can contain only letters and spaces.";
    }

}
?>
