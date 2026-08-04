<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['student_logged_in']) || $_SESSION['student_logged_in'] !== true || empty($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

?>
