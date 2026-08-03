<?php
include_once __DIR__ . "/../auth_check.php";
include_once __DIR__ . "/../db.php";
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Course Schedule & Registration Offer - UMS Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="../css/style.css" />
  </head>

  <body>
    <div class="d-flex">
      <!-- Sidebar -->
      <div class="sidebar">
        <h3 class="text-center text-white mt-3 mb-4">UMS Admin</h3>
        <ul class="nav flex-column">
          <li class="nav-item"><a href="../dashboard.php" class="nav-link"><i class="bi bi-house-door"></i> Dashboard</a></li>
          <li class="nav-item"><a href="course-schedule.php" class="nav-link active"><i class="bi bi-calendar-event"></i> Course Schedule</a></li>
          <li class="nav-item"><a href="fee-transactions.php" class="nav-link"><i class="bi bi-cash-stack"></i> Fee Transactions</a></li>
          <li class="nav-item"><a href="publish-grades.php" class="nav-link"><i class="bi bi-journal-check"></i> Publish Grades</a></li>
          <li class="nav-item"><a href="notices.php" class="nav-link"><i class="bi bi-megaphone"></i> Notice Board</a></li>
          <li class="nav-item"><a href="routine.php" class="nav-link"><i class="bi bi-clock-history"></i> Routine Management</a></li>
          <li class="nav-item"><a href="students.php" class="nav-link"><i class="bi bi-people"></i> Students</a></li>
          <li class="nav-item"><a href="faculty.php" class="nav-link"><i class="bi bi-person-workspace"></i> Faculty</a></li>
          <li class="nav-item"><a href="reports.php" class="nav-link"><i class="bi bi-file-earmark-bar-graph"></i> Reports</a></li>
          <li class="nav-item"><a href="settings.php" class="nav-link"><i class="bi bi-gear"></i> Settings</a></li>
          <li class="nav-item mt-4"><a href="../logout.php" class="nav-link text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
        </ul>
      </div>

      <!-- Main Content -->
      <div class="main-content">
        <nav class="navbar bg-white shadow-sm px-4">
          <h4>Course Schedule & Registration Offer</h4>
          <div>
            <span class="me-3 fw-bold text-primary" id="clock"></span>
            <span class="fw-bold"><i class="bi bi-person-circle text-primary me-1"></i> Welcome <?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></span>
          </div>
        </nav>

        <div class="container mt-4">
          <!-- Add Course Card -->
          <div class="card shadow border-0">
            <div class="card-header bg-primary text-white">
              <i class="bi bi-plus-square me-2"></i>Add Course & Offer for Student Registration
            </div>

            <div class="card-body">
              <form id="courseForm">
                <div class="row">
                  <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Course Code</label>
                    <input type="text" id="courseCode" class="form-control" placeholder="e.g. CSE-301" required />
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Course Title</label>
                    <input type="text" id="courseName" class="form-control" placeholder="e.g. Database Systems" required />
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Credits (Fee: 3000 BDT/Credit)</label>
                    <input type="number" id="courseCredits" class="form-control" value="3.0" step="0.5" min="1" required />
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Faculty Name</label>
                    <input type="text" id="courseFaculty" class="form-control" placeholder="e.g. Prof. Ahsan" required />
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Semester</label>
                    <select id="courseSemester" class="form-select">
                      <option value="Spring">Spring</option>
                      <option value="Summer">Summer</option>
                      <option value="Fall">Fall</option>
                    </select>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Offer for Student Registration?</label>
                    <select id="courseIsOffered" class="form-select">
                      <option value="Yes">Yes (Open for Registration)</option>
                      <option value="No">No (Closed)</option>
                    </select>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Room No.</label>
                    <input type="text" id="courseRoom" class="form-control" placeholder="e.g. Lab 201" />
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Day</label>
                    <select id="courseDay" class="form-select">
                      <option>Saturday</option>
                      <option>Sunday</option>
                      <option>Monday</option>
                      <option>Tuesday</option>
                      <option>Wednesday</option>
                      <option>Thursday</option>
                    </select>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Time Slot</label>
                    <input type="text" id="courseTime" class="form-control" placeholder="e.g. 10:00 AM - 11:30 AM" />
                  </div>
                </div>

                <button type="button" class="btn btn-primary fw-bold px-4" onclick="addCourse()">
                  <i class="bi bi-save me-1"></i> Save Course & Offer to MySQL
                </button>
              </form>
            </div>
          </div>

          <!-- Course List -->
          <div class="card shadow border-0 mt-4">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
              <span><i class="bi bi-journal-text me-2"></i>Course Schedule & Registration Status List</span>
              <input type="text" id="courseSearch" class="form-control form-control-sm w-25" placeholder="Search Course..." onkeyup="searchCourse()" />
            </div>

            <div class="card-body">
              <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                  <tr>
                    <th>Code</th>
                    <th>Course Title</th>
                    <th>Credits</th>
                    <th>Faculty</th>
                    <th>Semester</th>
                    <th>Schedule</th>
                    <th>Reg Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody id="courseTableBody"></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="../js/script.js"></script>
  </body>
</html>
