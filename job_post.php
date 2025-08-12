<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'recruiter') {
    echo "<script>window.location.href='login.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $job_type = $_POST['job_type'];
    $salary_min = $_POST['salary_min'];
    $salary_max = $_POST['salary_max'];
    $location = $_POST['location'];
    $user_id = $_SESSION['user_id'];
    
    $stmt = $pdo->prepare("INSERT INTO jobs (user_id, title, description, category, job_type, salary_min, salary_max, location) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $title, $description, $category, $job_type, $salary_min, $salary_max, $location]);
    echo "<script>window.location.href='dashboard.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post a Job - Hiring Cafe</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .form-container { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); width: 100%; max-width: 600px; }
        h2 { text-align: center; color: #2c3e50; }
        input, select, textarea { width: 100%; padding: 0.5rem; margin: 0.5rem 0; border: 1px solid #ccc; border-radius: 4px; }
        .btn { background: #3498db; color: white; padding: 0.5rem; border: none; border-radius: 4px; cursor: pointer; width: 100%; }
        .btn:hover { background: #2980b9; }
        @media (max-width: 768px) { .form-container { padding: 1rem; } }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Post a Job</h2>
        <form method="POST">
            <input type="text" name="title" placeholder="Job Title" required>
            <textarea name="description" placeholder="Job Description" required></textarea>
            <input type="text" name="category" placeholder="Category (e.g., IT, Marketing)" required>
            <select name="job_type" required>
                <option value="full_time">Full Time</option>
                <option value="part_time">Part Time</option>
                <option value="remote">Remote</option>
            </select>
            <input type="number" name="salary_min" placeholder="Minimum Salary" required>
            <input type="number" name="salary_max" placeholder="Maximum Salary" required>
            <input type="text" name="location" placeholder="Location" required>
            <button type="submit" class="btn">Post Job</button>
        </form>
        <a href="#" onclick="navigate('dashboard.php')" class="btn">Back to Dashboard</a>
    </div>
    <script>
        function navigate(page) { window.location.href = page; }
    </script>
</body>
</html>
