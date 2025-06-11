<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['fullName'];
    $email = $_POST['email'];
    $test = $_POST['testName'];
    $date = $_POST['appointmentDate'];
    $msg = $_POST['message'];

    $sql = "INSERT INTO appointments (name, email, test, appointment_date, message) 
            VALUES ('$name', '$email', '$test', '$date', '$msg')";

    if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Appointment booked successfully!');window.location.href='dashboard.php';</script>";
    exit(); // Stop script after redirect
} else {
    echo "Error: " . $conn->error; // Correct way to show the DB error
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Book a Test - SWEM</title>
  <link rel="stylesheet" href="style.css">
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
    <img src="newlogo.png" alt="SWEM Logo" class="logo" />
    <h1 class="header-text"> Book Your Test</h1>
  </header>

  <!-- Navigation -->
  <header class="navbar">
    <nav class="nav-links">
      <a href="index.php">Home</a>
      <a href="about.php">About</a>
      <a href="locations.php">Locations</a>
      <a href="contactus.php">Contact Us</a>
      
    </nav>
  </header>

  <div class="page-container">
    <main class="main-content">
      <div class="form-wrapper">
        <form id="testForm" method="POST" action="book-test.php">
          <div class="form-field">
            <label>Full Name</label>
            <input type="text" name="fullName" required>
          </div>

          <div class="form-field">
            <label>Email Address</label>
            <input type="email" name="email" required>
          </div>

          <div class="form-field">
            <label>Test Required</label>
            <input type="text" name="testName" placeholder="e.g., Blood Test" required>
          </div>

          <div class="form-field">
            <label>Appointment Date</label>
            <input type="date" name="appointmentDate" required>
          </div>

          <div class="form-field">
            <label>Additional Information</label>
            <textarea name="message"></textarea>
          </div>

          <button type="submit" name="submit">Book Appointment</button>
        </form>
      </div>
    </main>
  </div>

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
        <a href="https://facebook.com" target="abc@hotmail.com" class="social-icon">
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
