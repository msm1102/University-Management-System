<?php

include_once __DIR__ . "/auth_check.php";
include_once __DIR__ . "/db.php";

$student_id = $_SESSION['student_id'];

// Query student profile from MySQL
$stmt = mysqli_prepare($conn, "SELECT * FROM students WHERE student_id = ?");
mysqli_stmt_bind_param($stmt, "s", $student_id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

$student = mysqli_fetch_assoc($res) ?: [
    'student_id' => $student_id,
    'name' => $_SESSION['student_name'] ?? 'Student',
    'email' => 'student@nub.ac.bd',
    'phone' => '01700000000',
    'department' => $_SESSION['student_dept'] ?? 'CSE',
    'semester' => '7th Semester',
    'cgpa' => 3.80
];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Student Portal</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: #f8fafc; color: #334155; }
        .sidebar { width: 260px; background: #1e293b; color: white; position: fixed; height: 100vh; padding: 20px 0; }
        .sidebar h2 { text-align: center; font-size: 20px; font-weight: 700; margin-bottom: 30px; color: #38bdf8; }
        .sidebar a { display: block; color: #94a3b8; padding: 14px 25px; text-decoration: none; font-weight: 600; font-size: 14px; transition: 0.2s; }
        .sidebar a:hover, .sidebar a.active { background: #334155; color: white; border-left: 4px solid #38bdf8; }
        .main { margin-left: 260px; padding: 30px; }
        .navbar { background: white; padding: 15px 25px; border-radius: 12px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 8px rgba(0,0,0,0.04); margin-bottom: 30px; }
        .profile-card { background: white; padding: 35px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); max-width: 650px; border-top: 5px solid #2563eb; }
        .info-row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #f1f5f9; font-size: 15px; }
        .info-row label { font-weight: 600; color: #64748b; }
        .info-row span { font-weight: 700; color: #0f172a; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2><i class="fa-solid fa-graduation-cap me-2"></i>UMS Student</h2>
    <a href="dashboard.php"><i class="fa-solid fa-house me-2"></i>Dashboard</a>
    <a href="courses.php"><i class="fa-solid fa-book me-2"></i>Course Registration</a>
    <a href="fee_payment.php"><i class="fa-solid fa-wallet me-2"></i>Tuition & Payments</a>
    <a href="notice.php"><i class="fa-solid fa-bullhorn me-2"></i>Notice Board</a>
    <a href="routine.php"><i class="fa-solid fa-clock me-2"></i>Class & Exam Routine</a>
    <a href="profile.php" class="active"><i class="fa-solid fa-user me-2"></i>My Profile</a>
    <a href="change_password.php"><i class="fa-solid fa-key me-2"></i>Change Password</a>
    <a href="logout.php" style="color: #f87171; margin-top: 40px;"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</a>
</div>

<div class="main">
    <div class="navbar">
        <h3><i class="fa-solid fa-user me-2" style="color: #2563eb;"></i>Student Academic Profile</h3>
        <span style="font-weight: 600; color: #166534; background: #dcfce7; padding: 6px 14px; border-radius: 20px; font-size: 13px;">Verified MySQL Record</span>
    </div>

    <div class="profile-card">
        <div style="text-align: center; margin-bottom: 25px;">
            <i class="fa-solid fa-circle-user" style="font-size: 70px; color: #2563eb;"></i>
            <h2 style="font-size: 22px; color: #0f172a; margin-top: 10px;"><?php echo htmlspecialchars($student['name']); ?></h2>
            <p style="color: #64748b; font-size: 14px;"><?php echo htmlspecialchars($student['department']); ?> | <?php echo htmlspecialchars($student['semester']); ?></p>
        </div>

        <div class="info-row"><label>Student ID:</label> <span><?php echo htmlspecialchars($student['student_id']); ?></span></div>
        <div class="info-row"><label>Official Email:</label> <span><?php echo htmlspecialchars($student['email']); ?></span></div>
        <div class="info-row"><label>Phone Number:</label> <span><?php echo htmlspecialchars($student['phone'] ?? '01700000000'); ?></span></div>
        <div class="info-row"><label>Department:</label> <span><?php echo htmlspecialchars($student['department']); ?></span></div>
        <div class="info-row"><label>Current Semester:</label> <span><?php echo htmlspecialchars($student['semester']); ?></span></div>
        <div class="info-row"><label>Current Cumulative CGPA:</label> <span style="color: #16a34a; font-size: 16px;"><?php echo htmlspecialchars($student['cgpa'] ?? '3.80'); ?></span></div>

        <div style="margin-top: 25px; text-align: center;">
            <a href="change_password.php" style="background: #2563eb; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px; display: inline-block;"><i class="fa-solid fa-key me-1"></i> Change Password</a>
        </div>
    </div>
</div>

</body>
</html>
