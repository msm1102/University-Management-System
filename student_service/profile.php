<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once 'db.php';

$user_email = $_SESSION['user_email'];

$sql = "SELECT full_name, student_id, department, semester, phone, cgpa, profile_image FROM students WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();
} else {
    die("Data not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>

<div class="container">
    <div class="profile-card">
        
        <?php $image_path = !empty($student['profile_image']) ? $student['profile_image'] : '../image/university.jpg'; ?>
        <img src="<?php echo htmlspecialchars($image_path); ?>" alt="Student">

        <h2><?php echo htmlspecialchars($student['full_name']); ?></h2>
        <p>Northern University Bangladesh</p>
        
        <table>
            <tr>
                <td><strong>Student ID</strong></td>
                <td><?php echo htmlspecialchars($student['student_id']); ?></td>
            </tr>
            <tr>
                <td><strong>Department</strong></td>
                <td><?php echo htmlspecialchars($student['department']); ?></td>
            </tr>
            <tr>
                <td><strong>Semester</strong></td>
                <td><?php echo htmlspecialchars($student['semester']); ?></td>
            </tr>
            <tr>
                <td><strong>Email</strong></td>
                <td><?php echo htmlspecialchars($user_email); ?></td>
            </tr>
            <tr>
                <td><strong>Phone</strong></td>
                <td><?php echo htmlspecialchars($student['phone']); ?></td>
            </tr>
            <tr>
                <td><strong>CGPA</strong></td>
                <td><?php echo htmlspecialchars($student['cgpa']); ?></td>
            </tr>
        </table>

        <a href="dashboard.php" class="btn">
            <i class="fa-solid fa-arrow-left"></i> Back Dashboard
        </a>

    </div>
</div>

</body>
</html>