<?php
$conn = mysqli_connect("localhost", "root", "", "cms_db");

$query = "SHOW TABLES";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_array($result)) {
    echo $row[0] . PHP_EOL;
}