<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

include_once __DIR__ . "/db.php";

$sql = "SELECT id, title, category, notice_date AS date, content FROM notices ORDER BY id DESC LIMIT 6";
$result = mysqli_query($conn, $sql);

$notices = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $notices[] = $row;
    }
}

echo json_encode($notices);

?>
