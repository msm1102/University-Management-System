<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
require_once 'db.php';

$user_email = $_SESSION['user_email'];
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = $_POST['phone'];
    $present_address = $_POST['present_address'];
    $permanent_address = $_POST['permanent_address'];
    
    $update_sql = "UPDATE students SET phone = ?, present_address = ?, permanent_address = ? WHERE email = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssss", $phone, $present_address, $permanent_address, $user_email);
    
    if ($update_stmt->execute()) {
        $message = "Settings updated successfully.";
        $message_type = "green";
    } else {
        $message = "Failed to update settings.";
        $message_type = "red";
    }
    $update_stmt->close();
}

$sql = "SELECT student_id, department, semester, batch, cgpa, phone, email, present_address, permanent_address FROM students WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings</title>
    <link rel="stylesheet" href="settings.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>

<div class="container">

<div class="header">
    <h1>Account Settings</h1>
    <a href="dashboard.php" class="back-btn">
        <i class="fa-solid fa-arrow-left"></i> Dashboard
    </a>
</div>

<?php if ($message): ?>
    <p style="text-align: center; margin-bottom: 15px; font-weight: 500; color: <?php echo $message_type; ?>;">
        <?php echo htmlspecialchars($message); ?>
    </p>
<?php endif; ?>

<form method="POST" action="" enctype="multipart/form-data">
    <div class="card">
        <h2>Contact Information</h2>

        <label for="phone">Mobile Number</label>
        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($student['phone'] ?? ''); ?>" required>

        <label for="email">Email Address</label>
        <input type="email" id="email" value="<?php echo htmlspecialchars($student['email'] ?? ''); ?>" readonly style="background-color: #f3f4f6; cursor: not-allowed;">

        <label for="present_address">Present Address</label>
        <textarea id="present_address" name="present_address" rows="3"><?php echo htmlspecialchars($student['present_address'] ?? ''); ?></textarea>

        <label for="permanent_address">Permanent Address</label>
        <textarea id="permanent_address" name="permanent_address" rows="3"><?php echo htmlspecialchars($student['permanent_address'] ?? ''); ?></textarea>

        <label for="profile_picture">Profile Picture</label>
        <input type="file" id="profile_picture" name="profile_picture">

        <button type="submit">Save Changes</button>
    </div>
</form>

<div class="card readonly">
    <h2>Academic Information (Read Only)</h2>
    <p><strong>Student ID:</strong> <?php echo htmlspecialchars($student['student_id'] ?? 'N/A'); ?></p>
    <p><strong>Department:</strong> <?php echo htmlspecialchars($student['department'] ?? 'N/A'); ?></p>
    <p><strong>Semester:</strong> <?php echo htmlspecialchars($student['semester'] ?? 'N/A'); ?></p>
    <p><strong>Batch:</strong> <?php echo htmlspecialchars($student['batch'] ?? 'N/A'); ?></p>
    <p><strong>CGPA:</strong> <?php echo htmlspecialchars($student['cgpa'] ?? 'N/A'); ?></p>
</div>

</div>

</body>
</html>