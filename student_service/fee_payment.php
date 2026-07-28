<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
require_once 'db.php';

$user_email = $_SESSION['user_email'];

$summary_sql = "SELECT total_fee, paid_amount, due_amount, deadline FROM fee_summary WHERE student_email = ?";
$stmt = $conn->prepare($summary_sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$summary_result = $stmt->get_result();
$fee = $summary_result->fetch_assoc();
$stmt->close();

$history_sql = "SELECT payment_date, receipt_no, amount, method, status FROM payment_history WHERE student_email = ? ORDER BY payment_date DESC";
$stmt2 = $conn->prepare($history_sql);
$stmt2->bind_param("s", $user_email);
$stmt2->execute();
$history_result = $stmt2->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fee Payment</title>
    <link rel="stylesheet" href="fee_payment.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>

<div class="container">

    <div class="header">
        <h1>Fee Payment</h1>
        <a href="dashboard.php" class="back-btn">
            <i class="fa-solid fa-arrow-left"></i> Dashboard
        </a>
    </div>

    <div class="cards">
        <div class="card">
            <h3>Total Fee</h3>
            <h2>৳<?php echo htmlspecialchars($fee['total_fee'] ?? '0'); ?></h2>
        </div>
        <div class="card">
            <h3>Paid Amount</h3>
            <h2>৳<?php echo htmlspecialchars($fee['paid_amount'] ?? '0'); ?></h2>
        </div>
        <div class="card">
            <h3>Due Amount</h3>
            <h2>৳<?php echo htmlspecialchars($fee['due_amount'] ?? '0'); ?></h2>
        </div>
        <div class="card">
            <h3>Payment Deadline</h3>
            <h2><?php echo !empty($fee['deadline']) ? date('d M Y', strtotime($fee['deadline'])) : 'N/A'; ?></h2>
        </div>
    </div>

    <h2>Payment History</h2>
    <table>
        <thead>
        <tr>
            <th>Date</th>
            <th>Receipt No.</th>
            <th>Amount</th>
            <th>Method</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($history_result && $history_result->num_rows > 0): ?>
            <?php while ($row = $history_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo date('d M Y', strtotime($row['payment_date'])); ?></td>
                    <td><?php echo htmlspecialchars($row['receipt_no']); ?></td>
                    <td>৳<?php echo htmlspecialchars($row['amount']); ?></td>
                    <td><?php echo htmlspecialchars($row['method']); ?></td>
                    <td><span class="<?php echo strtolower($row['status']) == 'paid' ? 'paid' : 'unpaid'; ?>"><?php echo htmlspecialchars($row['status']); ?></span></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" style="text-align: center;">No payment history found.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

    <button class="download">
        <i class="fa-solid fa-download"></i>
        Download Receipt
    </button>

</div>

</body>
</html>