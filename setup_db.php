<?php

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "university-management-system";

// 1. Connect to MySQL server without selecting database
$conn = mysqli_connect($host, $user, $pass);

if (!$conn) {
    die("MySQL Server Connection Failed: " . mysqli_connect_error());
}

// 2. Create Database if not exists
$createDB = "CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if (!mysqli_query($conn, $createDB)) {
    die("Error creating database: " . mysqli_error($conn));
}

// 3. Select Database
mysqli_select_db($conn, $dbname);

// 4. SQL Schema Definitions
$queries = [
    "students" => "CREATE TABLE IF NOT EXISTS `students` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `student_id` VARCHAR(50) NOT NULL UNIQUE,
        `name` VARCHAR(100) NOT NULL,
        `email` VARCHAR(100) NOT NULL UNIQUE,
        `password` VARCHAR(255) NOT NULL,
        `phone` VARCHAR(20) DEFAULT NULL,
        `department` VARCHAR(50) NOT NULL,
        `semester` VARCHAR(50) NOT NULL,
        `cgpa` DECIMAL(3,2) DEFAULT 3.75,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

    "faculty" => "CREATE TABLE IF NOT EXISTS `faculty` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `faculty_id` VARCHAR(50) NOT NULL UNIQUE,
        `name` VARCHAR(100) NOT NULL,
        `email` VARCHAR(100) NOT NULL UNIQUE,
        `phone` VARCHAR(20) DEFAULT NULL,
        `department` VARCHAR(50) NOT NULL,
        `designation` VARCHAR(50) NOT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

    "courses" => "CREATE TABLE IF NOT EXISTS `courses` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `course_code` VARCHAR(20) NOT NULL UNIQUE,
        `course_title` VARCHAR(150) NOT NULL,
        `credits` DECIMAL(3,1) DEFAULT 3.0,
        `faculty_name` VARCHAR(100) DEFAULT NULL,
        `semester` VARCHAR(50) NOT NULL,
        `room_no` VARCHAR(50) DEFAULT NULL,
        `day` VARCHAR(50) DEFAULT NULL,
        `time_slot` VARCHAR(50) DEFAULT NULL,
        `is_offered` ENUM('Yes', 'No') DEFAULT 'Yes',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

    "course_registrations" => "CREATE TABLE IF NOT EXISTS `course_registrations` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `student_id` VARCHAR(50) NOT NULL,
        `course_code` VARCHAR(20) NOT NULL,
        `registration_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY `unique_reg` (`student_id`, `course_code`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

    "fee_transactions" => "CREATE TABLE IF NOT EXISTS `fee_transactions` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `student_id` VARCHAR(50) NOT NULL,
        `student_name` VARCHAR(100) NOT NULL,
        `department` VARCHAR(50) DEFAULT NULL,
        `semester` VARCHAR(50) DEFAULT NULL,
        `amount` INT NOT NULL,
        `details` VARCHAR(255) DEFAULT NULL,
        `status` ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
        `transaction_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

    "notices" => "CREATE TABLE IF NOT EXISTS `notices` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `title` VARCHAR(255) NOT NULL,
        `category` VARCHAR(50) NOT NULL,
        `notice_date` DATE NOT NULL,
        `content` TEXT NOT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

    "routines" => "CREATE TABLE IF NOT EXISTS `routines` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `routine_type` ENUM('Class', 'Exam') DEFAULT 'Class',
        `course_info` VARCHAR(150) NOT NULL,
        `faculty_info` VARCHAR(100) DEFAULT NULL,
        `semester` VARCHAR(50) NOT NULL,
        `day_or_date` VARCHAR(50) NOT NULL,
        `time_slot` VARCHAR(50) NOT NULL,
        `room_no` VARCHAR(50) NOT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

    "admin_users" => "CREATE TABLE IF NOT EXISTS `admin_users` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `username` VARCHAR(50) NOT NULL UNIQUE,
        `password` VARCHAR(255) NOT NULL,
        `full_name` VARCHAR(100) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
];

foreach ($queries as $tableName => $sql) {
    if (!mysqli_query($conn, $sql)) {
        echo "<p style='color:red;'>Error creating table $tableName: " . mysqli_error($conn) . "</p>";
    }
}

