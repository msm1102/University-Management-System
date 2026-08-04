<?php
include_once __DIR__ . "/../auth_check.php";
include_once __DIR__ . "/../db.php";
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Routine Management - UMS Admin</title>

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
          <li class="nav-item"><a href="routine.php" class="nav-link active"><i class="bi bi-clock-history"></i> Routine Management</a></li>
          <li class="nav-item"><a href="students.php" class="nav-link"><i class="bi bi-people"></i> Students</a></li>
          <li class="nav-item"><a href="faculty.php" class="nav-link"><i class="bi bi-person-workspace"></i> Faculty</a></li>
          <li class="nav-item"><a href="reports.php" class="nav-link"><i class="bi bi-file-earmark-bar-graph"></i> Reports</a></li>
          <li class="nav-item"><a href="settings.php" class="nav-link"><i class="bi bi-gear"></i> Settings</a></li>
          <li class="nav-item mt-4"><a href="../logout.php" class="nav-link text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
        </ul>
      </div>

      <div class="main-content">
        <nav class="navbar bg-white shadow-sm px-4">
          <h4>Class & Exam Routine Management</h4>
          <div>
            <span class="me-3 fw-bold text-primary" id="clock"></span>
            <span class="fw-bold"><i class="bi bi-person-circle text-primary me-1"></i> Welcome <?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></span>
          </div>
        </nav>

        <div class="container mt-4">
          <!-- Class Routine Card -->
          <div class="card shadow border-0 mb-4">
            <div class="card-header bg-primary text-white">
              <i class="bi bi-calendar3 me-2"></i>Add Weekly Class Timetable Slot
            </div>
            <div class="card-body">
              <form id="classRoutineForm">
                <div class="row">
                  <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Course Info</label>
                    <input type="text" id="crCourse" class="form-control" placeholder="e.g. CSE-301 Database Systems" required />
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Faculty</label>
                    <input type="text" id="crFaculty" class="form-control" placeholder="e.g. Prof. Md. Ahsan" />
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Semester</label>
                    <input type="text" id="crSemester" class="form-control" placeholder="e.g. 7th Semester" />
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Day</label>
                    <select id="crDay" class="form-select">
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
                    <input type="text" id="crTime" class="form-control" placeholder="09:00 AM - 10:30 AM" required />
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Room No.</label>
                    <input type="text" id="crRoom" class="form-control" placeholder="Lab 201" />
                  </div>
                </div>
                <button type="button" class="btn btn-primary fw-bold" onclick="addClassRoutine()">
                  <i class="bi bi-plus-circle me-1"></i> Add Class Routine Slot
                </button>
              </form>
            </div>
          </div>

          <!-- Class Routine Table -->
          <div class="card shadow border-0 mb-5">
            <div class="card-header bg-dark text-white">Published Weekly Class Timetable</div>
            <div class="card-body">
              <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                  <tr>
                    <th>Day</th>
                    <th>Time Slot</th>
                    <th>Course</th>
                    <th>Faculty</th>
                    <th>Semester</th>
                    <th>Room</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody id="classRoutineTableBody"></tbody>
              </table>
            </div>
          </div>

          <!-- Exam Routine Card -->
          <div class="card shadow border-0 mb-4">
            <div class="card-header bg-danger text-white">
              <i class="bi bi-file-earmark-spreadsheet me-2"></i>Add Exam Schedule Routine Entry
            </div>
            <div class="card-body">
              <form id="examRoutineForm">
                <div class="row">
                  <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Course Info</label>
                    <input type="text" id="erCourse" class="form-control" placeholder="e.g. CSE-301 Database Systems" required />
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Exam Date</label>
                    <input type="date" id="erDate" class="form-control" required />
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Time Slot</label>
                    <input type="text" id="erTime" class="form-control" placeholder="10:00 AM - 01:00 PM" required />
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Exam Hall / Room</label>
                    <input type="text" id="erRoom" class="form-control" placeholder="Exam Hall 1 (Room 501)" />
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Semester</label>
                    <input type="text" id="erSemester" class="form-control" placeholder="7th Semester" />
                  </div>
                </div>
                <button type="button" class="btn btn-danger fw-bold" onclick="addExamRoutine()">
                  <i class="bi bi-plus-circle me-1"></i> Add Exam Schedule Slot
                </button>
              </form>
            </div>
          </div>

          <!-- Exam Routine Table -->
          <div class="card shadow border-0">
            <div class="card-header bg-dark text-white">Published Semester Examination Timetable</div>
            <div class="card-body">
              <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                  <tr>
                    <th>Date</th>
                    <th>Time Slot</th>
                    <th>Course</th>
                    <th>Semester</th>
                    <th>Exam Hall / Room</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody id="examRoutineTableBody"></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="../js/script.js"></script>
  </body>
</html>
