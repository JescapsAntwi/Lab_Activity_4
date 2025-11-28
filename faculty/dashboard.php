<?php
require_once '../includes/session.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireRole('faculty');

$courses = readJSON('courses.json');
$requests = readJSON('requests.json');

// Filter courses created by this faculty
$myCourses = array_filter($courses, function($course) {
    return $course['faculty_id'] === $_SESSION['user_id'];
});

// Count pending requests for faculty's courses
$myCourseIds = array_column($myCourses, 'id');
$pendingRequests = array_filter($requests, function($req) use ($myCourseIds) {
    return in_array($req['course_id'], $myCourseIds) && $req['status'] === 'pending';
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <h2>Course Management System</h2>
            <div class="nav-links">
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> (Faculty)</span>
                <a href="../logout.php" class="btn btn-secondary">Logout</a>
            </div>
        </div>
    </nav>
    
    <div class="container">
        <div class="dashboard">
            <h1>Faculty Dashboard</h1>
            
            <div class="stats">
                <div class="stat-card">
                    <h3><?php echo count($myCourses); ?></h3>
                    <p>My Courses</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo count($pendingRequests); ?></h3>
                    <p>Pending Requests</p>
                </div>
            </div>
            
            <div class="actions">
                <a href="create_course.php" class="btn btn-primary">Create New Course</a>
                <a href="manage_requests.php" class="btn btn-secondary">Manage Requests</a>
                <a href="manage_sessions.php" class="btn btn-primary">Manage Sessions</a>
            </div>
            
            <div class="section">
                <h2>My Courses</h2>
                <?php if (empty($myCourses)): ?>
                    <p class="empty-state">You haven't created any courses yet.</p>
                <?php else: ?>
                    <div class="course-grid">
                        <?php foreach ($myCourses as $course): ?>
                            <div class="course-card">
                                <h3><?php echo htmlspecialchars($course['course_name']); ?></h3>
                                <p class="course-code"><?php echo htmlspecialchars($course['course_code']); ?></p>
                                <p><?php echo htmlspecialchars($course['description']); ?></p>
                                <small>Created: <?php echo htmlspecialchars($course['created_at']); ?></small>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>

