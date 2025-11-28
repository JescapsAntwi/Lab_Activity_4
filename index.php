<?php
require_once 'includes/session.php';
require_once 'includes/auth.php';

if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

header('Location: welcome.php');
exit;

