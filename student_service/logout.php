<?php

session_start();
session_unset();
session_destroy();

// Redirect to main University Home Page
header("Location: ../home.html");
exit;

?>
