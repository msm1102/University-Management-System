<?php
include_once __DIR__ . "/../auth_check.php";
include_once __DIR__ . "/../db.php";
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Student Management - UMS Admin</title>

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
          <li class="nav-item"><a href="students.php" class="nav-link active"><i class="bi bi-people"></i> Students</a></li>
          <li class="nav-item"><a href="faculty.php" class="nav-link"><i class="bi bi-person-workspace"></i> Faculty</a></li>
          <li class="nav-item"><a href="reports.php" class="nav-link"><i class="bi bi-file-earmark-bar-graph"></i> Reports</a></li>
          <li class="nav-item"><a href="settings.php" class="nav-link"><i class="bi bi-gear"></i> Settings</a></li>
          <li class="nav-item mt-4"><a href="../logout.php" class="nav-link text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
        </ul>
      </div>

      <div class="main-content">
        <nav class="navbar bg-white shadow-sm px-4">
          <h4>Student Registration & Management</h4>
          <div>
            <span class="me-3 fw-bold text-primary" id="clock"></span>
            <span class="fw-bold"><i class="bi bi-person-circle text-primary me-1"></i> Welcome <?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></span>
          </div>
        </nav>

        <div class="container mt-4">
          <div class="card shadow border-0">
            <div class="card-header bg-primary text-white">
              <i class="bi bi-person-plus me-2"></i>Add New Student Record (Stores to MySQL Database)
            </div>
            <div class="card-body">
              <form id="studentForm">
                <div class="row">
                  <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Student ID</label>
                    <input type="text" id="studentId" class="form-control" placeholder="e.g. 2024-001" required />
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Student Name</label>
                    <input type="text" id="studentName" class="form-control" placeholder="Full Name" required />
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Email</label>
                    <input type="email" id="studentEmail" class="form-control" placeholder="student@nub.ac.bd" required />
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Phone</label>
                    <input type="text" id="studentPhone" class="form-control" placeholder="01700000000" />
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Department</label>
                    <select id="studentDepartment" class="form-select">
                      <option value="CSE">Computer Science & Engineering (CSE)</option>
                      <option value="EEE">Electrical & Electronic Engineering (EEE)</option>
                      <option value="BBA">Business Administration (BBA)</option>
                      <option value="ENG">Department of English</option>
                    </select>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Semester</label>
                    <input type="text" id="studentSemester" class="form-control" placeholder="e.g. 7th Semester" />
                  </div>
                </div>
                <button type="button" class="btn btn-primary fw-bold" onclick="addStudent()">
                  <i class="bi bi-save me-1"></i> Register Student to MySQL
                </button>
              </form>
            </div>
          </div>

          <div class="card shadow border-0 mt-4">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
              <span><i class="bi bi-people me-2"></i>Registered Students List</span>
              <span class="badge bg-primary fs-6">Live DB Synced</span>
            </div>
            <div class="card-body">
              <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                  <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Semester</th>
                    <th>Email</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody id="studentTableBody"></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="../js/script.js"></script>
  </body>
</html>
