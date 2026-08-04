<?php
include_once __DIR__ . "/auth_check.php";
include_once __DIR__ . "/db.php";
<<<<<<< HEAD
=======
<<<<<<< HEAD

// 3NF SELECT Query joining courses & faculty
$class_routine_sql = "SELECT 
                          r.id, 
                          r.routine_type, 
                          r.course_code, 
                          c.course_title, 
                          r.faculty_id, 
                          f.name AS faculty_name, 
                          r.semester, 
                          r.day_or_date AS day, 
                          r.time_slot AS time, 
                          r.room_no AS room 
                      FROM routines r
                      INNER JOIN courses c ON r.course_code = c.course_code
                      LEFT JOIN faculty f ON r.faculty_id = f.faculty_id
                      WHERE r.routine_type = 'Class'
                      ORDER BY r.id DESC";

$exam_routine_sql = "SELECT 
                         r.id, 
                         r.routine_type, 
                         r.course_code, 
                         c.course_title, 
                         r.faculty_id, 
                         f.name AS faculty_name, 
                         r.semester, 
                         r.day_or_date AS date, 
                         r.time_slot AS time, 
                         r.room_no AS room 
                     FROM routines r
                     INNER JOIN courses c ON r.course_code = c.course_code
                     LEFT JOIN faculty f ON r.faculty_id = f.faculty_id
                     WHERE r.routine_type = 'Exam'
                     ORDER BY r.id DESC";

$class_result = mysqli_query($conn, $class_routine_sql);
$exam_result = mysqli_query($conn, $exam_routine_sql);
=======
>>>>>>> d72d020c473b37966240849aa30820cc0e4111ce
>>>>>>> 5011bd327ff4618c666c22356e8eddd6277715a5
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class & Exam Routine - Student Portal</title>
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
    <a href="routine.php" class="active"><i class="fa-solid fa-clock me-2"></i>Class & Exam Routine</a>
    <a href="profile.php"><i class="fa-solid fa-user me-2"></i>My Profile</a>
    <a href="change_password.php"><i class="fa-solid fa-key me-2"></i>Change Password</a>
    <a href="logout.php" style="color: #f87171; margin-top: 40px;"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</a>
</div>

<div class="main">
    <div class="navbar">
<<<<<<< HEAD
        <h3><i class="fa-solid fa-clock me-2" style="color: #0284c7;"></i>Class Timetable & Examination Schedule</h3>
        <span style="font-weight: 600; color: #64748b; font-size: 13px;">Live Published Routines</span>
=======
<<<<<<< HEAD
        <h3><i class="fa-solid fa-clock me-2" style="color: #0284c7;"></i>Class Timetable & Examination Schedule (3NF)</h3>
        <span style="font-weight: 600; color: #64748b; font-size: 13px;">Live Published Routines (JOINs)</span>
=======
        <h3><i class="fa-solid fa-clock me-2" style="color: #0284c7;"></i>Class Timetable & Examination Schedule</h3>
        <span style="font-weight: 600; color: #64748b; font-size: 13px;">Live Published Routines</span>
>>>>>>> d72d020c473b37966240849aa30820cc0e4111ce
>>>>>>> 5011bd327ff4618c666c22356e8eddd6277715a5
    </div>

    <!-- Class Routine -->
    <div class="card">
        <h3 style="font-size: 18px; color: #1e3a8a; margin-bottom: 15px;"><i class="fa-solid fa-calendar-days me-2" style="color: #2563eb;"></i>Weekly Class Routine</h3>
        <table>
            <thead>
                <tr>
                    <th>Day</th>
                    <th>Time Slot</th>
<<<<<<< HEAD
                    <th>Course</th>
                    <th>Faculty</th>
=======
<<<<<<< HEAD
                    <th>Course Code & Title</th>
                    <th>Faculty Name</th>
=======
                    <th>Course</th>
                    <th>Faculty</th>
>>>>>>> d72d020c473b37966240849aa30820cc0e4111ce
>>>>>>> 5011bd327ff4618c666c22356e8eddd6277715a5
                    <th>Semester</th>
                    <th>Room</th>
                </tr>
            </thead>
<<<<<<< HEAD
            <tbody id="studentClassRoutineTbody"></tbody>
=======
<<<<<<< HEAD
            <tbody id="studentClassRoutineTbody">
                <?php if ($class_result && mysqli_num_rows($class_result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($class_result)): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 12px;"><span style="background: #1e293b; color: white; padding: 4px 8px; border-radius: 4px; font-weight: 600; font-size: 12px;"><?php echo htmlspecialchars($row['day']); ?></span></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars($row['time']); ?></td>
                            <td style="padding: 12px;"><strong><?php echo htmlspecialchars($row['course_code']) . " - " . htmlspecialchars($row['course_title']); ?></strong></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars($row['faculty_name'] ?? 'TBA'); ?></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars($row['semester']); ?></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars($row['room']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
=======
            <tbody id="studentClassRoutineTbody"></tbody>
>>>>>>> d72d020c473b37966240849aa30820cc0e4111ce
>>>>>>> 5011bd327ff4618c666c22356e8eddd6277715a5
        </table>
    </div>

    <!-- Exam Routine -->
    <div class="card">
        <h3 style="font-size: 18px; color: #991b1b; margin-bottom: 15px;"><i class="fa-solid fa-file-signature me-2" style="color: #dc2626;"></i>Semester Examination Schedule</h3>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time Slot</th>
<<<<<<< HEAD
                    <th>Course</th>
=======
<<<<<<< HEAD
                    <th>Course Code & Title</th>
=======
                    <th>Course</th>
>>>>>>> d72d020c473b37966240849aa30820cc0e4111ce
>>>>>>> 5011bd327ff4618c666c22356e8eddd6277715a5
                    <th>Semester</th>
                    <th>Exam Hall / Room</th>
                </tr>
            </thead>
<<<<<<< HEAD
            <tbody id="studentExamRoutineTbody"></tbody>
=======
<<<<<<< HEAD
            <tbody id="studentExamRoutineTbody">
                <?php if ($exam_result && mysqli_num_rows($exam_result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($exam_result)): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 12px;"><span style="background: #fee2e2; color: #b91c1c; padding: 4px 8px; border-radius: 4px; font-weight: 600; font-size: 13px;"><?php echo htmlspecialchars($row['date']); ?></span></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars($row['time']); ?></td>
                            <td style="padding: 12px;"><strong><?php echo htmlspecialchars($row['course_code']) . " - " . htmlspecialchars($row['course_title']); ?></strong></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars($row['semester']); ?></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars($row['room']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
=======
            <tbody id="studentExamRoutineTbody"></tbody>
>>>>>>> d72d020c473b37966240849aa30820cc0e4111ce
>>>>>>> 5011bd327ff4618c666c22356e8eddd6277715a5
        </table>
    </div>
</div>

<script src="dashboard.js"></script>
</body>
</html>
