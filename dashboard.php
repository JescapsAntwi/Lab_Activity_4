<?php
require_once 'includes/session.php';
require_once 'includes/auth.php';

requireLogin();

// Route to appropriate dashboard based on role
if ($_SESSION['role'] === 'faculty') {
    header('Location: faculty/dashboard.php');
} else {
    header('Location: student/dashboard.php');
}
exit;
