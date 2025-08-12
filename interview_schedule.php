<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user_type === 'recruiter') {
    $job_id = $_POST['job_id'];
    $candidate_id = $_POST['candidate_id'];
    $interview_time = $_POST['interview_time'];
    $interview_type = $_POST['interview_type'];
    
    $stmt = $pdo->prepare("INSERT INTO interviews (job_id, candidate_id, recruiter_id, interview_time, interview_type) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$job_id, $candidate_id, $user_id, $interview_time, $interview_type]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $receiver_id = $_POST['receiver_id'];
    $job_id = $_POST['job_id'];
    $message = $_POST['message'];
    
    $stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, job_id, message) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $receiver_id, $job_id, $message]);
}

$interviews = $pdo->prepare("SELECT * FROM interviews WHERE recruiter_id = ? OR candidate_id = ?");
$interviews->execute([$user_id, $user_id]);
$interviews = $interviews->fetchAll(PDO::FETCH_ASSOC);

$messages = $pdo->prepare("SELECT * FROM messages WHERE sender_id = ? OR receiver_id = ?");
$messages->execute([$user_id, $user_id]);
$messages = $messages->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Interviews - Hiring Cafe</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f4f4f4; }
        .container { max-width: 1200px; margin: 2rem auto; padding: 0 1rem; }
        .section { margin-bottom: 2rem; }
        .form-container { background: white; padding: 1rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        input, select, textarea { width: 100%; padding: 0.5rem; margin: 0.5rem 0; border: 1px solid #ccc; border-radius: 4px; }
        .btn { background: #3498db; color: white; padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer; }
        .btn:hover { background: #2980b9; }
        .interview-card, .message-card { background: white; border-radius: 8px; padding: 1rem; margin: 1rem 0; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        @media (max-width: 768px) { .container { padding: 0 0.5rem; } }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($user_type === 'recruiter'): ?>
            <div class="section">
                <h2>Schedule an Interview</h2>
                <div class="form-container">
                    <form method="POST">
                        <input type="number" name="job_id" placeholder="Job ID" required>
                        <input type="number" name="candidate_id" placeholder="Candidate ID" required>
                        <input type="datetime-local" name="interview_time" required>
                        <select name="interview_type" required>
                            <option value="video">Video</option>
                            <option value="in_person">In-Person</option>
                        </select>
                        <button type="submit" class="btn">Schedule</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
        <div class="section">
            <h2>Your Interviews</h2>
            <?php foreach ($interviews as $interview): ?>
                <div class="interview-card">
                    <p><strong>Job ID:</strong> <?php echo htmlspecialchars($interview['job_id']); ?></p>
                    <p><strong>Time:</strong> <?php echo htmlspecialchars($interview['interview_time']); ?></p>
                    <p><strong>Type:</strong> <?php echo htmlspecialchars($interview['interview_type']); ?></p>
                    <p><strong>Status:</strong> <?php echo htmlspecialchars($interview['status']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="section">
            <h2>Messages</h2>
            <div class="form-container">
                <form method="POST">
                    <input type="number" name="receiver_id" placeholder="Receiver ID" required>
                    <input type="number" name="job_id" placeholder="Job ID">
                    <textarea name="message" placeholder="Your message" required></textarea>
                    <button type="submit" class="btn">Send Message</button>
                </form>
            </div>
            <?php foreach ($messages as $message): ?>
                <div class="message-card">
                    <p><strong>From:</strong> <?php echo htmlspecialchars($message['sender_id']); ?></p>
                    <p><strong>Message:</strong> <?php echo htmlspecialchars($message['message']); ?></p>
                    <p><strong>Sent:</strong> <?php echo htmlspecialchars($message['sent_at']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        <a href="#" onclick="navigate('dashboard.php')" class="btn">Back to Dashboard</a>
    </div>
    <script>
        function navigate(page) { window.location.href = page; }
    </script>
</body>
</html>
