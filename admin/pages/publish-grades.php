<?php
include_once __DIR__ . "/../auth_check.php";
include_once __DIR__ . "/../db.php";
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Publish Grades - UMS Admin</title>

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
          <li class="nav-item"><a href="publish-grades.php" class="nav-link active"><i class="bi bi-journal-check"></i> Publish Grades</a></li>
          <li class="nav-item"><a href="notices.php" class="nav-link"><i class="bi bi-megaphone"></i> Notice Board</a></li>
          <li class="nav-item"><a href="routine.php" class="nav-link"><i class="bi bi-clock-history"></i> Routine Management</a></li>
          <li class="nav-item"><a href="students.php" class="nav-link"><i class="bi bi-people"></i> Students</a></li>
          <li class="nav-item"><a href="faculty.php" class="nav-link"><i class="bi bi-person-workspace"></i> Faculty</a></li>
          <li class="nav-item"><a href="reports.php" class="nav-link"><i class="bi bi-file-earmark-bar-graph"></i> Reports</a></li>
          <li class="nav-item"><a href="settings.php" class="nav-link"><i class="bi bi-gear"></i> Settings</a></li>
          <li class="nav-item mt-4"><a href="../logout.php" class="nav-link text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
        </ul>
      </div>

      <div class="main-content">
        <nav class="navbar bg-white shadow-sm px-4">
          <h4>Publish Student Grades & CGPA</h4>
          <div>
            <span class="me-3 fw-bold text-primary" id="clock"></span>
            <span class="fw-bold"><i class="bi bi-person-circle text-primary me-1"></i> Welcome <?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></span>
          </div>
        </nav>

        <div class="container mt-4">
          <div class="card shadow border-0">
            <div class="card-header bg-primary text-white">
              <i class="bi bi-journal-plus me-2"></i>Publish Academic Results
            </div>
            <div class="card-body">
              <form id="gradeForm">
                <div class="row">
                  <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Student ID</label>
                    <input type="text" id="gradeStudentId" class="form-control" placeholder="2024-001" required />
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Student Name</label>
                    <input type="text" id="gradeStudentName" class="form-control" placeholder="Student Name" required />
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Course Title / Code</label>
                    <input type="text" id="gradeCourse" class="form-control" placeholder="CSE-301 Database Systems" required />
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Semester</label>
                    <input type="text" id="gradeSemester" class="form-control" placeholder="7th Semester" />
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Grade Letter</label>
                    <select id="gradeGrade" class="form-select">
                      <option value="A+">A+ (4.00)</option>
                      <option value="A">A (3.75)</option>
                      <option value="A-">A- (3.50)</option>
                      <option value="B+">B+ (3.25)</option>
                      <option value="B">B (3.00)</option>
                      <option value="C">C (2.50)</option>
                      <option value="F">F (0.00)</option>
                    </select>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Current CGPA</label>
                    <input type="number" id="gradeCgpa" class="form-control" step="0.01" placeholder="3.80" />
                  </div>
                </div>
                <button type="button" class="btn btn-primary fw-bold" onclick="addGrade()">
                  <i class="bi bi-send me-1"></i> Publish Grade
                </button>
              </form>
            </div>
          </div>

          <div class="card shadow border-0 mt-4">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
              <span><i class="bi bi-journal-check me-2"></i>Published Academic Grades Record</span>
              <input type="text" id="gradeSearch" class="form-control form-control-sm w-25" placeholder="Search..." onkeyup="searchGrade()" />
            </div>
            <div class="card-body">
              <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                  <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Course</th>
                    <th>Semester</th>
                    <th>Grade</th>
                    <th>CGPA</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody id="gradeTableBody"></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="../js/script.js"></script>
  </body>
</html>
