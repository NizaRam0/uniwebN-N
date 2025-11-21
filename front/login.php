<?php
session_start();
if (isset($_SESSION["id"]) && isset($_SESSION["role"])) {
    if ($_SESSION["role"] === "doctor") {
        header("Location: doctorDashboard.php");
    } else {
        header("Location: patientDashboard.php");
    }
    exit();
} 

if(isset($_SESSION["id"])){
header("Location:homePage.php");
exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="loginSignup.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <title>Login</title>
</head>

<body>
<div class="login-container">

        <div class="login-card">

            <div class="login-logo" >
                <img src="../medias/logo.png" alt="Logo">
            </div>

            <h1>Login</h1>
            <p class="subtitle">Please login to your account.</p>

            <form action="../Backend/loginAction.php" method="POST">
            <input type="email" name="email" placeholder="Email Address" required>

                <div class="password-wrapper">
                <input type="password" name="pass" placeholder="Password" required>
                <a href="#" class="forgot-link">Forgot password?</a>
                </div>

                <button class="login-submit">Login</button>
            </form>

            <p class="signup-text">
                Don’t have an account? <a href="register.php">Sign Up</a>
            </p>

        </div>

    </div>
    <?php if (isset($_SESSION['error'])): ?>
    <div class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

</body>
</html>