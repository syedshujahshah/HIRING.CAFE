<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'candidate') {
    echo "<script>window.location.href='login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $skills = $_POST['skills'];
    $experience_years = $_POST['experience_years'];
    
    // Handle file uploads
    $resume = $_FILES['resume']['name'] ? 'uploads/' . basename($_FILES['resume']['name']) : null;
    $video_intro = $_FILES['video_intro']['name'] ? 'uploads/' . basename($_FILES['video_intro']['name']) : null;
    
    if ($resume) move_uploaded_file($_FILES['resume']['tmp_name'], $resume);
    if ($video_intro) move_uploaded_file($_FILES['video_intro']['tmp_name'], $video_intro);
    
    $stmt = $pdo->prepare("INSERT INTO candidate_profiles (user_id, full_name, skills, resume, video_intro, experience_years) VALUES (?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE full_name = ?, skills = ?, resume = ?, video_intro = ?, experience_years = ?");
    $stmt->execute([$user_id, $full_name, $skills, $resume, $video_intro, $experience_years, $full_name, $skills, $resume, $video_intro, $experience_years]);
    echo "<script>window.location.href='dashboard.php';</script>";
}

$profile = $pdo->prepare("SELECT * FROM candidate_profiles WHERE user_id = ?");
$profile->execute([$user_id]);
$profile = $profile->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Profile - Hiring Cafe</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .form-container { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); width: 100%; max-width: 600px; }
        h2 { text-align: center; color: #2c3e50; }
        input, textarea { width: 100%; padding: 0.5rem; margin: 0.5rem 0; border: 1px solid #ccc; border-radius: 4px; }
        .btn { background: #3498db; color: white; padding: 0.5rem; border: none; border-radius: 4px; cursor: pointer; width: 100%; }
        .btn:hover { background: #2980b9; }
        @media (max-width: 768px) { .form-container { padding: 1rem; } }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Create/Update Profile</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="full_name" placeholder="Full Name" value="<?php echo htmlspecialchars($profile['full_name'] ?? ''); ?>" required>
            <textarea name="skills" placeholder="Skills (comma-separated)" required><?php echo htmlspecialchars($profile['skills'] ?? ''); ?></textarea>
            <input type="number" name="experience_years" placeholder="Years of Experience" value="<?php echo htmlspecialchars($profile['experience_years'] ?? ''); ?>" required>
            <input type="file" name="resume" accept=".pdf">
            <input type="file" name="video_intro" accept="video/*">
            <button type="submit" class="btn">Save Profile</button>
        </form>
        <a href="#" onclick="navigate('dashboard.php')" class="btn">Back to Dashboard</a>
    </div>
    <script>
        function navigate(page) { window.location.href = page; }
    </script>
</body>
</html>
