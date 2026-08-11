<?php
$name = $_POST['scholar_name'] ?? '';
$name = trim($name);
$old['scholar_name'] = $name;
if ($name === '') {
    $errors['scholar_name'] = "Scholar name is required";
}
elseif (!preg_match("/^[A-Za-z ]+$/", $name)) {
    $errors['scholar_name'] = "Only letters and spaces allowed (no numbers, no special characters)";
}
?>
