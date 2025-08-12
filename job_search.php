<?php
session_start();
require_once 'db.php';

$filters = [];
$query = "SELECT * FROM jobs WHERE 1=1";
if (!empty($_GET['category'])) {
    $query .= " AND category = ?";
    $filters[] = $_GET['category'];
}
if (!empty($_GET['job_type'])) {
    $query .= " AND job_type = ?";
    $filters[] = $_GET['job_type'];
}
if (!empty($_GET['location'])) {
    $query .= " AND location LIKE ?";
    $filters[] = "%" . $_GET['location'] . "%";
}
$stmt = $pdo->prepare($query);
$stmt->execute($filters);
$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Jobs - Hiring Cafe</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f4f4f4; }
        .container { max-width: 1200px; margin: 2rem auto; padding: 0 1rem; }
        .filter-form { display: flex; gap: 1rem; margin-bottom: 2rem; }
        .filter-form input, .filter-form select { padding: 0.5rem; border: 1px solid #ccc; border-radius: 4px; }
        .job-card { background: white; border-radius: 8px; padding: 1rem; margin: 1rem 0; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .job-card h3 { margin: 0 0 0.5rem; color: #2c3e50; }
        .btn { background: #3498db; color: white; padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer; }
        .btn:hover { background: #2980b9; }
        @media (max-width: 768px) { .filter-form { flex-direction: column; } .container { padding: 0 0.5rem; } }
    </style>
</head>
<body>
    <div class="container">
        <h2>Find Your Dream Job</h2>
        <form class="filter-form" method="GET">
            <input type="text" name="category" placeholder="Category" value="<?php echo htmlspecialchars($_GET['category'] ?? ''); ?>">
            <select name="job_type">
                <option value="">All Job Types</option>
                <option value="full_time" <?php echo ($_GET['job_type'] ?? '') === 'full_time' ? 'selected' : ''; ?>>Full Time</option>
                <option value="part_time" <?php echo ($_GET['job_type'] ?? '') === 'part_time' ? 'selected' : ''; ?>>Part Time</option>
                <option value="remote" <?php echo ($_GET['job_type'] ?? '') === 'remote' ? 'selected' : ''; ?>>Remote</option>
            </select>
            <input type="text" name="location" placeholder="Location" value="<?php echo htmlspecialchars($_GET['location'] ?? ''); ?>">
            <button type="submit" class="btn">Search</button>
        </form>
        <?php foreach ($jobs as $job): ?>
            <div class="job-card">
                <h3><?php echo htmlspecialchars($job['title']); ?></h3>
                <p><?php echo htmlspecialchars($job['description']); ?></p>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?> | <strong>Type:</strong> <?php echo htmlspecialchars($job['job_type']); ?></p>
                <a href="#" onclick="navigate('interview_schedule.php')" class="btn">Apply Now</a>
            </div>
        <?php endforeach; ?>
        <a href="#" onclick="navigate('dashboard.php')" class="btn">Back to Dashboard</a>
    </div>
    <script>
        function navigate(page) { window.location.href = page; }
    </script>
</body>
</html>
