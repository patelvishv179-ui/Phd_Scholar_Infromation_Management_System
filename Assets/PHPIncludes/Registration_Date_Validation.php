<?

$registration_date = filter_input(INPUT_POST, 'registration_date', FILTER_SANITIZE_SPECIAL_CHARS);
$registration_date = trim($registration_date);

/* Old value store */
$old['registration_date'] = $registration_date;

/* Validation */
if ($registration_date === '') {
    $errors['registration_date'] = "Registration date is required";
}
else {
    $d = DateTime::createFromFormat('Y-m-d', $registration_date);

    if (!$d || $d->format('Y-m-d') !== $registration_date) {
        $errors['registration_date'] = "Invalid registration date";
    }
}

?>
