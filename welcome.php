<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Ashesi University CMS</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .welcome-hero {
            background: linear-gradient(rgba(0, 188, 212, 0.75), rgba(0, 96, 100, 0.85)), 
                        url('assets/images/ashesi_image.jpeg') center/cover;
            min-height: 70vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            padding: 40px 20px;
        }
        
        .welcome-hero h1 {
            font-size: 3.5rem;
            margin-bottom: 20px;
            text-shadow: 3px 3px 6px rgba(0,0,0,0.4);
            animation: fadeInDown 1s ease-in;
        }
        
        .welcome-hero .subtitle {
            font-size: 1.5rem;
            margin-bottom: 40px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            animation: fadeInUp 1s ease-in;
        }
        
        .welcome-actions {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: center;
            animation: fadeIn 1.5s ease-in;
        }
        
        .welcome-actions .btn {
            padding: 15px 40px;
            font-size: 1.1rem;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .features-section {
            max-width: 1200px;
            margin: 60px auto;
            padding: 0 20px;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }
        
        .feature-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
        }
        
        .feature-icon {
            font-size: 3rem;
            margin-bottom: 15px;
        }
        
        .feature-card h3 {
            color: #00bcd4;
            margin-bottom: 15px;
        }
        
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        
        .ashesi-badge {
            background: white;
            padding: 10px 20px;
            border-radius: 50px;
            display: inline-block;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        
        .ashesi-badge span {
            color: #00bcd4;
            font-weight: bold;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <div class="welcome-hero">
        <div class="ashesi-badge">
            <span>🎓 ASHESI UNIVERSITY</span>
        </div>
        <h1>Course Management System</h1>
        <p class="subtitle">Streamlined Attendance & Course Management</p>
        <div class="welcome-actions">
            <a href="login.php" class="btn btn-primary">Login</a>
            <a href="register.php" class="btn btn-secondary">Register</a>
        </div>
    </div>
    
    <div class="features-section">
        <h2 style="text-align: center; color: #00bcd4; font-size: 2.5rem; margin-bottom: 20px;">System Features</h2>
        <p style="text-align: center; color: #666; font-size: 1.2rem; margin-bottom: 40px;">
            A comprehensive platform for managing courses and tracking attendance
        </p>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">👨‍🏫</div>
                <h3>For Faculty</h3>
                <p>Create courses, manage enrollment requests, and track student attendance with ease.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">👨‍🎓</div>
                <h3>For Students</h3>
                <p>Browse courses, request enrollment, and mark attendance using unique session codes.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">📊</div>
                <h3>Analytics</h3>
                <p>View detailed attendance reports and statistics for better academic tracking.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">🔐</div>
                <h3>Secure</h3>
                <p>Role-based access control with encrypted passwords and session management.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">⚡</div>
                <h3>Real-time</h3>
                <p>Instant attendance marking and live updates for faculty and students.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">📱</div>
                <h3>Responsive</h3>
                <p>Access from any device - desktop, tablet, or mobile phone.</p>
            </div>
        </div>
    </div>
    
    <div class="ashesi-footer">
        <p style="font-size: 1.1rem; color: #00bcd4; font-weight: bold;">🎓 Ashesi University</p>
        <p>Excellence in Education | Innovation in Learning</p>
        <p style="margin-top: 10px; font-size: 0.9rem;">© 2024 Ashesi University Course Management System</p>
    </div>
</body>
</html>
