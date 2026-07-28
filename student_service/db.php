<?php
$host = "localhost";
$user = "root";
$pass = "";
$db_name = "university_management_system";

$conn = mysqli_connect($host, $user, $pass, $db_name);

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}
?>