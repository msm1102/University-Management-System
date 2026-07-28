<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
require_once 'db.php';

$user_email = $_SESSION['user_email'];

$student_sql = "SELECT department, semester FROM students WHERE email = ?";
$stmt = $conn->prepare($student_sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$student_result = $stmt->get_result();
$student_info = $student_result->fetch_assoc();
$stmt->close();

$department = $student_info['department'] ?? '';
$semester = $student_info['semester'] ?? '';

$routine_sql = "SELECT day, class_time, course_code, course_title, teacher, room FROM class_routine WHERE department = ? AND semester = ?";
$stmt2 = $conn->prepare($routine_sql);
$stmt2->bind_param("ss", $department, $semester);
$stmt2->execute();
$routine_result = $stmt2->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Routine</title>
    <link rel="stylesheet" href="routine.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>

<div class="container">

    <div class="header">
        <h1>Weekly Class Routine</h1>
        <a href="dashboard.php" class="back-btn">
            <i class="fa-solid fa-arrow-left"></i> Dashboard
        </a>
    </div>

    <table>
        <thead>
        <tr>
            <th>Day</th>
            <th>Time</th>
            <th>Course Code</th>
            <th>Course</th>
            <th>Teacher</th>
            <th>Room</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($routine_result && $routine_result->num_rows > 0): ?>
            <?php while ($row = $routine_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['day']); ?></td>
                    <td><?php echo htmlspecialchars($row['class_time']); ?></td>
                    <td><?php echo htmlspecialchars($row['course_code']); ?></td>
                    <td><?php echo htmlspecialchars($row['course_title']); ?></td>
                    <td><?php echo htmlspecialchars($row['teacher']); ?></td>
                    <td><?php echo htmlspecialchars($row['room']); ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" style="text-align: center;">No class routine available for your current semester.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

</div>

</body>
</html>