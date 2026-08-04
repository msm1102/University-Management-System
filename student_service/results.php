<?php
include_once __DIR__ . "/auth_check.php";
include_once __DIR__ . "/db.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Results - Student Portal</title>
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
        .card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { text-align: left; padding: 12px; border-bottom: 1px solid #e2e8f0; font-size: 14px; }
        th { background: #f1f5f9; color: #334155; font-weight: 700; }
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
    <a href="profile.php"><i class="fa-solid fa-user me-2"></i>My Profile</a>
    <a href="change_password.php"><i class="fa-solid fa-key me-2"></i>Change Password</a>
    <a href="logout.php" style="color: #f87171; margin-top: 40px;"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</a>
</div>

<div class="main">
    <div class="navbar">
        <h3><i class="fa-solid fa-square-poll-vertical me-2" style="color: #2563eb;"></i>Academic Semester Results & Transcripts</h3>
    </div>

    <div class="card">
        <h3 style="font-size: 18px; color: #1e3a8a; margin-bottom: 15px;"><i class="fa-solid fa-award me-2"></i>Published Semester Performance</h3>
        <table>
            <thead>
                <tr>
                    <th>Semester</th>
                    <th>Course Code & Title</th>
                    <th>Grade Letter</th>
                    <th>Grade Point</th>
                    <th>Publication Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>6th Semester</td>
                    <td><strong>CSE-301 Database Management Systems</strong></td>
                    <td><span style="background: #dcfce7; color: #166534; padding: 4px 8px; border-radius: 4px; font-weight: 700;">A+</span></td>
                    <td>4.00</td>
                    <td><span style="background: #e0f2fe; color: #0369a1; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">Published</span></td>
                </tr>
                <tr>
                    <td>6th Semester</td>
                    <td><strong>CSE-305 Software Engineering & Patterns</strong></td>
                    <td><span style="background: #dcfce7; color: #166534; padding: 4px 8px; border-radius: 4px; font-weight: 700;">A</span></td>
                    <td>3.75</td>
                    <td><span style="background: #e0f2fe; color: #0369a1; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">Published</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
