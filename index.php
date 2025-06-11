<?php
session_start();
require 'db.php';

// Handle Login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userid = $conn->real_escape_string($_POST['userID']);
    $pass = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->bind_param("s", $userid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($pass, $row['password'])) {
            $_SESSION['firstName'] = $row['first_name'];
            $_SESSION['lastName'] = $row['last_name'];
            $_SESSION['userID'] = $row['user_id'];
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<script>alert('Incorrect password.');</script>";
        }
    } else {
        echo "<script>alert('User not found.');</script>";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Reports Login - SWEM</title>
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

  <!-- Navigation -->
  <header class="navbar">
    <nav class="nav-links">
      <a href="index.php">Home</a>
      <a href="about.php">About</a>
      <a href="locations.php">Locations</a>
      <a href="contactus.php">Contact Us</a>
      <a href="feedback.php">Feedback</a>
    </nav>
  </header>

  <!-- Main Content -->
  <main class="main-container">
    <div class="left-section">
      <img src="cover pic.png" alt="Cover pic" class="cover-image" />
      <p class="lab-info">
        The SWEM Clinical Laboratories successfully meet over 3500 international standards and have been awarded the Gold Standard Accreditation by the College of American Pathologists (CAP).
      </p>
    </div>

    <div class="auth-section">
      <div class="login-section">
        <h2>Reports Login</h2>
        <form method="POST" action="index.php">
          <div class="form-group">
            <label for="userID">User ID</label>
            <input type="text" id="userID" name="userID" required />
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required />
          </div>
          <button type="submit">LOG IN</button>
        </form>
        <p class="auth-separator">Don't have an account? <a href="register.php" class="register-link">Register here</a></p>
      </div>
    </div>
  </main>

  <!-- Footer -->
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
