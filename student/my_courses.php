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

// Get my requests
$myRequests = array_filter($requests, function($req) {
    return $req['student_id'] === $_SESSION['user_id'];
});

// Map course IDs to course names
$courseMap = [];
foreach ($courses as $course) {
    $courseMap[$course['id']] = $course['course_name'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Courses</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <h2>Course Management System</h2>
            <div class="nav-links">
                <a href="dashboard.php">Dashboard</a>
                <a href="../logout.php" class="btn btn-secondary">Logout</a>
            </div>
        </div>
    </nav>
    
    <div class="container">
        <div class="dashboard">
            <h1>My Courses</h1>
            
            <div class="section">
                <h2>Enrolled Courses</h2>
                <?php if (empty($enrolledCourses)): ?>
                    <p class="empty-state">You are not enrolled in any courses yet.</p>
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
            
            <div class="section">
                <h2>My Requests</h2>
                <?php if (empty($myRequests)): ?>
                    <p class="empty-state">No requests yet.</p>
                <?php else: ?>
                    <div class="table-container">
                        <table class="requests-table">
                            <thead>
                                <tr>
                                    <th>Course</th>
                                    <th>Requested At</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($myRequests as $request): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($courseMap[$request['course_id']] ?? 'Unknown'); ?></td>
                                        <td><?php echo htmlspecialchars($request['requested_at']); ?></td>
                                        <td>
                                            <span class="status status-<?php echo $request['status']; ?>">
                                                <?php echo ucfirst($request['status']); ?>
                                            </span>
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

//new