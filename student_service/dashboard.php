<?php

include_once __DIR__ . "/auth_check.php";
include_once __DIR__ . "/db.php";

$student_id = $_SESSION['student_id'];
$student_name = $_SESSION['student_name'] ?? 'Student';
$student_dept = $_SESSION['student_dept'] ?? 'CSE';

// Fetch registered courses for this student from MySQL
$reg_query = "SELECT c.credits FROM course_registrations cr JOIN courses c ON cr.course_code = c.course_code WHERE cr.student_id = '$student_id'";
$reg_res = mysqli_query($conn, $reg_query);

$total_courses = 0;
$total_credits = 0.0;

if ($reg_res) {
    while ($row = mysqli_fetch_assoc($reg_res)) {
        $total_courses++;
        $total_credits += floatval($row['credits']);
    }
}

$total_tuition_fee = $total_credits * 3000; // 1 Credit = 3000 BDT

// Fetch latest notice
$notice_res = mysqli_query($conn, "SELECT * FROM notices ORDER BY id DESC LIMIT 1");
$latest_notice = ($notice_res && mysqli_num_rows($notice_res) > 0) ? mysqli_fetch_assoc($notice_res) : null;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - UMS Portal</title>
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
        .cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); border-top: 4px solid #2563eb; }
        .card h4 { font-size: 14px; color: #64748b; margin-bottom: 10px; }
        .card h2 { font-size: 26px; color: #1e293b; font-weight: 700; }
        .notice-box { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); border-left: 5px solid #d97706; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2><i class="fa-solid fa-graduation-cap me-2"></i>UMS Student</h2>
    <a href="dashboard.php" class="active"><i class="fa-solid fa-house me-2"></i>Dashboard</a>
    <a href="courses.php"><i class="fa-solid fa-book me-2"></i>Course Registration</a>
    <a href="fee_payment.php"><i class="fa-solid fa-wallet me-2"></i>Tuition & Payments</a>
    <a href="notice.php"><i class="fa-solid fa-bullhorn me-2"></i>Notice Board</a>
    <a href="routine.php"><i class="fa-solid fa-clock me-2"></i>Class & Exam Routine</a>
    <a href="profile.php"><i class="fa-solid fa-user me-2"></i>My Profile</a>
    <a href="change_password.php"><i class="fa-solid fa-key me-2"></i>Change Password</a>
    <a href="logout.php" style="color: #f87171; margin-top: 40px;"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</a>
</div>

<div class="main">
    <div class="navbar">
        <h3 style="color: #1e293b;">Welcome Back, <?php echo htmlspecialchars($student_name); ?> 👋</h3>
        <div>
            <span style="font-weight: 600; color: #2563eb; margin-right: 15px;"><i class="fa-solid fa-id-badge me-1"></i>ID: <?php echo htmlspecialchars($student_id); ?></span>
            <span style="background: #e0f2fe; color: #0369a1; padding: 4px 12px; border-radius: 20px; font-size: 13px; font-weight: 600;"><?php echo htmlspecialchars($student_dept); ?></span>
        </div>
    </div>

    <div class="cards">
        <div class="card" style="border-color: #2563eb;">
            <h4>Registered Courses</h4>
            <h2><?php echo $total_courses; ?> Courses</h2>
        </div>
        <div class="card" style="border-color: #16a34a;">
            <h4>Enrolled Credits</h4>
            <h2><?php echo number_format($total_credits, 1); ?> Credits</h2>
        </div>
        <div class="card" style="border-color: #0284c7;">
            <h4>Calculated Tuition (3,000 BDT/Cr)</h4>
            <h2><?php echo number_format($total_tuition_fee); ?> BDT</h2>
        </div>
        <div class="card" style="border-color: #ca8a04;">
            <h4>Academic Status</h4>
            <h2>Active</h2>
        </div>
    </div>

    <div class="notice-box">
        <h3 style="font-size: 18px; color: #1e3a8a; margin-bottom: 10px;"><i class="fa-solid fa-bullhorn me-2" style="color: #d97706;"></i>Latest Admin Notice</h3>
        <?php if ($latest_notice): ?>
            <h4 style="font-size: 16px; color: #0f172a; margin-bottom: 5px;"><?php echo htmlspecialchars($latest_notice['title']); ?></h4>
            <small style="color: #64748b; font-size: 12px; display: block; margin-bottom: 10px;"><i class="fa-regular fa-calendar me-1"></i> <?php echo htmlspecialchars($latest_notice['notice_date']); ?> | Category: <?php echo htmlspecialchars($latest_notice['category']); ?></small>
            <p style="color: #475569; font-size: 14px; line-height: 1.6;"><?php echo htmlspecialchars($latest_notice['content']); ?></p>
        <?php else: ?>
            <p style="color: #64748b;">No recent notices published.</p>
        <?php endif; ?>
    </div>
</div>

<script src="dashboard.js"></script>
</body>
</html>