// 5. Insert Sample Seed Data if empty
// Admin User
$checkAdmin = mysqli_query($conn, "SELECT * FROM admin_users WHERE username='admin'");
if (mysqli_num_rows($checkAdmin) == 0) {
    $passHash = password_hash("1234", PASSWORD_DEFAULT);
    mysqli_query($conn, "INSERT INTO admin_users (username, password, full_name) VALUES ('admin', '$passHash', 'System Administrator')");
}

// Seed Students
$checkStudent = mysqli_query($conn, "SELECT * FROM students WHERE student_id='2024-001'");
if (mysqli_num_rows($checkStudent) == 0) {
    $passHash = password_hash("1234", PASSWORD_DEFAULT);
    mysqli_query($conn, "INSERT INTO students (student_id, name, email, password, phone, department, semester, cgpa) 
        VALUES ('2024-001', 'Sakib Khan Rony', 'sakib@nub.ac.bd', '$passHash', '01700000000', 'CSE', '7th Semester', 3.80)");
    mysqli_query($conn, "INSERT INTO students (student_id, name, email, password, phone, department, semester, cgpa) 
        VALUES ('2024-002', 'Nusrat Jahan', 'nusrat@nub.ac.bd', '$passHash', '01800000000', 'EEE', '5th Semester', 3.65)");
}

// Seed Faculty
$checkFaculty = mysqli_query($conn, "SELECT * FROM faculty WHERE faculty_id='F-101'");
if (mysqli_num_rows($checkFaculty) == 0) {
    mysqli_query($conn, "INSERT INTO faculty (faculty_id, name, email, phone, department, designation) 
        VALUES ('F-101', 'Prof. Md. Ahsan', 'ahsan@nub.ac.bd', '01711111111', 'CSE', 'Professor')");
    mysqli_query($conn, "INSERT INTO faculty (faculty_id, name, email, phone, department, designation) 
        VALUES ('F-102', 'Dr. Farhana Yasmin', 'farhana@nub.ac.bd', '01722222222', 'CSE', 'Associate Professor')");
}

// Seed Courses
$checkCourse = mysqli_query($conn, "SELECT * FROM courses WHERE course_code='CSE-301'");
if (mysqli_num_rows($checkCourse) == 0) {
    mysqli_query($conn, "INSERT INTO courses (course_code, course_title, credits, faculty_name, semester, room_no, day, time_slot, is_offered) 
        VALUES ('CSE-301', 'Database Management Systems', 3.0, 'Prof. Md. Ahsan', 'Spring', 'Lab 201', 'Sunday', '10:00 AM - 11:30 AM', 'Yes')");
    mysqli_query($conn, "INSERT INTO courses (course_code, course_title, credits, faculty_name, semester, room_no, day, time_slot, is_offered) 
        VALUES ('CSE-305', 'Software Engineering & Patterns', 3.0, 'Dr. Farhana Yasmin', 'Spring', 'Room 405', 'Tuesday', '11:30 AM - 01:00 PM', 'Yes')");
    mysqli_query($conn, "INSERT INTO courses (course_code, course_title, credits, faculty_name, semester, room_no, day, time_slot, is_offered) 
        VALUES ('CSE-401', 'Artificial Intelligence', 3.0, 'Dr. Kamrul Hasan', 'Fall', 'Lab 304', 'Monday', '09:00 AM - 10:30 AM', 'Yes')");
    mysqli_query($conn, "INSERT INTO courses (course_code, course_title, credits, faculty_name, semester, room_no, day, time_slot, is_offered) 
        VALUES ('MATH-201', 'Linear Algebra & Differential Equations', 3.0, 'Prof. S. R. Chowdhury', 'Spring', 'Room 102', 'Wednesday', '02:00 PM - 03:30 PM', 'Yes')");
}

// Seed Course Registrations for student 2024-001
$checkReg = mysqli_query($conn, "SELECT * FROM course_registrations WHERE student_id='2024-001'");
if (mysqli_num_rows($checkReg) == 0) {
    mysqli_query($conn, "INSERT INTO course_registrations (student_id, course_code) VALUES ('2024-001', 'CSE-301')");
    mysqli_query($conn, "INSERT INTO course_registrations (student_id, course_code) VALUES ('2024-001', 'CSE-305')");
    mysqli_query($conn, "INSERT INTO course_registrations (student_id, course_code) VALUES ('2024-001', 'CSE-401')");
}

// Seed Fee Transactions
$checkFee = mysqli_query($conn, "SELECT * FROM fee_transactions WHERE student_id='2024-001'");
if (mysqli_num_rows($checkFee) == 0) {
    mysqli_query($conn, "INSERT INTO fee_transactions (student_id, student_name, department, semester, amount, details, status) 
        VALUES ('2024-001', 'Sakib Khan Rony', 'CSE', '7th Semester', 27000, '9.0 Credits Registration (9 x 3,000 BDT)', 'Approved')");
}

// Seed Notices
$checkNotice = mysqli_query($conn, "SELECT * FROM notices");
if (mysqli_num_rows($checkNotice) == 0) {
    $today = date("Y-m-d");
    mysqli_query($conn, "INSERT INTO notices (title, category, notice_date, content) 
        VALUES ('Final Examination Routine Published', 'Exam', '$today', 'All undergraduate students are informed that the final examination schedule is released. Please check your routine section.')");
    mysqli_query($conn, "INSERT INTO notices (title, category, notice_date, content) 
        VALUES ('Next Semester Course Registration Open', 'Academic', '$today', 'Course registration for the upcoming semester is now active. Tuition rate is 3,000 BDT per credit hour.')");
}

// Seed Routines
$checkRoutine = mysqli_query($conn, "SELECT * FROM routines");
if (mysqli_num_rows($checkRoutine) == 0) {
    mysqli_query($conn, "INSERT INTO routines (routine_type, course_info, faculty_info, semester, day_or_date, time_slot, room_no) 
        VALUES ('Class', 'CSE-301 Database Systems', 'Prof. Md. Ahsan', '7th Semester', 'Sunday', '10:00 AM - 11:30 AM', 'Lab 201')");
    mysqli_query($conn, "INSERT INTO routines (routine_type, course_info, faculty_info, semester, day_or_date, time_slot, room_no) 
        VALUES ('Class', 'CSE-305 Software Engineering', 'Dr. Farhana Yasmin', '7th Semester', 'Tuesday', '11:30 AM - 01:00 PM', 'Room 405')");
    mysqli_query($conn, "INSERT INTO routines (routine_type, course_info, faculty_info, semester, day_or_date, time_slot, room_no) 
        VALUES ('Exam', 'CSE-301 Database Systems', 'Exam Committee', '7th Semester', '2026-08-15', '10:00 AM - 01:00 PM', 'Exam Hall 1 (Room 501)')");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Database Setup Successful</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
    <div class="container bg-white p-5 rounded shadow" style="max-width: 600px;">
        <h2 class="text-success mb-3">✅ Database Setup Complete!</h2>
        <p class="lead">Database <code>university-management-system</code> has been created and populated with sample tables and seed data.</p>
        <hr>
        <ul class="list-group mb-4">
            <li class="list-group-item d-flex justify-content-between"><span>students</span><span class="badge bg-success">Ready</span></li>
            <li class="list-group-item d-flex justify-content-between"><span>faculty</span><span class="badge bg-success">Ready</span></li>
            <li class="list-group-item d-flex justify-content-between"><span>courses</span><span class="badge bg-success">Ready</span></li>
            <li class="list-group-item d-flex justify-content-between"><span>course_registrations</span><span class="badge bg-success">Ready</span></li>
            <li class="list-group-item d-flex justify-content-between"><span>fee_transactions</span><span class="badge bg-success">Ready</span></li>
            <li class="list-group-item d-flex justify-content-between"><span>notices</span><span class="badge bg-success">Ready</span></li>
            <li class="list-group-item d-flex justify-content-between"><span>routines</span><span class="badge bg-success">Ready</span></li>
            <li class="list-group-item d-flex justify-content-between"><span>admin_users</span><span class="badge bg-success">Ready</span></li>
        </ul>
        <div class="d-grid gap-2">
            <a href="admin/index.html" class="btn btn-primary">Go to Admin Login Panel</a>
            <a href="student_service/login.html" class="btn btn-secondary">Go to Student Service Portal</a>
        </div>
    </div>
</body>
</html>
