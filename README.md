# Course Management System

A PHP-based course management system with role-based access control for Faculty and Students. This is my submission for Web Tech lab 4.

## Features

### User Authentication
- Secure registration and login system
- Password hashing using PHP's `password_hash()`
- Session-based authentication
- Role-based access control (Faculty/Student)

### Faculty Features
- Create new courses
- View all created courses
- Manage student requests to join courses
- Approve or reject enrollment requests

### Student Features
- Browse available courses
- Request to join courses
- View enrolled courses
- Track request status (pending/approved/rejected)

### Security
- Password hashing with bcrypt
- Input validation and sanitization
- XSS protection using `htmlspecialchars()`
- Session management
- File locking for JSON data integrity

## Installation

1. Clone the repository
2. Ensure PHP 7.4+ is installed
3. Start a local PHP server:
   ```bash
   php -S localhost:8000
   ```
4. Open your browser and navigate to `http://localhost:8000`

## File Structure

```
/
├── config/
│   └── init.php              # Initialize data files
├── includes/
│   ├── auth.php              # Authentication functions
│   ├── functions.php         # Helper functions
│   └── session.php           # Session management
├── data/                     # JSON storage (auto-created)
│   ├── users.json
│   ├── courses.json
│   ├── requests.json
│   └── enrollments.json
├── assets/
│   ├── css/
│   │   └── style.css
│   └── js/
│       └── validation.js
├── faculty/
│   ├── dashboard.php
│   ├── create_course.php
│   └── manage_requests.php
├── student/
│   ├── dashboard.php
│   ├── browse_courses.php
│   └── my_courses.php
├── index.php
├── register.php
├── login.php
├── logout.php
└── dashboard.php
```

## Usage

### Registration
1. Navigate to the registration page
2. Fill in username, email, password
3. Select role (Student or Faculty)
4. Submit the form

### Login
1. Enter your registered email and password
2. You'll be redirected to your role-specific dashboard

### Faculty Workflow
1. Create courses from the dashboard
2. Students can request to join your courses
3. Manage requests from the "Manage Requests" page
4. Approve or reject student enrollment requests

### Student Workflow
1. Browse available courses
2. Request to join courses
3. View enrolled courses and request status
4. Access course materials once approved

## Data Storage

This system uses JSON files for data persistence:
- `users.json` - User accounts
- `courses.json` - Course information
- `requests.json` - Enrollment requests
- `enrollments.json` - Approved enrollments

## Technologies Used

- PHP 7.4+
- HTML5
- CSS3
- Vanilla JavaScript
- JSON file storage

## Security Notes

- Passwords are hashed using PHP's `password_hash()` with bcrypt
- All user inputs are sanitized and validated
- Session-based authentication protects routes
- File locking prevents data corruption during concurrent writes

##
