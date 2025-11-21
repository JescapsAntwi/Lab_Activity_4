<?php
require_once '../includes/session.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireRole('faculty');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $courseName = sanitizeInput($_POST['course_name']);
    $courseCode = sanitizeInput($_POST['course_code']);
    $description = sanitizeInput($_POST['description']);
    
    if (empty($courseName) || empty($courseCode) || empty($description)) {
        $error = 'All fields are required';
    } else {
        $courses = readJSON('courses.json');
        
        // Check if course code already exists
        $codeExists = false;
        foreach ($courses as $course) {
            if ($course['course_code'] === $courseCode) {
                $codeExists = true;
                break;
            }
        }
        
        if ($codeExists) {
            $error = 'Course code already exists';
        } else {
            $newCourse = [
                'id' => generateId(),
                'faculty_id' => $_SESSION['user_id'],
                'faculty_name' => $_SESSION['username'],
                'course_name' => $courseName,
                'course_code' => $courseCode,
                'description' => $description,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $courses[] = $newCourse;
            writeJSON('courses.json', $courses);
            
            $success = 'Course created successfully!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Course</title>
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
        <div class="form-container">
            <h1>Create New Course</h1>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?php echo $success; ?>
                    <a href="dashboard.php">Back to Dashboard</a>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="course_name">Course Name</label>
                    <input type="text" id="course_name" name="course_name" required>
                </div>
                
                <div class="form-group">
                    <label for="course_code">Course Code</label>
                    <input type="text" id="course_code" name="course_code" required placeholder="e.g., CS101">
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="4" required></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Create Course</button>
                    <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
