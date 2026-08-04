<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include_once __DIR__ . "/../db.php";

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $type = $_GET['type'] ?? '';
    $sql = "SELECT id, routine_type, course_info AS course, faculty_info AS faculty, semester, day_or_date AS day, time_slot AS time, room_no AS room FROM routines";
    if ($type === 'Class' || $type === 'Exam') {
        $sql .= " WHERE routine_type='$type'";
    }
    $sql .= " ORDER BY id DESC";

    $result = mysqli_query($conn, $sql);
    $routines = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $routines[] = $row;
    }
    echo json_encode($routines);
    exit;
}

if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    $type = mysqli_real_escape_string($conn, $data['routine_type'] ?? $_POST['routine_type'] ?? 'Class');
    $course = mysqli_real_escape_string($conn, $data['course'] ?? $_POST['course'] ?? '');
    $faculty = mysqli_real_escape_string($conn, $data['faculty'] ?? $_POST['faculty'] ?? '');
    $semester = mysqli_real_escape_string($conn, $data['semester'] ?? $_POST['semester'] ?? '');
    $day = mysqli_real_escape_string($conn, $data['day'] ?? $_POST['day'] ?? $data['date'] ?? '');
    $time = mysqli_real_escape_string($conn, $data['time'] ?? $_POST['time'] ?? '');
    $room = mysqli_real_escape_string($conn, $data['room'] ?? $_POST['room'] ?? '');

    if (empty($course) || empty($time)) {
        echo json_encode(["status" => "error", "message" => "Course and Time required"]);
        exit;
    }

    $sql = "INSERT INTO routines (routine_type, course_info, faculty_info, semester, day_or_date, time_slot, room_no) 
            VALUES ('$type', '$course', '$faculty', '$semester', '$day', '$time', '$room')";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(["status" => "success", "message" => "$type routine entry saved"]);
    } else {
        echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
    }
    exit;
}

if ($method === 'DELETE') {
    $id = intval($_GET['id'] ?? 0);
    if ($id > 0) {
        mysqli_query($conn, "DELETE FROM routines WHERE id=$id");
        echo json_encode(["status" => "success", "message" => "Routine entry deleted"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid ID"]);
    }
    exit;
}

?>
