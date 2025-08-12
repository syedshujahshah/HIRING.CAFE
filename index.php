<?php
session_start();
require_once 'db.php';
$jobs = $pdo->query("SELECT * FROM jobs ORDER BY posted_at DESC LIMIT 6")->fetchAll(PDO::FETCH_ASSOC);
$candidates = $pdo->query("SELECT * FROM candidate_profiles ORDER BY id DESC LIMIT 6")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hiring Cafe - Find Jobs & Talent</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f4f4f4; }
        header { background: #2c3e50; color: white; padding: 1rem; text-align: center; }
        nav { display: flex; justify-content: center; gap: 1rem; }
        nav a { color: white; text-decoration: none; font-weight: bold; }
        nav a:hover { text-decoration: underline; }
        .container { max-width: 1200px; margin: 2rem auto; padding: 0 1rem; }
        .section { margin-bottom: 2rem; }
        .job-card, .candidate-card { background: white; border-radius: 8px; padding: 1rem; margin: 1rem 0; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .job-card h3, .candidate-card h3 { margin: 0 0 0.5rem; color: #2c3e50; }
        .btn { background: #3498db; color: white; padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn:hover { background: #2980b9; }
        @media (max-width: 768px) { .container { padding: 0 0.5rem; } nav { flex-direction: column; } }
    </style>
</head>
<body>
    <header>
        <h1>Hiring Cafe</h1>
        <nav>
            <a href="#" onclick="navigate('index.php')">Home</a>
            <a href="#" onclick="navigate('job_search.php')">Find Jobs</a>
            <a href="#" onclick="navigate('candidate_profile.php')">Create Profile</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="#" onclick="navigate('dashboard.php')">Dashboard</a>
                <a href="#" onclick="navigate('logout.php')">Logout</a>
            <?php else: ?>
                <a href="#" onclick="navigate('login.php')">Login</a>
                <a href="#" onclick="navigate('signup.php')">Sign Up</a>
            <?php endif; ?>
        </nav>
    </header>
    <div class="container">
        <div class="section">
            <h2>Trending Jobs</h2>
            <?php foreach ($jobs as $job): ?>
                <div class="job-card">
                    <h3><?php echo htmlspecialchars($job['title']); ?></h3>
                    <p><?php echo htmlspecialchars($job['description']); ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?> | <strong>Type:</strong> <?php echo htmlspecialchars($job['job_type']); ?></p>
                    <a href="#" onclick="navigate('job_search.php')" class="btn">Apply Now</a>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="section">
            <h2>Top Candidates</h2>
            <?php foreach ($candidates as $candidate): ?>
                <div class="candidate-card">
                    <h3><?php echo htmlspecialchars($candidate['full_name']); ?></h3>
                    <p><strong>Skills:</strong> <?php echo htmlspecialchars($candidate['skills']); ?></p>
                    <p><strong>Experience:</strong> <?php echo htmlspecialchars($candidate['experience_years']); ?> years</p>
                    <a href="#" onclick="navigate('candidate_profile.php')" class="btn">View Profile</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script>
        function navigate(page) { window.location.href = page; }
    </script>
</body>
</html>
