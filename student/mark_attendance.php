<?php
require_once '../includes/session.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireRole('student');

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = sanitizeInput($_POST['code']);
    
    if (empty($code)) {
        $error = 'Please enter attendance code';
    } else {
        $sessions = readJSON('sessions.json');
        $attendance = readJSON('attendance.json');
        $enrollments = readJSON('enrollments.json');
        
        // Find session by code
        $session = null;
        foreach ($sessions as $s) {
            if ($s['code'] === $code) {
                $session = $s;
                break;
            }
        }
        
        if (!$session) {
            $error = 'Invalid attendance code';
        } elseif ($session['status'] !== 'active') {
            $error = 'This session is closed';
        } else {
            // Check if student is enrolled in the course
            $isEnrolled = false;
            foreach ($enrollments as $enrollment) {
                if ($enrollment['student_id'] === $_SESSION['user_id'] && 
                    $enrollment['course_id'] === $session['course_id']) {
                    $isEnrolled = true;
                    break;
                }
            }
            
            if (!$isEnrolled) {
                $error = 'You are not enrolled in this course';
            } else {
                // Check if already marked
                $alreadyMarked = false;
                foreach ($attendance as $att) {
                    if ($att['session_id'] === $session['id'] && 
                        $att['student_id'] === $_SESSION['user_id']) {
                        $alreadyMarked = true;
                        break;
                    }
                }
                
                if ($alreadyMarked) {
                    $error = 'You have already marked attendance for this session';
                } else {
                    // Mark attendance
                    $newAttendance = [
                        'id' => generateId(),
                        'session_id' => $session['id'],
                        'course_id' => $session['course_id'],
                        'student_id' => $_SESSION['user_id'],
                        'marked_at' => date('Y-m-d H:i:s')
                    ];
                    
                    $attendance[] = $newAttendance;
                    writeJSON('attendance.json', $attendance);
                    
                    $message = 'Attendance marked successfully!';
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Attendance</title>
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
                <a href="../logout.php" class="btn btn-secondary">Logout</a>
            </div>
        </div>
    </nav>
    
    <div class="container">
        <div class="form-container">
            <h1>Mark Attendance</h1>
            
            <?php if ($message): ?>
                <div class="alert alert-success">
                    <?php echo $message; ?>
                    <a href="view_attendance.php">View My Attendance</a>
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="code">Enter Attendance Code</label>
                    <input type="text" id="code" name="code" placeholder="Enter 6-digit code" maxlength="6" required autofocus>
                </div>
                
                <button type="submit" class="btn btn-primary">Mark Attendance</button>
            </form>
            
            <p class="text-center">
                <a href="view_attendance.php">View My Attendance Records</a>
            </p>
        </div>
    </div>
</body>
</html>
