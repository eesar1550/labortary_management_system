<?php
session_start();
require 'db.php';

$success_message = '';
$error_message = '';
$report = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
    $report_id = isset($_POST['report_id']) ? $_POST['report_id'] : '';
    $report_type = isset($_POST['report_type']) ? $_POST['report_type'] : '';
    $report_content = isset($_POST['report_content']) ? $_POST['report_content'] : '';

    if (!empty($user_id) && !empty($report_type) && !empty($report_content)) {
        // If no report_id is provided, generate one
        if (empty($report_id)) {
            $report_id = "LAB" . time();
        }

        // Check if report exists
        $check_stmt = $conn->prepare("SELECT * FROM reports WHERE report_id = ? AND user_id = ?");
        $check_stmt->bind_param("ss", $report_id, $user_id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            // Update existing report
            $stmt = $conn->prepare("UPDATE reports SET status = 'Completed', report_type = ?, report_content = ? WHERE report_id = ? AND user_id = ?");
            $stmt->bind_param("ssss", $report_type, $report_content, $report_id, $user_id);
        } else {
            // Create new report
            $stmt = $conn->prepare("INSERT INTO reports (report_id, user_id, report_date, status, report_type, report_content) VALUES (?, ?, CURDATE(), 'Completed', ?, ?)");
            $stmt->bind_param("ssss", $report_id, $user_id, $report_type, $report_content);
        }

        if ($stmt->execute()) {
            $success_message = "Report updated successfully!";
            
            // Fetch the updated report
            $fetch_stmt = $conn->prepare("SELECT * FROM reports WHERE report_id = ? AND user_id = ?");
            $fetch_stmt->bind_param("ss", $report_id, $user_id);
            $fetch_stmt->execute();
            $report = $fetch_stmt->get_result()->fetch_assoc();
        } else {
            $error_message = "Error updating report: " . $stmt->error;
        }
    } else {
        $error_message = "Please fill in all required fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Report Status - SWEM</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .logo {
            display: block;
            margin: 0 auto 20px;
            max-width: 200px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"], select, textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea {
            height: 150px;
            resize: vertical;
        }
        .submit-btn {
            background-color: #d10000;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        .submit-btn:hover {
            background-color: #b30000;
        }
        .success {
            color: #28a745;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #d4edda;
            border-radius: 4px;
        }
        .error {
            color: #d10000;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f8d7da;
            border-radius: 4px;
        }
        .report-content {
            margin-top: 20px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .view-btn {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }
        .view-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="logo.png" alt="SWEM Logo" class="logo">
        <h2>Update Report Status</h2>
        
        <?php if ($success_message): ?>
            <div class="success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        
        <?php if ($error_message): ?>
            <div class="error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="user_id">User ID:</label>
                <input type="text" id="user_id" name="user_id" required 
                       value="<?php echo isset($_POST['user_id']) ? htmlspecialchars($_POST['user_id']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="report_id">Report ID (leave empty for new report):</label>
                <input type="text" id="report_id" name="report_id"
                       value="<?php echo isset($_POST['report_id']) ? htmlspecialchars($_POST['report_id']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="report_type">Report Type:</label>
                <select id="report_type" name="report_type" required>
                    <option value="">Select Report Type</option>
                    <option value="Blood Test" <?php echo (isset($_POST['report_type']) && $_POST['report_type'] == 'Blood Test') ? 'selected' : ''; ?>>Blood Test</option>
                    <option value="X-Ray" <?php echo (isset($_POST['report_type']) && $_POST['report_type'] == 'X-Ray') ? 'selected' : ''; ?>>X-Ray</option>
                    <option value="MRI" <?php echo (isset($_POST['report_type']) && $_POST['report_type'] == 'MRI') ? 'selected' : ''; ?>>MRI</option>
                    <option value="CT Scan" <?php echo (isset($_POST['report_type']) && $_POST['report_type'] == 'CT Scan') ? 'selected' : ''; ?>>CT Scan</option>
                    <option value="Ultrasound" <?php echo (isset($_POST['report_type']) && $_POST['report_type'] == 'Ultrasound') ? 'selected' : ''; ?>>Ultrasound</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="report_content">Report Content:</label>
                <textarea id="report_content" name="report_content" required><?php echo isset($_POST['report_content']) ? htmlspecialchars($_POST['report_content']) : ''; ?></textarea>
            </div>
            
            <button type="submit" class="submit-btn">Update Report</button>
        </form>

        <?php if ($report): ?>
            <div class="report-content">
                <h3>Report Updated Successfully</h3>
                <p><strong>Report ID:</strong> <?php echo htmlspecialchars($report['report_id']); ?></p>
                <p><strong>Status:</strong> <?php echo htmlspecialchars($report['status']); ?></p>
                <p><strong>Type:</strong> <?php echo htmlspecialchars($report['report_type']); ?></p>
                <p><strong>Date:</strong> <?php echo htmlspecialchars($report['report_date']); ?></p>
                <a href="view-report.php?id=<?php echo htmlspecialchars($report['report_id']); ?>" class="view-btn" target="_blank">View Report</a>
            </div>
        <?php endif; ?>

        <div style="margin-top: 20px;">
            <a href="dashboard.php" style="color: #d10000;">Back to Dashboard</a>
        </div>
    </div>
</body>
</html> 