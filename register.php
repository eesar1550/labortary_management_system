<?php
session_start();
require 'db.php';

// Handle Registration
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first = $conn->real_escape_string($_POST['firstName']);
    $last = $conn->real_escape_string($_POST['lastName']);
    $userid = $conn->real_escape_string($_POST['userID']);
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $checkUser = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($checkUser);
    $stmt->bind_param("s", $userid);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo "<script>alert('User ID already exists!');</script>";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, user_id, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $first, $last, $userid, $pass);
        
        if ($stmt->execute()) {
            echo "<script>alert('Registration successful! Please log in.'); window.location.href = 'index.php';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Register - SWEM</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <div class="top-bar">
        <ul class="top-links">
            <li><a href="about.php">About Us</a></li>
            <li><a href="locations.php">Locations</a></li>
            <li><a href="contactus.php">Contact Us</a></li>
        </ul>
    </div>

    <header class="header">
        <img src="logo.png" alt="SWEM Logo" class="logo" />
    </header>

    <header class="navbar">
        <nav class="nav-links">
            <a href="index.php">Home</a>
            <a href="about.php">About</a>
            <a href="locations.php">Locations</a>
            <a href="contactus.php">Contact Us</a>
        </nav>
    </header>

    <main class="main-container">
        <div class="auth-section">
            <div class="register-section">
                <h2>New User Registration</h2>
                <form method="POST" action="register.php">
                    <div class="form-group">
                        <label for="firstName">First Name</label>
                        <input type="text" id="firstName" name="firstName" required />
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name</label>
                        <input type="text" id="lastName" name="lastName" required />
                    </div>
                    <div class="form-group">
                        <label for="userID">User ID</label>
                        <input type="text" id="userID" name="userID" required />
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required />
                    </div>
                    <button type="submit">REGISTER</button>
                </form>
                <p class="auth-separator">Already have an account? <a href="index.php">Login here</a></p>
            </div>
        </div>
    </main>

    <footer class="footer">
        <div class="footer-container">
            <div class="footer-left">
                <img src="logo.png" alt="SWEM Logo" class="footer-logo">
                <p>&copy; 2025 SWEM Laboratories. All rights reserved.</p>
            </div>
            <div class="footer-right">
                <ul class="footer-links">
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="contactus.php">Contact</a></li>
                    <li><a href="locations.php">Locations</a></li>
                    <li><a href="index.php">Home</a></li>
                </ul>
            </div>
            <div class="footer-right">
                <a href="https://facebook.com" target="_blank" class="social-icon">
                    <img src="facebook.svg" alt="Facebook">
                </a>
                <a href="https://instagram.com" target="_blank" class="social-icon">
                    <img src="instagram.svg" alt="Instagram">
                </a>
                <a href="https://linkedin.com" target="_blank" class="social-icon">
                    <img src="linkedin.svg" alt="LinkedIn">
                </a>
            </div>
        </div>
    </footer>
</body>
</html> 