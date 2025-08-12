<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $user_type = $_POST['user_type'];
    
    try {
        $stmt = $pdo->prepare("INSERT INTO users (email, password, user_type) VALUES (?, ?, ?)");
        $stmt->execute([$email, $password, $user_type]);
        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['user_type'] = $user_type;
        echo "<script>window.location.href='dashboard.php';</script>";
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Hiring Cafe</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .form-container { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        h2 { text-align: center; color: #2c3e50; }
        input, select { width: 100%; padding: 0.5rem; margin: 0.5rem 0; border: 1px solid #ccc; border-radius: 4px; }
        .btn { background: #3498db; color: white; padding: 0.5rem; border: none; border-radius: 4px; cursor: pointer; width: 100%; }
        .btn:hover { background: #2980b9; }
        .error { color: red; text-align: center; }
        @media (max-width: 768px) { .form-container { padding: 1rem; } }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Sign Up</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="user_type" required>
                <option value="candidate">Candidate</option>
                <option value="recruiter">Recruiter</option>
            </select>
            <button type="submit" class="btn">Sign Up</button>
        </form>
        <p>Already have an account? <a href="#" onclick="navigate('login.php')">Login</a></p>
    </div>
    <script>
        function navigate(page) { window.location.href = page; }
    </script>
</body>
</html>
