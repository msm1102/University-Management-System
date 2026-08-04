<?php

include_once __DIR__ . "/auth_check.php";
include_once __DIR__ . "/db.php";

// Total Students
$student_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM students");
$total_students = ($student_result) ? mysqli_fetch_assoc($student_result)['total'] : 0;

// Total Faculty
$faculty_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM faculty");
$total_faculty = ($faculty_result) ? mysqli_fetch_assoc($faculty_result)['total'] : 0;

// Total Courses
$course_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM courses");
$total_courses = ($course_result) ? mysqli_fetch_assoc($course_result)['total'] : 0;

// Pending Fees
$fee_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM fee_transactions WHERE status='Pending'");
$pending_fees = ($fee_result) ? mysqli_fetch_assoc($fee_result)['total'] : 0;

// Recent Activities / Notices
$recent_activities = mysqli_query($conn, "SELECT * FROM notices ORDER BY id DESC LIMIT 5");

$admin_name = $_SESSION['admin_name'] ?? 'System Administrator';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - UMS</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar">
        <h3 class="text-center text-white mb-4 mt-3">UMS Admin</h3>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="dashboard.php" class="nav-link active">
                    <i class="bi bi-house-door"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="pages/course-schedule.php" class="nav-link">
                    <i class="bi bi-calendar-event"></i> Course Schedule
                </a>
            </li>
            <li class="nav-item">
                <a href="pages/fee-transactions.php" class="nav-link">
                    <i class="bi bi-cash-stack"></i> Fee Transactions
                </a>
            </li>
            <li class="nav-item">
                <a href="pages/publish-grades.php" class="nav-link">
                    <i class="bi bi-journal-check"></i> Publish Grades
                </a>
            </li>
            <li class="nav-item">
                <a href="pages/notices.php" class="nav-link">
                    <i class="bi bi-megaphone"></i> Notice Board
                </a>
            </li>
            <li class="nav-item">
                <a href="pages/routine.php" class="nav-link">
                    <i class="bi bi-clock-history"></i> Routine Management
                </a>
            </li>
            <li class="nav-item">
                <a href="pages/students.php" class="nav-link">
                    <i class="bi bi-people"></i> Students
                </a>
            </li>
            <li class="nav-item">
                <a href="pages/faculty.php" class="nav-link">
                    <i class="bi bi-person-workspace"></i> Faculty
                </a>
            </li>
            <li class="nav-item">
                <a href="pages/reports.php" class="nav-link">
                    <i class="bi bi-file-earmark-bar-graph"></i> Reports
                </a>
            </li>
            <li class="nav-item">
                <a href="pages/settings.php" class="nav-link">
                    <i class="bi bi-gear"></i> Settings
                </a>
            </li>
            <li class="nav-item mt-4">
                <a href="logout.php" class="nav-link text-danger">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">

        <!-- Navbar -->
        <nav class="navbar navbar-light bg-white shadow-sm px-4">
            <h4>Admin Dashboard</h4>
            <div>
                <span class="me-3 fw-bold text-primary" id="clock"></span>
                <span class="fw-bold text-dark"><i class="bi bi-person-circle text-primary me-1"></i> Welcome <?php echo htmlspecialchars($admin_name); ?></span>
            </div>
        </nav>

        <!-- Dashboard Cards -->
        <div class="container-fluid mt-4">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="card shadow border-0 text-white bg-primary">
                        <div class="card-body text-center">
                            <h5>Total Students</h5>
                            <h2><?php echo $total_students; ?></h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="card shadow border-0 text-white bg-success">
                        <div class="card-body text-center">
                            <h5>Total Faculty</h5>
                            <h2><?php echo $total_faculty; ?></h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="card shadow border-0 text-white bg-info">
                        <div class="card-body text-center">
                            <h5>Total Courses</h5>
                            <h2><?php echo $total_courses; ?></h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="card shadow border-0 text-white bg-warning">
                        <div class="card-body text-center">
                            <h5>Pending Fees</h5>
                            <h2><?php echo $pending_fees; ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent System Notices / Activities -->
            <div class="card mt-4 shadow border-0">
                <div class="card-header bg-dark text-white">
                    <i class="bi bi-megaphone me-2"></i>Published System Announcements & Circulars
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Category</th>
                                <th>Title</th>
                                <th>Content Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($recent_activities) > 0): ?>
                                <?php while ($act = mysqli_fetch_assoc($recent_activities)): ?>
                                    <tr>
                                        <td><small class="fw-bold"><?php echo htmlspecialchars($act['notice_date']); ?></small></td>
                                        <td><span class="badge bg-primary"><?php echo htmlspecialchars($act['category']); ?></span></td>
                                        <td><strong><?php echo htmlspecialchars($act['title']); ?></strong></td>
                                        <td><small><?php echo htmlspecialchars(substr($act['content'], 0, 120)); ?>...</small></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center text-muted">No notices published yet.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>
</div>

<script src="js/script.js"></script>
</body>
</html>