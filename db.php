<?php

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "university-management-system";

// Disable default fatal exception reporting in PHP 8+ for custom graceful handling
mysqli_report(MYSQLI_REPORT_OFF);

// 1. Try connecting directly to database
$conn = @mysqli_connect($host, $user, $pass, $dbname);

// 2. If database does not exist, connect to MySQL server and auto-create database & tables
if (!$conn) {
    $serverConn = @mysqli_connect($host, $user, $pass);
    if ($serverConn) {
        // Create Database
        mysqli_query($serverConn, "CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        mysqli_select_db($serverConn, $dbname);
        $conn = $serverConn;

        // Auto-create required tables
        $tables = [
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

        foreach ($tables as $tSql) {
            mysqli_query($conn, $tSql);
        }

        // Seed default Admin & Student accounts if empty
        $passHash = password_hash("1234", PASSWORD_DEFAULT);
        @mysqli_query($conn, "INSERT IGNORE INTO admin_users (username, password, full_name) VALUES ('admin', '$passHash', 'System Administrator')");
        @mysqli_query($conn, "INSERT IGNORE INTO students (student_id, name, email, password, phone, department, semester, cgpa) VALUES ('2024-001', 'Sakib Khan Rony', 'sakib@nub.ac.bd', '$passHash', '01700000000', 'CSE', '7th Semester', 3.80)");
        @mysqli_query($conn, "INSERT IGNORE INTO courses (course_code, course_title, credits, faculty_name, semester, room_no, day, time_slot, is_offered) VALUES ('CSE-301', 'Database Management Systems', 3.0, 'Prof. Md. Ahsan', 'Spring', 'Lab 201', 'Sunday', '10:00 AM - 11:30 AM', 'Yes')");
        @mysqli_query($conn, "INSERT IGNORE INTO courses (course_code, course_title, credits, faculty_name, semester, room_no, day, time_slot, is_offered) VALUES ('CSE-305', 'Software Engineering & Patterns', 3.0, 'Dr. Farhana Yasmin', 'Spring', 'Room 405', 'Tuesday', '11:30 AM - 01:00 PM', 'Yes')");
        @mysqli_query($conn, "INSERT IGNORE INTO course_registrations (student_id, course_code) VALUES ('2024-001', 'CSE-301')");
        @mysqli_query($conn, "INSERT IGNORE INTO fee_transactions (student_id, student_name, department, semester, amount, details, status) VALUES ('2024-001', 'Sakib Khan Rony', 'CSE', '7th Semester', 27000, '9.0 Credits Registration (9 x 3,000 BDT)', 'Approved')");
        @mysqli_query($conn, "INSERT IGNORE INTO notices (title, category, notice_date, content) VALUES ('Final Examination Routine Published', 'Exam', CURDATE(), 'All undergraduate students are informed that the final examination schedule is released.')");
        @mysqli_query($conn, "INSERT IGNORE INTO routines (routine_type, course_info, faculty_info, semester, day_or_date, time_slot, room_no) VALUES ('Class', 'CSE-301 Database Systems', 'Prof. Md. Ahsan', '7th Semester', 'Sunday', '10:00 AM - 11:30 AM', 'Lab 201')");
    } else {
        die("MySQL Connection Failed. Please ensure MySQL is started in XAMPP Control Panel.");
    }
}

mysqli_set_charset($conn, "utf8mb4");

?>
