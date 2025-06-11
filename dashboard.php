<?php
session_start();
require 'db.php';

// If the user is not logged in, redirect to login page
if (!isset($_SESSION['firstName'])) {
    header("Location: index.php");
    exit();
}

// Fetch user's reports
$stmt = $conn->prepare("SELECT * FROM reports WHERE user_id = ? ORDER BY report_date DESC");
$stmt->bind_param("s", $_SESSION['userID']);
$stmt->execute();
$reports = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SWEM Dashboard</title>
  <link rel="stylesheet" href="dashboard.css">
</head>
<body>

  <header class="dashboard-header">
    <img src="logo.png" alt="SWEM Logo" class="dashboard-logo">
    <button onclick="logoutUser()" class="logout-button">Logout</button>
  </header>

  <main class="dashboard-main">
    <section class="welcome-section">
      <h2>Welcome, <?php echo htmlspecialchars($_SESSION['firstName']); ?>!</h2>
    </section>

    <section class="profile-card">
      <h3>Profile</h3>
      <p><strong>Name:</strong> <?php echo htmlspecialchars($_SESSION['firstName'] . " " . $_SESSION['lastName']); ?></p>
      <p><strong>User ID:</strong> <?php echo htmlspecialchars($_SESSION['userID']); ?></p>
      <button class="edit-profile-btn">Edit Profile</button>
    </section>

    <section class="reports-section">
      <h3>Your Recent Reports</h3>
      <table class="reports-table">
        <tr>
          <th>Report ID</th>
          <th>Date</th>
          <th>Status</th>
          <th>Type</th>
          <th>Action</th>
        </tr>
        <?php if ($reports->num_rows > 0): ?>
          <?php while($report = $reports->fetch_assoc()): ?>
            <tr>
              <td><?php echo htmlspecialchars($report['report_id']); ?></td>
              <td><?php echo htmlspecialchars($report['report_date']); ?></td>
              <td>
                <?php 
                  $status = htmlspecialchars($report['status']);
                  $statusColor = ($status === 'Completed') ? '#28a745' : '#ffc107';
                  echo "<span style='color: $statusColor; font-weight: bold;'>$status</span>";
                ?>
              </td>
              <td><?php echo htmlspecialchars($report['report_type']); ?></td>
              <td>
                <?php if ($report['status'] === 'Completed'): ?>
                  <button onclick="viewReport('<?php echo htmlspecialchars($report['report_id']); ?>')" class="view-btn">View</button>
                <?php else: ?>
                  <span class="status-badge" style="background-color: #ffc107; color: #000; padding: 5px 10px; border-radius: 4px; font-size: 0.9em;">Pending</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td><?php echo htmlspecialchars($_SESSION['userID']); ?></td>
            <td><?php echo date('Y-m-d'); ?></td>
            <td><span style="color: #ffc107; font-weight: bold;">Pending</span></td>
            <td>Not Assigned</td>
            <td><span class="status-badge" style="background-color: #ffc107; color: #000; padding: 5px 10px; border-radius: 4px; font-size: 0.9em;">Pending</span></td>
          </tr>
        <?php endif; ?>
      </table>
    </section>

    <section class="book-test-section">
      <button onclick="window.location.href='book-test.php'" class="book-test-btn">Book a Test</button>
    </section>
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

  <script>
    function logoutUser() {
      window.location.href = "logout.php";
    }

    function viewReport(reportId) {
      window.open(`view-report.php?id=${reportId}`, '_blank');
    }
  </script>

</body>
</html>
