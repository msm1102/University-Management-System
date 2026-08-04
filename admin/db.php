<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "universitymenagement"
);

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

?>