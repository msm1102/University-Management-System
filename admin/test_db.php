<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "universitymenagement"
);

if ($conn) {
    echo "Database Connected Successfully!";
} else {
    echo "Database Connection Failed!";
}

?>