<?php
include_once __DIR__ . "/auth_check.php";
include_once __DIR__ . "/db.php";

$student_id = $_SESSION['student_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fee Payment & Tuition Accounting - Student Portal</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: #f8fafc; color: #334155; }
        .sidebar { width: 260px; background: #1e293b; color: white; position: fixed; height: 100vh; padding: 20px 0; }
        .sidebar h2 { text-align: center; font-size: 20px; font-weight: 700; margin-bottom: 30px; color: #38bdf8; }
        .sidebar a { display: block; color: #94a3b8; padding: 14px 25px; text-decoration: none; font-weight: 600; font-size: 14px; transition: 0.2s; }
        .sidebar a:hover, .sidebar a.active { background: #334155; color: white; border-left: 4px solid #38bdf8; }
        .main { margin-left: 260px; padding: 30px; }
        .navbar { background: white; padding: 15px 25px; border-radius: 12px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 8px rgba(0,0,0,0.04); margin-bottom: 30px; }
        .card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); margin-bottom: 30px; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { text-align: left; padding: 12px; border-bottom: 1px solid #e2e8f0; font-size: 14px; }
        th { background: #f1f5f9; color: #334155; font-weight: 700; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: 600; font-size: 13px; margin-bottom: 6px; }
        .form-group input, .form-group select { width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; }
        .btn-submit { width: 100%; padding: 12px; background: #16a34a; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2><i class="fa-solid fa-graduation-cap me-2"></i>UMS Student</h2>
    <a href="dashboard.php"><i class="fa-solid fa-house me-2"></i>Dashboard</a>
    <a href="courses.php"><i class="fa-solid fa-book me-2"></i>Course Registration</a>
    <a href="fee_payment.php" class="active"><i class="fa-solid fa-wallet me-2"></i>Tuition & Payments</a>
    <a href="notice.php"><i class="fa-solid fa-bullhorn me-2"></i>Notice Board</a>
    <a href="routine.php"><i class="fa-solid fa-clock me-2"></i>Class & Exam Routine</a>
    <a href="profile.php"><i class="fa-solid fa-user me-2"></i>My Profile</a>
    <a href="change_password.php"><i class="fa-solid fa-key me-2"></i>Change Password</a>
    <a href="logout.php" style="color: #f87171; margin-top: 40px;"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</a>
</div>

<div class="main">
    <div class="navbar">
        <h3><i class="fa-solid fa-wallet me-2" style="color: #16a34a;"></i>Credit-Based Tuition Fee & Payment Center</h3>
        <div>
            <span style="font-weight: 600; color: #1e3a8a; background: #e0f2fe; padding: 6px 14px; border-radius: 20px; font-size: 13px;">Rate Standard: 3,000 BDT / Credit</span>
        </div>
    </div>

    <div class="grid-2">
        <!-- Tuition Breakdown Card -->
        <div class="card">
            <h3 style="font-size: 18px; color: #1e3a8a; margin-bottom: 15px;"><i class="fa-solid fa-calculator me-2" style="color: #2563eb;"></i>Current Tuition Summary</h3>
            <div style="background: #f8fafc; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 20px;">
                <p style="font-size: 14px; margin-bottom: 8px;">Enrolled Credits Total: <strong id="studentFeeCredits" style="color: #2563eb;">0.0 Credits</strong></p>
                <p style="font-size: 14px; margin-bottom: 8px;">Calculated Tuition Fee: <strong id="studentTotalFeeAmount" style="color: #16a34a; font-size: 18px;">0 BDT</strong></p>
                <p style="font-size: 14px; margin-bottom: 8px;">Approved Paid Amount: <strong id="studentPaidStatus" style="color: #15803d;">0 BDT</strong></p>
                <p style="font-size: 14px;">Pending Approval: <strong id="studentPendingFeeAmount" style="color: #d97706;">0 BDT</strong></p>
            </div>
        </div>

        <!-- Submit Payment Receipt Card -->
        <div class="card">
            <h3 style="font-size: 18px; color: #166534; margin-bottom: 15px;"><i class="fa-solid fa-money-bill-transfer me-2"></i>Deposit Payment Receipt</h3>
            <form id="studentPaymentForm" onsubmit="submitStudentPayment(event)">
                <div class="form-group">
                    <label>Payment Method</label>
                    <select id="payMethod">
                        <option value="bKash Mobile Banking">bKash Mobile Banking</option>
                        <option value="Nagad Mobile Banking">Nagad Mobile Banking</option>
                        <option value="Bank Online Transfer">Bank Online Transfer</option>
                        <option value="Cash Deposit at Accounts">Cash Deposit at Accounts</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Transaction ID / Deposit Slip No.</label>
                    <input type="text" id="payTrxId" placeholder="e.g. BK789XYZ12" required>
                </div>
                <div class="form-group">
                    <label>Amount (BDT)</label>
                    <input type="number" id="payAmount" placeholder="e.g. 27000" required>
                </div>
                <button type="submit" class="btn-submit"><i class="fa-solid fa-paper-plane me-1"></i> Submit Payment Receipt for Admin Approval</button>
            </form>
        </div>
    </div>

    <!-- History Card -->
    <div class="card">
        <h3 style="font-size: 18px; color: #1e293b; margin-bottom: 15px;"><i class="fa-solid fa-receipt me-2"></i>Payment Submission & Approval History</h3>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Payment Details / TrxID</th>
                    <th>Amount</th>
                    <th>Admin Approval Status</th>
                </tr>
            </thead>
            <tbody id="studentFeeHistoryTbody"></tbody>
        </table>
    </div>
</div>

<script src="dashboard.js"></script>
</body>
</html>
