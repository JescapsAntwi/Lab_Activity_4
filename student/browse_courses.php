<?php
require_once '../includes/session.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireRole('student');

$message = '';

// Handle course request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['course_id'])) {
    $courseId = sanitizeInput($_POST['course_id']);
    
    $requests = readJSON('requests.json');
    $enrollments = readJSON('enrollments.json');
    
    // Check if already enrolled
    $alreadyEnrolled = false;
    foreach ($enrollments as $enrollment) {
        if ($enrollment['student_id'] === $_SESSION['user_id'] && $enrollment['course_id'] === $courseId) {
            $alreadyEnrolled = true;
            break;
        }
    }
    
    // Check if already requested
    $alreadyRequested = false;
    foreach ($requests as $request) {
        if ($request['student_id'] === $_SESSION['user_id'] && 
            $request['course_id'] === $courseId && 
            $request['status'] === 'pending') {
            $alreadyRequested = true;
            break;
        }
    }
    
    if ($alreadyEnrolled) {
        $message = 'You are already enrolled in this course.';
    } elseif ($alreadyRequested) {
        $message = 'You have already requested to join this course.';
    } else {
        $newRequest = [
            'id' => generateId(),
            'student_id' => $_SESSION['user_id'],
            'course_id' => $courseId,
            'status' => 'pending',
            'requested_at' => date('Y-m-d H:i:s')
        ];
        
        $requests[] = $newRequest;
        writeJSON('requests.json', $requests);
        
        $message = 'Request sent successfully!';
    }
}

$courses = readJSON('courses.json');
$enrollments = readJSON('enrollments.json');
$requests = readJSON('requests.json');

// Get enrolled course IDs
$enrolledCourseIds = [];
foreach ($enrollments as $enrollment) {
    if ($enrollment['student_id'] === $_SESSION['user_id']) {
        $enrolledCourseIds[] = $enrollment['course_id'];
    }
}

// Get requested course IDs
$requestedCourseIds = [];
foreach ($requests as $request) {
    if ($request['student_id'] === $_SESSION['user_id'] && $request['status'] === 'pending') {
        $requestedCourseIds[] = $request['course_id'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Courses</title>
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
            <h1>Browse Courses</h1>
            
            <?php if ($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <?php if (empty($courses)): ?>
                <p class="empty-state">No courses available yet.</p>
            <?php else: ?>
                <div class="course-grid">
                    <?php foreach ($courses as $course): ?>
                        <div class="course-card">
                            <h3><?php echo htmlspecialchars($course['course_name']); ?></h3>
                            <p class="course-code"><?php echo htmlspecialchars($course['course_code']); ?></p>
                            <p><?php echo htmlspecialchars($course['description']); ?></p>
                            <small>Faculty: <?php echo htmlspecialchars($course['faculty_name']); ?></small>
                            
                            <?php if (in_array($course['id'], $enrolledCourseIds)): ?>
                                <button class="btn btn-disabled" disabled>Enrolled</button>
                            <?php elseif (in_array($course['id'], $requestedCourseIds)): ?>
                                <button class="btn btn-disabled" disabled>Request Pending</button>
                            <?php else: ?>
                                <form method="POST" style="margin-top: 10px;">
                                    <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                                    <button type="submit" class="btn btn-primary">Request to Join</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

