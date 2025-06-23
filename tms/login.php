<?php
session_start();
require_once 'includes/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    // Use prepared statements to prevent SQL injection
    $stmt = $main_db->prepare('SELECT * FROM user WHERE username = ? LIMIT 1');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($user = $result->fetch_assoc()) {
        // For demo: using md5, for production use password_hash
        if ($user['password'] === md5($password)) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            // Redirect based on where the user logged in from
            if (isset($_GET['tms'])) {
                header('Location: dash.php'); // TMS dashboard
            } else {
                header('Location: ../dashboard_new/dash.php'); // Main user dashboard
            }
            exit;
        } else {
            $error = 'Invalid password.';
        }
    } else {
        $error = 'User not found.';
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>TMS Login</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <h2>Login to TMS</h2>
    <?php if ($error): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="post" action="login.php">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
    </form>
</body>

</html>