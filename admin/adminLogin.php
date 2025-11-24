<?php
session_start();
if (isset($_SESSION["role"]) && $_SESSION["role"] === "admin") {
    header("Location: adminDashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="../front/loginSignup.css">
</head>

<body>

<div class="login-container">
    <div class="login-card">

        <h1>Admin Login</h1>
        <p class="subtitle">Access the Admin Panel</p>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <form action="../Backend/adminLoginAction.php" method="POST">
            <input type="email" placeholder="Email" name="email">
            <input type="password" placeholder="Password" name="password">
            <button class="login-submit">Login</button>
        </form>

    </div>
</div>

</body>
</html>
