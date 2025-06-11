<?php
session_start();
require 'db.php';

// Debug information
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION['firstName']) || !isset($_SESSION['userID'])) {
    die("Please log in to view reports.");
}

// Check if report ID is provided
if (!isset($_GET['id'])) {
    die("Report ID not provided");
}

$reportId = $conn->real_escape_string($_GET['id']);
$userId = $_SESSION['userID'];

// Fetch report details with more detailed error handling
$stmt = $conn->prepare("SELECT * FROM reports WHERE report_id = ? AND user_id = ?");
$stmt->bind_param("ss", $reportId, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Report not found or you don't have permission to view it.");
}

$report = $result->fetch_assoc();

// Check if report is completed
if ($report['status'] !== 'Completed') {
    die("This report is still in progress. Please check back later.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Report - SWEM</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .report-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .report-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .report-header img {
            max-width: 200px;
            margin-bottom: 20px;
        }
        .report-title {
            color: #d10000;
            font-size: 24px;
            margin: 20px 0;
        }
        .report-info {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .report-info p {
            margin: 10px 0;
            line-height: 1.5;
        }
        .report-content {
            margin-top: 30px;
            line-height: 1.6;
        }
        .report-image {
            max-width: 100%;
            height: auto;
            margin: 20px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .action-buttons {
            margin-top: 30px;
            display: flex;
            gap: 15px;
            justify-content: center;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .back-btn {
            background-color: #333;
            color: white;
        }
        .print-btn {
            background-color: #d10000;
            color: white;
        }
        .btn:hover {
            opacity: 0.9;
        }
        @media print {
            .action-buttons {
                display: none;
            }
            body {
                padding: 0;
                background-color: white;
            }
            .report-container {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="report-container">
        <div class="report-header">
            <img src="logo.png" alt="SWEM Logo">
            <h1 class="report-title">Medical Report</h1>
            <div class="report-info">
                <p><strong>Report ID:</strong> <?php echo htmlspecialchars($report['report_id']); ?></p>
                <p><strong>Patient Name:</strong> <?php echo htmlspecialchars($_SESSION['firstName'] . ' ' . $_SESSION['lastName']); ?></p>
                <p><strong>Date:</strong> <?php echo htmlspecialchars($report['report_date']); ?></p>
                <p><strong>Test Type:</strong> <?php echo htmlspecialchars($report['report_type']); ?></p>
            </div>
        </div>

        <div class="report-content">
            <?php if ($report['file_path']): ?>
                <img src="<?php echo htmlspecialchars($report['file_path']); ?>" alt="Medical Report Image" class="report-image">
            <?php endif; ?>

            <?php if ($report['report_content']): ?>
                <h2>Report Findings</h2>
                <div class="report-text">
                    <?php echo nl2br(htmlspecialchars($report['report_content'])); ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="action-buttons">
            <a href="dashboard.php" class="btn back-btn">Back to Dashboard</a>
            <button onclick="window.print()" class="btn print-btn">Print Report</button>
        </div>
    </div>
</body>
</html> 