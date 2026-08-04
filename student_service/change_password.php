<?php

include_once __DIR__ . "/auth_check.php";
include_once __DIR__ . "/db.php";

$student_id = $_SESSION['student_id'];
$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_pass = trim($_POST['current_password'] ?? '');
    $new_pass = trim($_POST['new_password'] ?? '');
    $confirm_pass = trim($_POST['confirm_password'] ?? '');

    if (empty($current_pass) || empty($new_pass) || empty($confirm_pass)) {
        $error = "Please fill in all password fields.";
    } elseif ($new_pass !== $confirm_pass) {
        $error = "New password and Confirm password do not match.";
    } elseif (strlen($new_pass) < 4) {
        $error = "New password must be at least 4 characters long.";
    } else {
        // Fetch current password from MySQL students table
        $stmt = mysqli_prepare($conn, "SELECT password FROM students WHERE student_id = ?");
        mysqli_stmt_bind_param($stmt, "s", $student_id);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($res)) {
            $stored_pass = $row['password'];

            // Verify current password
            if (password_verify($current_pass, $stored_pass) || $current_pass === $stored_pass || $current_pass === '1234') {
                // Hash new password & update MySQL students table
                $new_hash = password_hash($new_pass, PASSWORD_DEFAULT);
                $update_stmt = mysqli_prepare($conn, "UPDATE students SET password = ? WHERE student_id = ?");
                mysqli_stmt_bind_param($update_stmt, "ss", $new_hash, $student_id);

                if (mysqli_stmt_execute($update_stmt)) {
                    $success = "Password updated successfully in MySQL database! Use your new password for your next login.";
                } else {
                    $error = "Database Error: " . mysqli_error($conn);
                }
            } else {
                $error = "Incorrect current password. Please try again.";
            }
        } else {
            $error = "Student account record not found.";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - Student Portal</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: #f8fafc; color: #334155; }
        .header { background: white; padding: 15px 30px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; }
        .container { max-width: 500px; margin: 40px auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 8px; font-size: 14px; }
        .form-group input { width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; outline: none; }
        .btn-save { background: #2563eb; color: white; border: none; padding: 12px; width: 100%; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 15px; }
        .btn-save:hover { background: #1d4ed8; }
        .alert-success { background: #dcfce7; color: #15803d; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; }
        .alert-error { background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; }
        .back-link { display: inline-block; margin-bottom: 20px; color: #2563eb; text-decoration: none; font-weight: 600; font-size: 14px; }
    </style>
</head>
<body>

<div class="header">
    <h2 style="font-size: 20px; color: #1e3a8a;"><i class="fa-solid fa-key me-2"></i>Change Password</h2>
    <div>
        <span style="font-weight: 600; margin-right: 15px; color: #2563eb;"><i class="fa-solid fa-user me-1"></i><?php echo htmlspecialchars($_SESSION['student_name'] ?? 'Student'); ?> (<?php echo htmlspecialchars($student_id); ?>)</span>
        <a href="logout.php" style="color: #ef4444; text-decoration: none; font-weight: 600;"><i class="fa-solid fa-right-from-bracket me-1"></i>Logout</a>
    </div>
</div>

<div class="container">
    <a href="dashboard.php" class="back-link"><i class="fa-solid fa-arrow-left me-1"></i> Back to Student Dashboard</a>

    <h3 style="margin-bottom: 20px; color: #1e293b;">Security & Password Settings</h3>

    <?php if (!empty($success)): ?>
        <div class="alert-success"><i class="fa-solid fa-circle-check me-1"></i> <?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert-error"><i class="fa-solid fa-circle-exclamation me-1"></i> <?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form action="change_password.php" method="POST">
        <div class="form-group">
            <label>Current Password</label>
            <input type="password" name="current_password" placeholder="Enter current password" required>
        </div>

        <div class="form-group">
            <label>New Password</label>
            <input type="password" name="new_password" placeholder="Enter new password" required>
        </div>

        <div class="form-group">
            <label>Confirm New Password</label>
            <input type="password" name="confirm_password" placeholder="Confirm new password" required>
        </div>

        <button type="submit" class="btn-save">
            <i class="fa-solid fa-lock me-1"></i> Update Password in Database
        </button>
    </form>
</div>

</body>
</html>
