<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Hiring Cafe</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f4f4f4; }
        header { background: #2c3e50; color: white; padding: 1rem; text-align: center; }
        .container { max-width: 1200px; margin: 2rem auto; padding: 0 1rem; }
        .section { margin-bottom: 2rem; }
        .btn { background: #3498db; color: white; padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn:hover { background: #2980b9; }
        @media (max-width: 768px) { .container { padding: 0 0.5rem; } }
    </style>
</head>
<body>
    <header>
        <h1>Welcome to Your Dashboard</h1>
    </header>
    <div class="container">
        <?php if ($user_type === 'recruiter'): ?>
            <div class="section">
                <h2>Recruiter Dashboard</h2>
                <a href="#" onclick="navigate('job_post.php')" class="btn">Post a Job</a>
                <a href="#" onclick="navigate('interview_schedule.php')" class="btn">Schedule Interviews</a>
            </div>
        <?php else: ?>
            <div class="section">
                <h2>Candidate Dashboard</h2>
                <a href="#" onclick="navigate('candidate_profile.php')" class="btn">Create/Update Profile</a>
                <a href="#" onclick="navigate('job_search.php')" class="btn">Search Jobs</a>
                <a href="#" onclick="navigate('interview_schedule.php')" class="btn">View Interviews</a>
            </div>
        <?php endif; ?>
        <a href="#" onclick="navigate('logout.php')" class="btn">Logout</a>
    </div>
    <script>
        function navigate(page) { window.location.href = page; }
    </script>
</body>
</html>
