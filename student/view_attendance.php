<?php
require_once '../includes/session.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireRole('student');

// Get student's enrollments
$enrollments = readJSON('enrollments.json');
$myEnrollments = array_filter($enrollments, function($e) {
    return $e['student_id'] === $_SESSION['user_id'];
});

$myCourseIds = array_column($myEnrollments, 'course_id');

// Get courses
$courses = readJSON('courses.json');
$courseMap = [];
foreach ($courses as $course) {
    $courseMap[$course['id']] = $course;
}

// Get all sessions for enrolled courses
$sessions = readJSON('sessions.json');
$mySessions = array_filter($sessions, function($s) use ($myCourseIds) {
    return in_array($s['course_id'], $myCourseIds);
});

// Get attendance records
$attendance = readJSON('attendance.json');
$myAttendance = array_filter($attendance, function($a) {
    return $a['student_id'] === $_SESSION['user_id'];
});

// Create attendance map
$attendanceMap = [];
foreach ($myAttendance as $att) {
    $attendanceMap[$att['session_id']] = $att;
}

// Calculate stats per course
$courseStats = [];
foreach ($myCourseIds as $courseId) {
    $courseSessions = array_filter($mySessions, function($s) use ($courseId) {
        return $s['course_id'] === $courseId;
    });
    
    $courseAttendance = array_filter($myAttendance, function($a) use ($courseId) {
        return $a['course_id'] === $courseId;
    });
    
    $total = count($courseSessions);
    $attended = count($courseAttendance);
    $percentage = $total > 0 ? round(($attended / $total) * 100) : 0;
    
    $courseStats[$courseId] = [
        'total' => $total,
        'attended' => $attended,
        'percentage' => $percentage
    ];
}

// Sort sessions by date
usort($mySessions, function($a, $b) {
    return strtotime($b['session_date'] . ' ' . $b['session_time']) - strtotime($a['session_date'] . ' ' . $a['session_time']);
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Attendance</title>
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
                <a href="mark_attendance.php" class="btn btn-primary">Mark Attendance</a>
                <a href="../logout.php" class="btn btn-secondary">Logout</a>
            </div>
        </div>
    </nav>
    
    <div class="container">
        <div class="dashboard">
            <h1>My Attendance Records</h1>
            
            <div class="section">
                <h2>Course Summary</h2>
                <?php if (empty($courseStats)): ?>
                    <p class="empty-state">No courses enrolled yet.</p>
                <?php else: ?>
                    <div class="course-grid">
                        <?php foreach ($courseStats as $courseId => $stats): ?>
                            <?php $course = $courseMap[$courseId]; ?>
                            <div class="course-card">
                                <h3><?php echo htmlspecialchars($course['course_name']); ?></h3>
                                <p class="course-code"><?php echo htmlspecialchars($course['course_code']); ?></p>
                                <div class="attendance-stats">
                                    <p><strong>Sessions Attended:</strong> <?php echo $stats['attended']; ?> / <?php echo $stats['total']; ?></p>
                                    <p><strong>Attendance Rate:</strong> 
                                        <span style="color: <?php echo $stats['percentage'] >= 75 ? '#28a745' : ($stats['percentage'] >= 50 ? '#ffc107' : '#dc3545'); ?>; font-weight: bold;">
                                            <?php echo $stats['percentage']; ?>%
                                        </span>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="section">
                <h2>Session Details</h2>
                <?php if (empty($mySessions)): ?>
                    <p class="empty-state">No sessions available yet.</p>
                <?php else: ?>
                    <div class="table-container">
                        <table class="requests-table">
                            <thead>
                                <tr>
                                    <th>Course</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th>Marked At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($mySessions as $session): ?>
                                    <?php 
                                    $attended = isset($attendanceMap[$session['id']]);
                                    $course = $courseMap[$session['course_id']];
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($course['course_name']); ?></td>
                                        <td><?php echo $session['session_date']; ?></td>
                                        <td><?php echo $session['session_time']; ?></td>
                                        <td>
                                            <?php if ($attended): ?>
                                                <span class="status status-approved">Present</span>
                                            <?php else: ?>
                                                <span class="status status-rejected">Absent</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php echo $attended ? $attendanceMap[$session['id']]['marked_at'] : '-'; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
