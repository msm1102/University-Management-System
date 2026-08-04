<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include_once __DIR__ . "/../db.php";

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $result = mysqli_query($conn, "SELECT id, title, category, notice_date AS date, content, created_at FROM notices ORDER BY id DESC");
    $notices = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $notices[] = $row;
    }
    echo json_encode($notices);
    exit;
}

if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    $title = mysqli_real_escape_string($conn, $data['title'] ?? $_POST['title'] ?? '');
    $category = mysqli_real_escape_string($conn, $data['category'] ?? $_POST['category'] ?? 'General');
    $date = mysqli_real_escape_string($conn, $data['date'] ?? $_POST['date'] ?? date('Y-m-d'));
    $content = mysqli_real_escape_string($conn, $data['content'] ?? $_POST['content'] ?? '');

    if (empty($title) || empty($content)) {
        echo json_encode(["status" => "error", "message" => "Title and content required"]);
        exit;
    }

    $sql = "INSERT INTO notices (title, category, notice_date, content) VALUES ('$title', '$category', '$date', '$content')";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(["status" => "success", "message" => "Notice published successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
    }
    exit;
}

if ($method === 'DELETE') {
    $id = intval($_GET['id'] ?? 0);
    if ($id > 0) {
        mysqli_query($conn, "DELETE FROM notices WHERE id=$id");
        echo json_encode(["status" => "success", "message" => "Notice deleted"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid notice ID"]);
    }
    exit;
}

?>
