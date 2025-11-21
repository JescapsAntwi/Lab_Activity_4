<?php
require_once __DIR__ . '/functions.php';

function registerUser($username, $email, $password, $role) {
    $users = readJSON('users.json');
    
    // Check if user already exists
    foreach ($users as $user) {
        if ($user['email'] === $email) {
            return ['success' => false, 'message' => 'Email already registered'];
        }
        if ($user['username'] === $username) {
            return ['success' => false, 'message' => 'Username already taken'];
        }
    }
    
    // Create new user
    $newUser = [
        'id' => generateId(),
        'username' => $username,
        'email' => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'role' => $role,
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    $users[] = $newUser;
    writeJSON('users.json', $users);
    
    return ['success' => true, 'message' => 'Registration successful'];
}

function loginUser($email, $password) {
    $users = readJSON('users.json');
    
    foreach ($users as $user) {
        if ($user['email'] === $email) {
            if (password_verify($password, $user['password'])) {
                return ['success' => true, 'user' => $user];
            } else {
                return ['success' => false, 'message' => 'Invalid password'];
            }
        }
    }
    
    return ['success' => false, 'message' => 'User not found'];
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /login.php');
        exit;
    }
}

function requireRole($role) {
    requireLogin();
    if ($_SESSION['role'] !== $role) {
        header('Location: /dashboard.php');
        exit;
    }
}
