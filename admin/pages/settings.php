<?php
include_once __DIR__ . "/../auth_check.php";
include_once __DIR__ . "/../db.php";

$msg = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adminName = trim($_POST['adminName'] ?? '');
    $newPassword = trim($_POST['adminPassword'] ?? '');

    if (!empty($adminName)) {
        $_SESSION['admin_name'] = $adminName;
        $username = $_SESSION['admin_user'] ?? 'admin';

        if (!empty($newPassword)) {
            $passHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($conn, "UPDATE admin_users SET full_name = ?, password = ? WHERE username = ?");
            mysqli_stmt_bind_param($stmt, "sss", $adminName, $passHash, $username);
            mysqli_stmt_execute($stmt);
        } else {
            $stmt = mysqli_prepare($conn, "UPDATE admin_users SET full_name = ? WHERE username = ?");
            mysqli_stmt_bind_param($stmt, "ss", $adminName, $username);
            mysqli_stmt_execute($stmt);
        }
        $msg = "Admin Settings updated successfully in MySQL!";
    } else {
        $error = "Admin name cannot be empty.";
    }
}

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Settings - UMS Admin</title>

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
          <li class="nav-item"><a href="reports.php" class="nav-link"><i class="bi bi-file-earmark-bar-graph"></i> Reports</a></li>
          <li class="nav-item"><a href="settings.php" class="nav-link active"><i class="bi bi-gear"></i> Settings</a></li>
          <li class="nav-item mt-4"><a href="../logout.php" class="nav-link text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
        </ul>
      </div>

      <div class="main-content">
        <nav class="navbar bg-white shadow-sm px-4">
          <h4>Admin Account Settings</h4>
          <div>
            <span class="me-3 fw-bold text-primary" id="clock"></span>
            <span class="fw-bold"><i class="bi bi-person-circle text-primary me-1"></i> Welcome <?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></span>
          </div>
        </nav>

        <div class="container mt-4" style="max-width: 600px;">
          <?php if (!empty($msg)): ?>
            <div class="alert alert-success py-2"><i class="bi bi-check-circle-fill me-1"></i> <?php echo htmlspecialchars($msg); ?></div>
          <?php endif; ?>
          <?php if (!empty($error)): ?>
            <div class="alert alert-danger py-2"><i class="bi bi-exclamation-triangle-fill me-1"></i> <?php echo htmlspecialchars($error); ?></div>
          <?php endif; ?>

          <div class="card shadow border-0">
            <div class="card-header bg-primary text-white">
              <i class="bi bi-gear-fill me-2"></i>Update Admin Credentials
            </div>
            <div class="card-body">
              <form action="settings.php" method="POST">
                <div class="mb-3">
                  <label class="form-label fw-bold">Administrator Name</label>
                  <input type="text" name="adminName" class="form-control" value="<?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'System Administrator'); ?>" required />
                </div>
                <div class="mb-3">
                  <label class="form-label fw-bold">New Password (leave blank to keep current)</label>
                  <input type="password" name="adminPassword" class="form-control" placeholder="Enter new password" />
                </div>
                <button type="submit" class="btn btn-primary fw-bold w-100">
                  <i class="bi bi-save me-1"></i> Save Settings to MySQL
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="../js/script.js"></script>
  </body>
</html>
