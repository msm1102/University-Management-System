<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // If inside /pages/ subdirectory, redirect to ../index.php
    $redirect = strpos($_SERVER['SCRIPT_NAME'], '/pages/') !== false ? '../index.php' : 'index.php';
    header("Location: " . $redirect);
    exit;
}

?>
