<?php
require_once '../includes/session.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireRole('student');

$enrollments = readJSON('enrollments.json');
$courses = readJSON('courses.json');
$requests = readJSON('requests.json');

// Get enrolled courses
$myEnrollments = array_filter($enrollments, function($enrollment) {
    return $enrollment['student_id'] === $_SESSION['user_id'];
});

$enrolledCourseIds = array_column($myEnrollments, 'course_id');
$enrolledCourses = array_filter($courses, function($course) use ($enrolledCourseIds) {
    return in_array($course['id'], $enrolledCourseIds);
});

// Get pending requests
$myRequests = array_filter($requests, function($req) {
    return $req['student_id'] === $_SESSION['user_id'] && $req['status'] === 'pending';
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-brand">
                <h2>🎓 Ashesi CMS</h2>
            </div>
            <div class="nav-links">
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> (Student)</span>
                <a href="../logout.php" class="btn btn-secondary">Logout</a>
            </div>
        </div>
    </nav>
    
    <div class="container">
        <div class="dashboard">
            <h1>Student Dashboard</h1>
            
            <div class="stats">
                <div class="stat-card">
                    <h3><?php echo count($enrolledCourses); ?></h3>
                    <p>Enrolled Courses</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo count($myRequests); ?></h3>
                    <p>Pending Requests</p>
                </div>
            </div>
            
            <div class="actions">
                <a href="browse_courses.php" class="btn btn-primary">Browse Courses</a>
                <a href="my_courses.php" class="btn btn-secondary">My Courses</a>
                <a href="mark_attendance.php" class="btn btn-primary">Mark Attendance</a>
                <a href="view_attendance.php" class="btn btn-secondary">View Attendance</a>
            </div>
            
            <div class="section">
                <h2>My Enrolled Courses</h2>
                <?php if (empty($enrolledCourses)): ?>
                    <p class="empty-state">You are not enrolled in any courses yet. <a href="browse_courses.php">Browse courses</a> to get started.</p>
                <?php else: ?>
                    <div class="course-grid">
                        <?php foreach ($enrolledCourses as $course): ?>
                            <div class="course-card">
                                <h3><?php echo htmlspecialchars($course['course_name']); ?></h3>
                                <p class="course-code"><?php echo htmlspecialchars($course['course_code']); ?></p>
                                <p><?php echo htmlspecialchars($course['description']); ?></p>
                                <small>Faculty: <?php echo htmlspecialchars($course['faculty_name']); ?></small>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="ashesi-footer">
            <p>🎓 Ashesi University Course Management System</p>
            <p style="font-size: 0.9rem; margin-top: 5px;">Excellence in Education</p>
        </div>
    </div>
</body>
</html>

