<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$student_query = "SELECT s.*, sem.semester_name 
                 FROM students s 
                 LEFT JOIN semesters sem ON s.current_semester_id = sem.id 
                 WHERE s.user_id = '$user_id'";
$student_result = mysqli_query($conn, $student_query);
$student = mysqli_fetch_assoc($student_result);

$student_pk_id = $student['id'] ?? 0;

$course_count_query = "SELECT COUNT(*) AS total_courses 
                       FROM course_registrations 
                       WHERE student_id = '$student_pk_id' AND status = 'registered'";
$course_count_result = mysqli_query($conn, $course_count_query);
$course_count_data = mysqli_fetch_assoc($course_count_result);
$total_courses = $course_count_data['total_courses'] ?? 0;

$fee_query = "SELECT SUM(amount) AS pending_amount 
              FROM payments 
              WHERE student_id = '$student_pk_id' AND status = 'pending'";
$fee_result = mysqli_query($conn, $fee_query);
$fee_data = mysqli_fetch_assoc($fee_result);
$pending_fees = $fee_data['pending_amount'] ?? 0;

$notice_query = "SELECT * FROM notices ORDER BY id DESC LIMIT 1";
$notice_result = mysqli_query($conn, $notice_query);
$latest_notice = mysqli_fetch_assoc($notice_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>

    <link rel="stylesheet" href="dashboard.css">
    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

<div class="sidebar">
    <h2>NUB Portal</h2>

    <a href="dashboard.php" class="active"><i class="fa-solid fa-house"></i> Dashboard</a>
    <a href="profile.php"><i class="fa-solid fa-user"></i> My Profile</a>
    <a href="courses.php"><i class="fa-solid fa-book"></i> My Courses</a>
    <a href="register.php"><i class="fa-solid fa-user-plus"></i> Register</a>
    <a href="results.php"><i class="fa-solid fa-chart-column"></i> Results</a>
    <a href="routine.php"><i class="fa-solid fa-calendar-days"></i> Routine</a>
    <a href="notice.php"><i class="fa-solid fa-bullhorn"></i> Notices</a>
    <a href="fee_payment.php"><i class="fa-solid fa-money-bill-wave"></i> Fee Payment</a>
    <a href="settings.php"><i class="fa-solid fa-gear"></i> Settings</a>
    <a href="change_password.php"><i class="fa-solid fa-key"></i> Change Password</a>
    <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
</div>

<div class="main">

    <div class="header mb-6">
        <h1 class="text-3xl font-extrabold text-blue-900">
            Welcome, <?php echo htmlspecialchars($student['name'] ?? 'Student'); ?>
        </h1>
        <p class="text-gray-600">
            Northern University Bangladesh<br>
            Student Service Portal
        </p>
    </div>

    <div id="stats-cards-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Current Semester</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">
                    <?php echo htmlspecialchars($student['semester_name'] ?? 'N/A'); ?>
                </h3>
            </div>
            <div class="p-3 bg-blue-100 text-blue-600 rounded-lg">
                <i class="fa-solid fa-graduation-cap text-2xl"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Enrolled Courses</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">
                    <?php echo $total_courses; ?> Courses
                </h3>
            </div>
            <div class="p-3 bg-green-100 text-green-600 rounded-lg">
                <i class="fa-solid fa-book-open text-2xl"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">CGPA</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">
                    <?php echo htmlspecialchars($student['cgpa'] ?? '0.00'); ?>
                </h3>
            </div>
            <div class="p-3 bg-purple-100 text-purple-600 rounded-lg">
                <i class="fa-solid fa-chart-line text-2xl"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Pending Fees</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">
                    <?php echo number_format($pending_fees, 0); ?> BDT
                </h3>
            </div>
            <div class="p-3 bg-amber-100 text-amber-600 rounded-lg">
                <i class="fa-solid fa-wallet text-2xl"></i>
            </div>
        </div>

    </div>

    <div class="notice bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <h2 class="text-xl font-bold text-blue-900 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-bullhorn text-yellow-500"></i>
            Latest Notice
        </h2>
        <p class="text-gray-600">
            <?php 
                if ($latest_notice) {
                    echo htmlspecialchars($latest_notice['content']);
                } else {
                    echo "No recent notices available.";
                }
            ?>
        </p>
    </div>

</div>

</body>
</html>