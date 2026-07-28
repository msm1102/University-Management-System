<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
require_once 'db.php';

$sql = "SELECT title, published_date, description FROM notices ORDER BY published_date DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Notices</title>
    <link rel="stylesheet" href="notices.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>

<div class="container">

    <div class="header">
        <h1>University Notices</h1>
        <a href="dashboard.php" class="back-btn">
            <i class="fa-solid fa-arrow-left"></i> Dashboard
        </a>
    </div>

    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($notice = $result->fetch_assoc()): ?>
            <div class="notice-card">
                <h2>📢 <?php echo htmlspecialchars($notice['title']); ?></h2>
                <span>Published: <?php echo date('d F Y', strtotime($notice['published_date'])); ?></span>
                <p><?php echo nl2br(htmlspecialchars($notice['description'])); ?></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="notice-card">
            <p>No notices available.</p>
        </div>
    <?php endif; ?>

</div>

</body>
</html>