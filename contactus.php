<?php
session_start();
require 'db.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name    = $conn->real_escape_string($_POST['name']);
    $email   = $conn->real_escape_string($_POST['email']);
    $subject = $conn->real_escape_string($_POST['subject']);
    $message = $conn->real_escape_string($_POST['message']);

    $sql = "INSERT INTO contact_messages (name, email, subject, message)
            VALUES ('$name', '$email', '$subject', '$message')";

    if ($conn->query($sql) === TRUE) {
        $success = "Message sent successfully!";
    } else {
        $success = "Error: " . $conn->error;
    }
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
    </nav>
  </header>


  <section class="contact-section">
    <h1>Contact Us</h1>
    <p>If you have any questions, feel free to reach out to us. We’re here to help!</p>

    <div class="contact-container">
      <form class="contact-form" method="POST" action="">
        <input type="text" name="name" placeholder="Your Name" required>
        <input type="email" name="email" placeholder="Your Email" required>
        <input type="text" name="subject" placeholder="Subject" required>
        <textarea name="message" placeholder="Your Message" rows="6" required></textarea>
        <button type="submit">Send Message</button>
      </form>

      <div class="contact-info">
        <h2>Our Office</h2>
        <p><strong>Address:</strong> Clifton, Karachi, Pakistan</p>
        <p><strong>Phone:</strong> +92 123 4567890</p>
        <p><strong>Email:</strong> info@swemlabs.com</p>
        <p><strong>Working Hours:</strong> Mon - Sat | 9 AM - 7 PM</p>
      </div>
    </div>
  </section>

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
