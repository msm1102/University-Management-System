<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
require_once 'db.php';

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $user_email = $_SESSION['user_email'];

    $sql = "SELECT password FROM students WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $db_password = $row['password'];

        if (password_verify($current_password, $db_password)) {
            if ($new_password === $confirm_password) {
                if (preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $new_password)) {
                    $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    
                    $update_sql = "UPDATE students SET password = ? WHERE email = ?";
                    $update_stmt = $conn->prepare($update_sql);
                    $update_stmt->bind_param("ss", $new_hashed_password, $user_email);
                    
                    if ($update_stmt->execute()) {
                        $message = "Password updated successfully.";
                        $message_type = "green";
                    } else {
                        $message = "Something went wrong. Please try again.";
                        $message_type = "red";
                    }
                    $update_stmt->close();
                } else {
                    $message = "New password does not meet the requirements.";
                    $message_type = "red";
                }
            } else {
                $message = "New passwords do not match.";
                $message_type = "red";
            }
        } else {
            $message = "Incorrect current password.";
            $message_type = "red";
        }
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="change_password.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body>

<div class="container">

    <div class="header">
        <h1>Change Password</h1>

        <a href="dashboard.php" class="back-btn">
            <i class="fa-solid fa-arrow-left"></i> Dashboard
        </a>
    </div>

    <div class="card">

        <?php if ($message): ?>
            <p style="text-align: center; margin-bottom: 15px; font-weight: 500; color: <?php echo $message_type; ?>;">
                <?php echo htmlspecialchars($message); ?>
            </p>
        <?php endif; ?>

        <form method="POST" action="">

            <label for="current_password">Current Password</label>
            <input
                type="password"
                id="current_password"
                name="current_password"
                placeholder="Enter Current Password"
                required
            >

            <label for="new_password">New Password</label>
            <input
                type="password"
                id="new_password"
                name="new_password"
                placeholder="Enter New Password"
                required
            >

            <div class="password-note">
                <h3>
                    <i class="fa-solid fa-shield-halved"></i>
                    Strong Password Requirements
                </h3>
                <ul>
                    <li>Minimum <strong>8 characters</strong>.</li>
                    <li>Include at least <strong>one uppercase letter (A–Z)</strong>.</li>
                    <li>Include at least <strong>one lowercase letter (a–z)</strong>.</li>
                    <li>Include at least <strong>one number (0–9)</strong>.</li>
                    <li>Include at least <strong>one special character</strong> (e.g. ! @ # $ % ^ & *).</li>
                    <li>Do <strong>not</strong> use your Student ID, name, or date of birth.</li>
                    <li>Do not reuse your previous password.</li>
                </ul>
            </div>

            <label for="confirm_password">Confirm New Password</label>
            <input
                type="password"
                id="confirm_password"
                name="confirm_password"
                placeholder="Confirm New Password"
                required
            >

            <button type="submit">
                <i class="fa-solid fa-key"></i>
                Update Password
            </button>

        </form>

    </div>

</div>

</body>
</html>