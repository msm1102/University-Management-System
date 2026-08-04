<?php
include_once __DIR__ . "/auth_check.php";
include_once __DIR__ . "/db.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official Notice Board - Student Portal</title>
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
        .notice-container { display: flex; flex-direction: column; gap: 20px; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2><i class="fa-solid fa-graduation-cap me-2"></i>UMS Student</h2>
    <a href="dashboard.php"><i class="fa-solid fa-house me-2"></i>Dashboard</a>
    <a href="courses.php"><i class="fa-solid fa-book me-2"></i>Course Registration</a>
    <a href="fee_payment.php"><i class="fa-solid fa-wallet me-2"></i>Tuition & Payments</a>
    <a href="notice.php" class="active"><i class="fa-solid fa-bullhorn me-2"></i>Notice Board</a>
    <a href="routine.php"><i class="fa-solid fa-clock me-2"></i>Class & Exam Routine</a>
    <a href="profile.php"><i class="fa-solid fa-user me-2"></i>My Profile</a>
    <a href="change_password.php"><i class="fa-solid fa-key me-2"></i>Change Password</a>
    <a href="logout.php" style="color: #f87171; margin-top: 40px;"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</a>
</div>

<div class="main">
    <div class="navbar">
        <h3><i class="fa-solid fa-bullhorn me-2" style="color: #d97706;"></i>Official University Notice Board</h3>
        <span style="font-weight: 600; color: #64748b; font-size: 13px;">Live Admin Published Circulars</span>
    </div>

    <div class="notice-container" id="studentNoticeBoardContainer"></div>
</div>

<script src="dashboard.js"></script>
</body>
</html>
