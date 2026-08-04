<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include_once __DIR__ . "/../db.php";

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $result = mysqli_query($conn, "SELECT * FROM courses ORDER BY id DESC");
    $courses = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $row['code'] = $row['course_code'];
        $row['name'] = $row['course_title'];
        $row['faculty'] = $row['faculty_name'];
        $row['room'] = $row['room_no'];
        $row['time'] = $row['time_slot'];
        $row['isOffered'] = $row['is_offered'];
        $courses[] = $row;
    }
    echo json_encode($courses);
    exit;
}

if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    $code = mysqli_real_escape_string($conn, $data['code'] ?? $_POST['code'] ?? '');
    $name = mysqli_real_escape_string($conn, $data['name'] ?? $_POST['name'] ?? '');
    $credits = floatval($data['credits'] ?? $_POST['credits'] ?? 3.0);
    $faculty = mysqli_real_escape_string($conn, $data['faculty'] ?? $_POST['faculty'] ?? '');
    $semester = mysqli_real_escape_string($conn, $data['semester'] ?? $_POST['semester'] ?? '');
    $room = mysqli_real_escape_string($conn, $data['room'] ?? $_POST['room'] ?? '');
    $day = mysqli_real_escape_string($conn, $data['day'] ?? $_POST['day'] ?? '');
    $time = mysqli_real_escape_string($conn, $data['time'] ?? $_POST['time'] ?? '');
    $isOffered = mysqli_real_escape_string($conn, $data['isOffered'] ?? $_POST['isOffered'] ?? 'Yes');

    if (empty($code) || empty($name)) {
        echo json_encode(["status" => "error", "message" => "Course code and title required"]);
        exit;
    }

    $sql = "INSERT INTO courses (course_code, course_title, credits, faculty_name, semester, room_no, day, time_slot, is_offered) 
            VALUES ('$code', '$name', '$credits', '$faculty', '$semester', '$room', '$day', '$time', '$isOffered')
            ON DUPLICATE KEY UPDATE course_title='$name', credits='$credits', faculty_name='$faculty', semester='$semester', room_no='$room', day='$day', time_slot='$time', is_offered='$isOffered'";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(["status" => "success", "message" => "Course saved and offered status updated"]);
    } else {
        echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
    }
    exit;
}

if ($method === 'DELETE') {
    $code = mysqli_real_escape_string($conn, $_GET['code'] ?? '');
    if (!empty($code)) {
        mysqli_query($conn, "DELETE FROM courses WHERE course_code='$code'");
        echo json_encode(["status" => "success", "message" => "Course deleted"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Missing course code"]);
    }
    exit;
}

?>
