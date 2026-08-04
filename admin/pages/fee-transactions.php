<?php
include_once __DIR__ . "/../auth_check.php";
include_once __DIR__ . "/../db.php";
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Fee Transactions & Approvals - UMS Admin</title>

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
          <li class="nav-item"><a href="fee-transactions.php" class="nav-link active"><i class="bi bi-cash-stack"></i> Fee Transactions</a></li>
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

      <div class="main-content">
        <nav class="navbar bg-white shadow-sm px-4">
          <h4>Fee Transactions & Payment Approvals</h4>
          <div>
            <span class="me-3 fw-bold text-primary" id="clock"></span>
            <span class="fw-bold"><i class="bi bi-person-circle text-primary me-1"></i> Welcome <?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></span>
          </div>
        </nav>

        <div class="container mt-4">
          <div class="alert alert-primary d-flex align-items-center shadow-sm" role="alert">
            <i class="bi bi-calculator-fill fs-4 me-3"></i>
            <div>
              <strong>Tuition Fee Standard:</strong> Calculated automatically at <strong>3,000 BDT per Credit Hour</strong>. Review student deposit receipts below.
            </div>
          </div>

          <div class="card shadow border-0">
            <div class="card-header bg-primary text-white">
              <i class="bi bi-credit-card me-2"></i>Issue Manual Student Fee Invoice
            </div>
            <div class="card-body">
              <form id="feeForm">
                <div class="row">
                  <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Student ID</label>
                    <input type="text" id="feeId" class="form-control" placeholder="2024-001" required />
                  </div>
                  <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Student Name</label>
                    <input type="text" id="feeName" class="form-control" placeholder="Student Name" required />
                  </div>
                  <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Department</label>
                    <input type="text" id="feeDepartment" class="form-control" placeholder="CSE" />
                  </div>
                  <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Semester</label>
                    <input type="text" id="feeSemester" class="form-control" placeholder="7th Semester" />
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Amount (BDT)</label>
                    <input type="number" id="feeAmount" class="form-control" placeholder="e.g. 27000" required />
                  </div>
                </div>
                <button type="button" class="btn btn-primary fw-bold" onclick="addFee()">
                  <i class="bi bi-save me-1"></i> Issue Fee Invoice to MySQL
                </button>
              </form>
            </div>
          </div>

          <div class="card shadow border-0 mt-4">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
              <span><i class="bi bi-receipt me-2"></i>Student Payment Transactions & Approvals</span>
            </div>
            <div class="card-body">
              <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                  <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Dept</th>
                    <th>Semester</th>
                    <th>Payment Details</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Approval Action</th>
                  </tr>
                </thead>
                <tbody id="feeTableBody"></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="../js/script.js"></script>
  </body>
</html>
