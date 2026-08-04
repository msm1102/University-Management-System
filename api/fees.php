<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include_once __DIR__ . "/../db.php";

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $student_id = $_GET['student_id'] ?? '';
    $sql = "SELECT id, student_id, student_name AS name, department, semester, amount, details, status, transaction_date AS date FROM fee_transactions";
    if (!empty($student_id)) {
        $sql .= " WHERE student_id='$student_id'";
    }
    $sql .= " ORDER BY id DESC";

    $result = mysqli_query($conn, $sql);
    $fees = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $fees[] = $row;
    }
    echo json_encode($fees);
    exit;
}

if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    $student_id = mysqli_real_escape_string($conn, $data['id'] ?? $data['student_id'] ?? $_POST['student_id'] ?? '2024-001');
    $student_name = mysqli_real_escape_string($conn, $data['name'] ?? $data['student_name'] ?? $_POST['name'] ?? 'Sakib Khan Rony');
    $department = mysqli_real_escape_string($conn, $data['department'] ?? $_POST['department'] ?? 'CSE');
    $semester = mysqli_real_escape_string($conn, $data['semester'] ?? $_POST['semester'] ?? '7th Semester');
    $amount = intval($data['amount'] ?? $_POST['amount'] ?? 0);
    $details = mysqli_real_escape_string($conn, $data['details'] ?? $_POST['details'] ?? 'Tuition Fee');
    $status = mysqli_real_escape_string($conn, $data['status'] ?? $_POST['status'] ?? 'Pending');

    if ($amount <= 0) {
        echo json_encode(["status" => "error", "message" => "Valid amount required"]);
        exit;
    }

    $sql = "INSERT INTO fee_transactions (student_id, student_name, department, semester, amount, details, status) 
            VALUES ('$student_id', '$student_name', '$department', '$semester', $amount, '$details', '$status')";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(["status" => "success", "message" => "Fee transaction recorded"]);
    } else {
        echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
    }
    exit;
}

if ($method === 'PUT') {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = intval($data['id'] ?? 0);
    $status = mysqli_real_escape_string($conn, $data['status'] ?? '');

    if ($id > 0 && in_array($status, ['Approved', 'Rejected', 'Pending'])) {
        mysqli_query($conn, "UPDATE fee_transactions SET status='$status' WHERE id=$id");
        echo json_encode(["status" => "success", "message" => "Fee status updated to $status"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid ID or status"]);
    }
    exit;
}

?>
