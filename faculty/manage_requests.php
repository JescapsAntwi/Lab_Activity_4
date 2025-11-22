<?php
require_once '../includes/session.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireRole('faculty');

$message = '';

// Handle approve/reject actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $requestId = sanitizeInput($_POST['request_id']);
    $action = sanitizeInput($_POST['action']);
    
    $requests = readJSON('requests.json');
    $enrollments = readJSON('enrollments.json');
    
    foreach ($requests as &$request) {
        if ($request['id'] === $requestId) {
            if ($action === 'approve') {
                $request['status'] = 'approved';
                
                // Add to enrollments
                $enrollments[] = [
                    'id' => generateId(),
                    'student_id' => $request['student_id'],
                    'course_id' => $request['course_id'],
                    'enrolled_at' => date('Y-m-d H:i:s')
                ];
                
                $message = 'Request approved successfully!';
            } elseif ($action === 'reject') {
                $request['status'] = 'rejected';
                $message = 'Request rejected.';
            }
            break;
        }
    }
    
    writeJSON('requests.json', $requests);
    writeJSON('enrollments.json', $enrollments);
}

// Get faculty's courses
$courses = readJSON('courses.json');
$myCourses = array_filter($courses, function($course) {
    return $course['faculty_id'] === $_SESSION['user_id'];
});
$myCourseIds = array_column($myCourses, 'id');

// Get requests for faculty's courses
$allRequests = readJSON('requests.json');
$myRequests = array_filter($allRequests, function($req) use ($myCourseIds) {
    return in_array($req['course_id'], $myCourseIds);
});

// Get user data for student names
$users = readJSON('users.json');
$userMap = [];
foreach ($users as $user) {
    $userMap[$user['id']] = $user['username'];
}

// Get course names
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
    <title>Manage Requests</title>
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
            <h1>Manage Course Requests</h1>
            
            <?php if ($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <?php if (empty($myRequests)): ?>
                <p class="empty-state">No requests yet.</p>
            <?php else: ?>
                <div class="table-container">
                    <table class="requests-table">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Course</th>
                                <th>Requested At</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($myRequests as $request): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($userMap[$request['student_id']] ?? 'Unknown'); ?></td>
                                    <td><?php echo htmlspecialchars($courseMap[$request['course_id']] ?? 'Unknown'); ?></td>
                                    <td><?php echo htmlspecialchars($request['requested_at']); ?></td>
                                    <td>
                                        <span class="status status-<?php echo $request['status']; ?>">
                                            <?php echo ucfirst($request['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($request['status'] === 'pending'): ?>
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                                                <button type="submit" name="action" value="approve" class="btn btn-sm btn-success">Approve</button>
                                                <button type="submit" name="action" value="reject" class="btn btn-sm btn-danger">Reject</button>
                                            </form>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

