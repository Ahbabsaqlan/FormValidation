<?php
session_start();

// Handle logout
if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    // Unset all session variables
    $_SESSION = [];

    // Destroy the session
    session_destroy();

    // Redirect to login page
    header("Location: index.php");
    exit;
}

// Check if user is logged in
$user = $_SESSION['user'] ?? null;

if (!$user) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Welcome</title>
</head>
<body>
    <h1>Welcome, <?= htmlspecialchars($user['userName']) ?>!</h1>
    <h1>Country: <?= htmlspecialchars($user['country']) ?></h1>
    
    <!-- Logout link -->
    <p><a href="home.php?logout=true">Logout</a></p>
</body>
</html>