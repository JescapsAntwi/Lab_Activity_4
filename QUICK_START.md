# Quick Start Guide - Attendance Management System

## Starting the Application

```bash
# Navigate to project directory
cd lab_4

# Start PHP development server
php -S localhost:8000

# Open in browser
# http://localhost:8000
```

## Testing the Attendance Feature

### Step 1: Create Test Accounts

**Faculty Account:**
- Register at: http://localhost:8000/register.php
- Username: `prof_smith`
- Email: `smith@university.edu`
- Password: `password123`
- Role: **Faculty**

**Student Account:**
- Register at: http://localhost:8000/register.php
- Username: `john_doe`
- Email: `john@student.edu`
- Password: `password123`
- Role: **Student**

### Step 2: Faculty - Create a Course

1. Login as faculty
2. Click "Create New Course"
3. Fill in:
   - Course Name: `Web Technologies`
   - Course Code: `CS101`
   - Description: `Introduction to Web Development`
4. Click "Create Course"

### Step 3: Student - Request to Join Course

1. Login as student
2. Click "Browse Courses"
3. Find "Web Technologies"
4. Click "Request to Join"

### Step 4: Faculty - Approve Request

1. Login as faculty
2. Click "Manage Requests"
3. Find student's request
4. Click "Approve"

### Step 5: Faculty - Create Attendance Session

1. Click "Manage Sessions"
2. Fill in:
   - Course: `Web Technologies`
   - Date: Today's date
   - Time: Current time
   - Duration: `60` minutes
3. Click "Create Session"
4. **Note the 6-digit code** (e.g., 042857)

### Step 6: Student - Mark Attendance

1. Login as student
2. Click "Mark Attendance"
3. Enter the 6-digit code
4. Click "Mark Attendance"
5. See success message

### Step 7: View Reports

**Student:**
1. Click "View Attendance"
2. See course summary with attendance percentage
3. See session details with present/absent status

**Faculty:**
1. Go to "Manage Sessions"
2. Click "View" next to the session
3. See list of students with attendance status
4. See attendance statistics

## Features to Test

### Faculty Features
- ✅ Create multiple sessions for different courses
- ✅ Close a session (toggle status)
- ✅ Reopen a closed session
- ✅ View attendance reports
- ✅ See real-time attendance as students mark

### Student Features
- ✅ Mark attendance with valid code
- ✅ Try marking twice (should fail)
- ✅ Try invalid code (should fail)
- ✅ Try code for course not enrolled in (should fail)
- ✅ View attendance statistics
- ✅ See attendance percentage color coding

## Sample Test Scenarios

### Scenario 1: Normal Attendance Flow
1. Faculty creates session → Gets code `123456`
2. Student enters code `123456` → Success
3. Faculty views attendance → Student shows as "Present"
4. Student views attendance → Shows 100% for that course

### Scenario 2: Duplicate Attendance Prevention
1. Student marks attendance with code `123456` → Success
2. Student tries same code again → Error: "Already marked"

### Scenario 3: Closed Session
1. Faculty creates session with code `789012`
2. Faculty closes the session
3. Student tries code `789012` → Error: "Session is closed"

### Scenario 4: Invalid Code
1. Student enters random code `999999` → Error: "Invalid code"

### Scenario 5: Not Enrolled
1. Faculty creates session for Course A
2. Student (not enrolled in Course A) tries code → Error: "Not enrolled"

## Troubleshooting

### Issue: "Call to undefined function"
**Solution:** Make sure all files are properly included
```php
require_once '../includes/session.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';
```

### Issue: JSON files not found
**Solution:** Visit any page once to trigger `config/init.php` which creates the files

### Issue: Permission denied on data files
**Solution:** 
```bash
chmod 755 data/
chmod 644 data/*.json
```

### Issue: Session not starting
**Solution:** Check PHP session configuration
```bash
php -i | grep session
```

## Color Scheme
- **Primary (Cyan)**: #00bcd4
- **Secondary (Black)**: #000
- **Background**: White with cyan gradient
- **Success**: Green (#28a745)
- **Warning**: Yellow (#ffc107)
- **Danger**: Red (#dc3545)

## File Permissions
```bash
# Set correct permissions
chmod 755 faculty/ student/ includes/ config/ assets/
chmod 644 *.php faculty/*.php student/*.php
chmod 755 data/
chmod 666 data/*.json
```

## Next Steps
1. Test all features thoroughly
2. Create demo video showing attendance flow
3. Deploy to hosting service
4. Update GitHub repository
5. Submit project URL and GitHub link

## Support
For issues or questions, refer to:
- `README.md` - General documentation
- `ATTENDANCE_FEATURES.md` - Detailed feature documentation
- Check PHP error logs: `tail -f /var/log/php_errors.log`
