<?php

include "db.php";

$faculty_id = $_POST['faculty_id'];
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$department = $_POST['department'];
$designation = $_POST['designation'];

$sql = "INSERT INTO faculty (faculty_id, name, email, phone, department, designation)
VALUES ('$faculty_id', '$name', '$email', '$phone', '$department', '$designation')";

if (mysqli_query($conn, $sql)) {
    echo "success";
} else {
    echo "Error: " . mysqli_error($conn);
}

?>