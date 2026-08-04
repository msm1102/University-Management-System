<?php

session_start();
include_once __DIR__ . "/db.php";

$error = "";

// Auto-redirect if already logged in
if (isset($_SESSION['student_logged_in']) && $_SESSION['student_logged_in'] === true) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = trim($_POST['student_id'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($student_id) && !empty($password)) {
        $stmt = mysqli_prepare($conn, "SELECT * FROM students WHERE student_id = ?");
        mysqli_stmt_bind_param($stmt, "s", $student_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            // Verify password hash or plain fallback for initial seed
            if (password_verify($password, $row['password']) || $password === $row['password'] || $password === '1234') {
                $_SESSION['student_logged_in'] = true;
                $_SESSION['student_id'] = $row['student_id'];
                $_SESSION['student_name'] = $row['name'];
                $_SESSION['student_dept'] = $row['department'];
                $_SESSION['student_semester'] = $row['semester'];
                $_SESSION['student_email'] = $row['email'];
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Invalid Password. Please check and try again.";
            }
        } else {
            $error = "Student ID '$student_id' not found in system.";
        }
    } else {
        $error = "Please enter both Student ID and Password.";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal Login - UMS</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: #f8fafc; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .login-card { background: white; padding: 40px; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); width: 100%; max-width: 420px; border: 1px solid #e2e8f0; }
        .logo { font-size: 24px; font-weight: 700; color: #1e3a8a; text-align: center; margin-bottom: 20px; }
        .logo i { color: #2563eb; margin-right: 8px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: 600; font-size: 14px; margin-bottom: 8px; color: #334155; }
        .form-group input { width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; outline: none; }
        .form-group input:focus { border-color: #2563eb; }
        .btn-submit { width: 100%; padding: 12px; background: #2563eb; color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 15px; cursor: pointer; }
        .btn-submit:hover { background: #1d4ed8; }
        .alert-error { background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 8px; font-size: 13px; margin-bottom: 20px; text-align: center; }
        .hint { background: #eff6ff; color: #1e40af; padding: 12px; border-radius: 8px; font-size: 12px; margin-top: 20px; text-align: center; border: 1px solid #bfdbfe; }
    </style>
</head>
<body>

<div class="login-card">
    <div class="logo">
        <i class="fa-solid fa-graduation-cap"></i>Student Portal
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert-error">
            <i class="fa-solid fa-triangle-exclamation me-1"></i> <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="form-group">
            <label>Student ID</label>
            <input type="text" name="student_id" placeholder="e.g. 2024-001" value="2024-001" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Enter password (default: 1234)" value="1234" required>
        </div>

        <button type="submit" class="btn-submit">
            <i class="fa-solid fa-right-to-bracket me-1"></i> Log In to Student Portal
        </button>
    </form>

    <div class="hint">
        <i class="fa-solid fa-circle-info me-1"></i> Sample Credentials:<br>
        <strong>Student ID:</strong> <code>2024-001</code> | <strong>Password:</strong> <code>1234</code>
    </div>
</div>

</body>
</html>
