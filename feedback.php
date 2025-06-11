<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $subject = $conn->real_escape_string($_POST['subject']);
    $message = $conn->real_escape_string($_POST['message']);
    $rating = $conn->real_escape_string($_POST['rating']);
    
    $stmt = $conn->prepare("INSERT INTO feedback (name, email, subject, message, rating, submission_date) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssssi", $name, $email, $subject, $message, $rating);
    
    if ($stmt->execute()) {
        echo "<script>alert('Thank you for your feedback!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Error submitting feedback. Please try again.');</script>";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback - SWEM</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .feedback-form {
            max-width: 600px;
            margin: 2rem auto;
            padding: 2rem;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .rating {
            display: flex;
            gap: 1rem;
            margin: 1rem 0;
        }
        .rating input {
            display: none;
        }
        .rating label {
            cursor: pointer;
            font-size: 1.5rem;
            color: #ddd;
            transition: color 0.2s;
        }
        .rating label:hover,
        .rating label:hover ~ label {
            color: #ffd700;
        }
        .rating input:checked ~ label {
            color: #ddd;
        }
        .rating input:checked + label,
        .rating input:checked + label ~ label {
            color: #ffd700;
        }
    </style>
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
            <a href="feedback.php">Feedback</a>
        </nav>
    </header>

    <main class="main-container">
        <div class="feedback-form">
            <h2>Share Your Feedback</h2>
            <form method="POST" action="feedback.php">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" id="subject" name="subject" required>
                </div>
                <div class="form-group">
                    <label>Rating</label>
                    <div class="rating">
                        <input type="radio" id="star5" name="rating" value="5" required>
                        <label for="star5">★</label>
                        <input type="radio" id="star4" name="rating" value="4">
                        <label for="star4">★</label>
                        <input type="radio" id="star3" name="rating" value="3">
                        <label for="star3">★</label>
                        <input type="radio" id="star2" name="rating" value="2">
                        <label for="star2">★</label>
                        <input type="radio" id="star1" name="rating" value="1">
                        <label for="star1">★</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="5" required></textarea>
                </div>
                <button type="submit">Submit Feedback</button>
            </form>
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
        </div>
    </footer>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ratingInputs = document.querySelectorAll('.rating input');
            const ratingLabels = document.querySelectorAll('.rating label');

            ratingInputs.forEach((input, index) => {
                input.addEventListener('change', function() {
                    // Reset all stars to default color
                    ratingLabels.forEach(label => {
                        label.style.color = '#ddd';
                    });

                    // Color stars up to the selected rating
                    for (let i = 0; i <= index; i++) {
                        ratingLabels[i].style.color = '#ffd700';
                    }
                });
            });
        });
    </script>
</body>
</html> 