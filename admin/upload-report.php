<?php
session_start();
require '../db.php';

// Check if user is admin/manager
if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
    header("Location: ../index.php");
    exit();
}

// Handle report upload
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $report_id = $conn->real_escape_string($_POST['report_id']);
    $user_id = $conn->real_escape_string($_POST['user_id']);
    $report_type = $conn->real_escape_string($_POST['report_type']);
    $report_content = $conn->real_escape_string($_POST['report_content']);
    
    // Handle file upload
    $target_dir = "../uploads/reports/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file_path = null;
    if (isset($_FILES["report_file"]) && $_FILES["report_file"]["error"] == 0) {
        $file_extension = pathinfo($_FILES["report_file"]["name"], PATHINFO_EXTENSION);
        $file_name = $report_id . "_" . time() . "." . $file_extension;
        $target_file = $target_dir . $file_name;
        
        if (move_uploaded_file($_FILES["report_file"]["tmp_name"], $target_file)) {
            $file_path = "uploads/reports/" . $file_name;
        }
    }
    
    // Update report status to completed and save file path
    $stmt = $conn->prepare("UPDATE reports SET status = 'Completed', report_type = ?, report_content = ?, file_path = ? WHERE report_id = ? AND user_id = ?");
    $stmt->bind_param("sssss", $report_type, $report_content, $file_path, $report_id, $user_id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Report uploaded successfully!');</script>";
    } else {
        echo "<script>alert('Error uploading report: " . $stmt->error . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Report - SWEM Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .upload-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea {
            height: 100px;
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
        }
        .submit-btn:hover {
            background-color: #b30000;
        }
    </style>
</head>
<body>
    <div class="upload-container">
        <h2>Upload Medical Report</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="report_id">Report ID:</label>
                <input type="text" id="report_id" name="report_id" required>
            </div>
            
            <div class="form-group">
                <label for="user_id">User ID:</label>
                <input type="text" id="user_id" name="user_id" required>
            </div>
            
            <div class="form-group">
                <label for="report_type">Report Type:</label>
                <select id="report_type" name="report_type" required>
                    <option value="X-Ray">X-Ray</option>
                    <option value="Blood Test">Blood Test</option>
                    <option value="MRI">MRI</option>
                    <option value="CT Scan">CT Scan</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="report_content">Report Findings:</label>
                <textarea id="report_content" name="report_content" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="report_file">Upload Report File:</label>
                <input type="file" id="report_file" name="report_file" accept="image/*,.pdf" required>
            </div>
            
            <button type="submit" class="submit-btn">Upload Report</button>
        </form>
    </div>
</body>
</html> 