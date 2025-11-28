<?php
require_once '../includes/session.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireRole('faculty');

$message = '';
$error = '';

// Handle session creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_session'])) {
    $courseId = sanitizeInput($_POST['course_id']);
    $sessionDate = sanitizeInput($_POST['session_date']);
    $sessionTime = sanitizeInput($_POST['session_time']);
    $duration = (int)$_POST['duration'];
    
    if (empty($courseId) || empty($sessionDate) || empty($sessionTime)) {
        $error = 'All fields are required';
    } else {
        $sessions = readJSON('sessions.json');
        
        // Generate unique 6-digit code
        $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        
        $newSession = [
            'id' => generateId(),
            'course_id' => $courseId,
            'faculty_id' => $_SESSION['user_id'],
            'session_date' => $sessionDate,
            'session_time' => $sessionTime,
            'duration' => $duration,
            'code' => $code,
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $sessions[] = $newSession;
        writeJSON('sessions.json', $sessions);
        
        $message = "Session created successfully! Code: $code";
    }
}

// Handle session status toggle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_status'])) {
    $sessionId = sanitizeInput($_POST['session_id']);
    $sessions = readJSON('sessions.json');
    
    foreach ($sessions as &$session) {
        if ($session['id'] === $sessionId && $session['faculty_id'] === $_SESSION['user_id']) {
            $session['status'] = $session['status'] === 'active' ? 'closed' : 'active';
            $message = 'Session status updated';
            break;
        }
    }
    
    writeJSON('sessions.json', $sessions);
}

// Get faculty's courses
$courses = readJSON('courses.json');
$myCourses = array_filter($courses, function($course) {
    return $course['faculty_id'] === $_SESSION['user_id'];
});

// Get all sessions for faculty's courses
$allSessions = readJSON('sessions.json');
$myCourseIds = array_column($myCourses, 'id');
$mySessions = array_filter($allSessions, function($session) use ($myCourseIds) {
    return in_array($session['course_id'], $myCourseIds);
});

// Sort by date descending
usort($mySessions, function($a, $b) {
    return strtotime($b['session_date'] . ' ' . $b['session_time']) - strtotime($a['session_date'] . ' ' . $a['session_time']);
});

// Map course names
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
    <title>Manage Sessions</title>
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
            <h1>Manage Class Sessions</h1>
            
            <?php if ($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <div class="form-container" style="max-width: 100%;">
                <h2>Create New Session</h2>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="course_id">Select Course</label>
                        <select id="course_id" name="course_id" required>
                            <option value="">Choose a course</option>
                            <?php foreach ($myCourses as $course): ?>
                                <option value="<?php echo $course['id']; ?>">
                                    <?php echo htmlspecialchars($course['course_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="session_date">Session Date</label>
                        <input type="date" id="session_date" name="session_date" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="session_time">Session Time</label>
                        <input type="time" id="session_time" name="session_time" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="duration">Duration (minutes)</label>
                        <input type="number" id="duration" name="duration" value="60" min="15" max="300" required>
                    </div>
                    
                    <button type="submit" name="create_session" class="btn btn-primary">Create Session</button>
                </form>
            </div>
            
            <div class="section">
                <h2>All Sessions</h2>
                <?php if (empty($mySessions)): ?>
                    <p class="empty-state">No sessions created yet.</p>
                <?php else: ?>
                    <div class="table-container">
                        <table class="requests-table">
                            <thead>
                                <tr>
                                    <th>Course</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Duration</th>
                                    <th>Code</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($mySessions as $session): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($courseMap[$session['course_id']] ?? 'Unknown'); ?></td>
                                        <td><?php echo htmlspecialchars($session['session_date']); ?></td>
                                        <td><?php echo htmlspecialchars($session['session_time']); ?></td>
                                        <td><?php echo $session['duration']; ?> min</td>
                                        <td><strong><?php echo $session['code']; ?></strong></td>
                                        <td>
                                            <span class="status status-<?php echo $session['status']; ?>">
                                                <?php echo ucfirst($session['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="session_id" value="<?php echo $session['id']; ?>">
                                                <button type="submit" name="toggle_status" class="btn btn-sm btn-secondary">
                                                    <?php echo $session['status'] === 'active' ? 'Close' : 'Reopen'; ?>
                                                </button>
                                            </form>
                                            <a href="view_attendance.php?session_id=<?php echo $session['id']; ?>" class="btn btn-sm btn-primary">View</a>
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
