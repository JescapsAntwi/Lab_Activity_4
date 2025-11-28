<?php
require_once '../includes/session.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireRole('faculty');

$sessionId = isset($_GET['session_id']) ? sanitizeInput($_GET['session_id']) : '';

if (empty($sessionId)) {
    header('Location: manage_sessions.php');
    exit;
}

$sessions = readJSON('sessions.json');
$session = null;

foreach ($sessions as $s) {
    if ($s['id'] === $sessionId && $s['faculty_id'] === $_SESSION['user_id']) {
        $session = $s;
        break;
    }
}

if (!$session) {
    header('Location: manage_sessions.php');
    exit;
}

// Get course info
$courses = readJSON('courses.json');
$course = null;
foreach ($courses as $c) {
    if ($c['id'] === $session['course_id']) {
        $course = $c;
        break;
    }
}

// Get enrolled students
$enrollments = readJSON('enrollments.json');
$enrolledStudents = array_filter($enrollments, function($e) use ($session) {
    return $e['course_id'] === $session['course_id'];
});

// Get attendance records
$attendance = readJSON('attendance.json');
$sessionAttendance = array_filter($attendance, function($a) use ($sessionId) {
    return $a['session_id'] === $sessionId;
});

// Get user info
$users = readJSON('users.json');
$userMap = [];
foreach ($users as $user) {
    $userMap[$user['id']] = $user;
}

// Create attendance map
$attendanceMap = [];
foreach ($sessionAttendance as $att) {
    $attendanceMap[$att['student_id']] = $att;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Attendance</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-brand">
                <h2>🎓 Ashesi CMS</h2>
            </div>
            <div class="nav-links">
                <a href="dashboard.php">Dashboard</a>
                <a href="manage_sessions.php">Sessions</a>
                <a href="../logout.php" class="btn btn-secondary">Logout</a>
            </div>
        </div>
    </nav>
    
    <div class="container">
        <div class="dashboard">
            <h1>Session Attendance</h1>
            
            <div class="session-info">
                <h2><?php echo htmlspecialchars($course['course_name']); ?></h2>
                <p><strong>Date:</strong> <?php echo $session['session_date']; ?></p>
                <p><strong>Time:</strong> <?php echo $session['session_time']; ?></p>
                <p><strong>Code:</strong> <?php echo $session['code']; ?></p>
                <p><strong>Status:</strong> <span class="status status-<?php echo $session['status']; ?>"><?php echo ucfirst($session['status']); ?></span></p>
            </div>
            
            <div class="stats">
                <div class="stat-card">
                    <h3><?php echo count($sessionAttendance); ?> / <?php echo count($enrolledStudents); ?></h3>
                    <p>Students Present</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo count($enrolledStudents) > 0 ? round((count($sessionAttendance) / count($enrolledStudents)) * 100) : 0; ?>%</h3>
                    <p>Attendance Rate</p>
                </div>
            </div>
            
            <div class="section">
                <h2>Student Attendance</h2>
                <div class="table-container">
                    <table class="requests-table">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Marked At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($enrolledStudents as $enrollment): ?>
                                <?php 
                                $student = $userMap[$enrollment['student_id']] ?? null;
                                $attended = isset($attendanceMap[$enrollment['student_id']]);
                                ?>
                                <tr>
                                    <td><?php echo $student ? htmlspecialchars($student['username']) : 'Unknown'; ?></td>
                                    <td><?php echo $student ? htmlspecialchars($student['email']) : 'Unknown'; ?></td>
                                    <td>
                                        <?php if ($attended): ?>
                                            <span class="status status-approved">Present</span>
                                        <?php else: ?>
                                            <span class="status status-rejected">Absent</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php echo $attended ? $attendanceMap[$enrollment['student_id']]['marked_at'] : '-'; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
