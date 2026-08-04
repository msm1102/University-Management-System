<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include_once __DIR__ . "/../db.php";

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $result = mysqli_query($conn, "SELECT id, student_id, name, email, phone, department, semester, cgpa, created_at FROM students ORDER BY id DESC");
    $students = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $students[] = $row;
    }
    echo json_encode($students);
    exit;
}

if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    // Support form-urlencoded or JSON
    $student_id = mysqli_real_escape_string($conn, $data['id'] ?? $_POST['student_id'] ?? $_POST['id'] ?? '');
    $name = mysqli_real_escape_string($conn, $data['name'] ?? $_POST['name'] ?? '');
    $email = mysqli_real_escape_string($conn, $data['email'] ?? $_POST['email'] ?? '');
    $phone = mysqli_real_escape_string($conn, $data['phone'] ?? $_POST['phone'] ?? '');
    $department = mysqli_real_escape_string($conn, $data['department'] ?? $_POST['department'] ?? '');
    $semester = mysqli_real_escape_string($conn, $data['semester'] ?? $_POST['semester'] ?? '');
    $password = mysqli_real_escape_string($conn, $data['password'] ?? $_POST['password'] ?? '1234');
    $passHash = password_hash($password, PASSWORD_DEFAULT);

    if (empty($student_id) || empty($name) || empty($email)) {
        echo json_encode(["status" => "error", "message" => "Please provide Student ID, Name, and Email"]);
        exit;
    }

    $sql = "INSERT INTO students (student_id, name, email, password, phone, department, semester) 
            VALUES ('$student_id', '$name', '$email', '$passHash', '$phone', '$department', '$semester')
            ON DUPLICATE KEY UPDATE name='$name', email='$email', phone='$phone', department='$department', semester='$semester'";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(["status" => "success", "message" => "Student saved to database successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
    }
    exit;
}

if ($method === 'DELETE') {
    parse_str(file_get_contents("php://input"), $delParams);
    $student_id = mysqli_real_escape_string($conn, $_GET['student_id'] ?? $delParams['student_id'] ?? '');

    if (!empty($student_id)) {
        mysqli_query($conn, "DELETE FROM students WHERE student_id='$student_id'");
        echo json_encode(["status" => "success", "message" => "Student deleted"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Missing student_id"]);
    }
    exit;
}

?>
