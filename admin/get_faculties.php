<?php

include "db.php";

$sql = "SELECT * FROM faculty";
$result = mysqli_query($conn, $sql);

$faculties = array();

while ($row = mysqli_fetch_assoc($result)) {
    $faculties[] = $row;
}

echo json_encode($faculties);

?>