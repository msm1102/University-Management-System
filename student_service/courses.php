<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once 'db.php';

$user_email = $_SESSION['user_email'];

$sql = "SELECT c.course_code, c.course_title, c.credit, c.instructor, c.room, c.schedule, cr.status 
        FROM course_registrations cr 
        JOIN courses c ON cr.course_id = c.id 
        JOIN students s ON cr.student_id = s.id 
        WHERE s.email = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Courses</title>
    <link rel="stylesheet" href="courses.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>

<div class="container">
    <div class="header">
        <h1>My Registered Courses</h1>
        <a href="dashboard.php" class="back-btn">
            <i class="fa-solid fa-arrow-left"></i> Dashboard
        </a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Course Code</th>
                <th>Course Title</th>
                <th>Credit</th>
                <th>Instructor</th>
                <th>Room</th>
                <th>Schedule</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['course_code']); ?></td>
                        <td><?php echo htmlspecialchars($row['course_title']); ?></td>
                        <td><?php echo htmlspecialchars($row['credit']); ?></td>
                        <td><?php echo htmlspecialchars($row['instructor']); ?></td>
                        <td><?php echo htmlspecialchars($row['room']); ?></td>
                        <td><?php echo htmlspecialchars($row['schedule']); ?></td>
                        <td><span class="active"><?php echo htmlspecialchars($row['status']); ?></span></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align: center;">No courses registered yet.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>