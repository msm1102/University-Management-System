<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include_once __DIR__ . "/../db.php";

$method = $_SERVER['REQUEST_METHOD'];
$student_id = mysqli_real_escape_string($conn, $_GET['student_id'] ?? $_POST['student_id'] ?? '2024-001');

if ($method === 'GET') {
    $sql = "SELECT c.id, c.course_code AS code, c.course_title AS name, c.credits, c.faculty_name AS faculty, c.semester, c.room_no AS room, c.time_slot AS time 
            FROM course_registrations cr
            JOIN courses c ON cr.course_code = c.course_code
            WHERE cr.student_id = '$student_id'
            ORDER BY cr.id DESC";

    $result = mysqli_query($conn, $sql);
    $registered = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $registered[] = $row;
    }
    echo json_encode($registered);
    exit;
}

if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $student_id = mysqli_real_escape_string($conn, $data['student_id'] ?? '2024-001');
    $course_code = mysqli_real_escape_string($conn, $data['course_code'] ?? $data['code'] ?? '');

    if (empty($course_code)) {
        echo json_encode(["status" => "error", "message" => "Course code required"]);
        exit;
    }

    $sql = "INSERT INTO course_registrations (student_id, course_code) VALUES ('$student_id', '$course_code')
            ON DUPLICATE KEY UPDATE registration_date=CURRENT_TIMESTAMP";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(["status" => "success", "message" => "Successfully registered for course $course_code"]);
    } else {
        echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
    }
    exit;
}

if ($method === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);
    $student_id = mysqli_real_escape_string($conn, $_GET['student_id'] ?? $data['student_id'] ?? '2024-001');
    $course_code = mysqli_real_escape_string($conn, $_GET['course_code'] ?? $data['course_code'] ?? '');

    if (!empty($course_code)) {
        mysqli_query($conn, "DELETE FROM course_registrations WHERE student_id='$student_id' AND course_code='$course_code'");
        echo json_encode(["status" => "success", "message" => "Course dropped"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Missing course_code"]);
    }
    exit;
}

?>
