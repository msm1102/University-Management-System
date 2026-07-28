<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
require_once 'db.php';

$user_email = $_SESSION['user_email'];

$summary_sql = "SELECT semester, semester_gpa, cgpa, completed_credits FROM students WHERE email = ?";
$stmt = $conn->prepare($summary_sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$summary_result = $stmt->get_result();
$summary = $summary_result->fetch_assoc();
$stmt->close();

$results_sql = "SELECT course_code, course_title, credit, grade, grade_point, status FROM results WHERE student_email = ?";
$stmt2 = $conn->prepare($results_sql);
$stmt2->bind_param("s", $user_email);
$stmt2->execute();
$results_data = $stmt2->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Results</title>
    <link rel="stylesheet" href="results.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>

<div class="container">

    <div class="header">
        <h1>Academic Results</h1>
        <a href="dashboard.php" class="back-btn">
            <i class="fa-solid fa-arrow-left"></i> Dashboard
        </a>
    </div>

    <div class="summary">
        <div class="card">
            <h3>Current Semester</h3>
            <p><?php echo htmlspecialchars($summary['semester'] ?? 'N/A'); ?></p>
        </div>
        <div class="card">
            <h3>Semester GPA</h3>
            <p><?php echo htmlspecialchars($summary['semester_gpa'] ?? '0.00'); ?></p>
        </div>
        <div class="card">
            <h3>Overall CGPA</h3>
            <p><?php echo htmlspecialchars($summary['cgpa'] ?? '0.00'); ?></p>
        </div>
        <div class="card">
            <h3>Completed Credits</h3>
            <p><?php echo htmlspecialchars($summary['completed_credits'] ?? '0'); ?></p>
        </div>
    </div>

    <table>
        <thead>
        <tr>
            <th>Course Code</th>
            <th>Course Title</th>
            <th>Credit</th>
            <th>Grade</th>
            <th>Grade Point</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($results_data && $results_data->num_rows > 0): ?>
            <?php while ($row = $results_data->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['course_code']); ?></td>
                    <td><?php echo htmlspecialchars($row['course_title']); ?></td>
                    <td><?php echo htmlspecialchars($row['credit']); ?></td>
                    <td><?php echo htmlspecialchars($row['grade']); ?></td>
                    <td><?php echo htmlspecialchars($row['grade_point']); ?></td>
                    <td><span class="<?php echo strtolower($row['status']) == 'passed' ? 'pass' : 'fail'; ?>"><?php echo htmlspecialchars($row['status']); ?></span></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" style="text-align: center;">No results found.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

</div>

</body>
</html>