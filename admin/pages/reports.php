<?php
include_once __DIR__ . "/../auth_check.php";
include_once __DIR__ . "/../db.php";

$total_students = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM students"))['total'];
$total_faculty = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM faculty"))['total'];
$total_courses = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM courses"))['total'];
$total_fees = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM fee_transactions"))['total'];
$total_notices = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM notices"))['total'];

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reports & Analytics - UMS Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="../css/style.css" />
  </head>

  <body>
    <div class="d-flex">
      <div class="sidebar">
        <h3 class="text-center text-white mt-3 mb-4">UMS Admin</h3>
        <ul class="nav flex-column">
          <li class="nav-item"><a href="../dashboard.php" class="nav-link"><i class="bi bi-house-door"></i> Dashboard</a></li>
          <li class="nav-item"><a href="course-schedule.php" class="nav-link"><i class="bi bi-calendar-event"></i> Course Schedule</a></li>
          <li class="nav-item"><a href="fee-transactions.php" class="nav-link"><i class="bi bi-cash-stack"></i> Fee Transactions</a></li>
          <li class="nav-item"><a href="publish-grades.php" class="nav-link"><i class="bi bi-journal-check"></i> Publish Grades</a></li>
          <li class="nav-item"><a href="notices.php" class="nav-link"><i class="bi bi-megaphone"></i> Notice Board</a></li>
          <li class="nav-item"><a href="routine.php" class="nav-link"><i class="bi bi-clock-history"></i> Routine Management</a></li>
          <li class="nav-item"><a href="students.php" class="nav-link"><i class="bi bi-people"></i> Students</a></li>
          <li class="nav-item"><a href="faculty.php" class="nav-link"><i class="bi bi-person-workspace"></i> Faculty</a></li>
          <li class="nav-item"><a href="reports.php" class="nav-link active"><i class="bi bi-file-earmark-bar-graph"></i> Reports</a></li>
          <li class="nav-item"><a href="settings.php" class="nav-link"><i class="bi bi-gear"></i> Settings</a></li>
          <li class="nav-item mt-4"><a href="../logout.php" class="nav-link text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
        </ul>
      </div>

      <div class="main-content">
        <nav class="navbar bg-white shadow-sm px-4">
          <h4>System Reports & Statistics</h4>
          <div>
            <span class="me-3 fw-bold text-primary" id="clock"></span>
            <span class="fw-bold"><i class="bi bi-person-circle text-primary me-1"></i> Welcome <?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></span>
          </div>
        </nav>

        <div class="container mt-4">
          <div class="row">
            <div class="col-md-3 mb-3">
              <div class="card shadow border-0 bg-primary text-white text-center p-3">
                <h5>Total Students</h5>
                <h2><?php echo $total_students; ?></h2>
              </div>
            </div>
            <div class="col-md-3 mb-3">
              <div class="card shadow border-0 bg-success text-white text-center p-3">
                <h5>Total Faculty</h5>
                <h2><?php echo $total_faculty; ?></h2>
              </div>
            </div>
            <div class="col-md-3 mb-3">
              <div class="card shadow border-0 bg-info text-white text-center p-3">
                <h5>Total Courses</h5>
                <h2><?php echo $total_courses; ?></h2>
              </div>
            </div>
            <div class="col-md-3 mb-3">
              <div class="card shadow border-0 bg-warning text-dark text-center p-3">
                <h5>Fee Transactions</h5>
                <h2><?php echo $total_fees; ?></h2>
              </div>
            </div>
          </div>

          <div class="card shadow border-0 mt-3">
            <div class="card-header bg-dark text-white"><i class="bi bi-graph-up me-2"></i>Database System Summary</div>
            <div class="card-body">
              <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between"><span>Registered Active Students:</span> <strong><?php echo $total_students; ?></strong></li>
                <li class="list-group-item d-flex justify-content-between"><span>Faculty Members:</span> <strong><?php echo $total_faculty; ?></strong></li>
                <li class="list-group-item d-flex justify-content-between"><span>Offered Courses Catalog:</span> <strong><?php echo $total_courses; ?></strong></li>
                <li class="list-group-item d-flex justify-content-between"><span>Published Notices & Announcements:</span> <strong><?php echo $total_notices; ?></strong></li>
                <li class="list-group-item d-flex justify-content-between"><span>Database Status:</span> <span class="badge bg-success">Active & Healthy</span></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="../js/script.js"></script>
  </body>
</html>
