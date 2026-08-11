<?php

require_once __DIR__ . "/env.php";

$con = mysqli_connect(
    $servername,
    $username,
    $password,
    $dbname
);

if (!$con) {
    die("Database connection failed.");
}
