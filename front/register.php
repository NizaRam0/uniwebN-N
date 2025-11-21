<?php
session_start();
if (isset($_SESSION["id"])&& $_SESSION["role"] === "patient") {
    header("Location: ../front/patientDashboard.php");
    exit();
}
elseif(isset($_SESSION["id"])&& $_SESSION["role"] === "doctor") {
    header("Location: ../front/doctorDashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patient Registration</title>
    
    <style>
        /* GENERAL */
body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background: #ffffff;
}

/* CONTAINER */
.register-container {
    display: flex;
    justify-content: center;
    padding: 60px 40px;
    background: #ffffff;
}

/* CARD */
.register-card {
    width: 420px;
    background: #ffffff;
    padding: 35px 35px 45px 35px;
    border-radius: 12px;
    text-align: center;
    box-shadow: rgba(0, 0, 0, 0.10) 0px 6px 18px;
    border: 1px solid #e6e6e6;
}

/* TITLE */
.register-card h1 {
    font-size: 28px;
    color: #003e74;
    margin-bottom: 5px;
}

.subtitle {
    font-size: 14px;
    color: #666;
    margin-bottom: 25px;
}

/* INPUT FIELDS */
.input-group {
    text-align: left;
    margin-bottom: 18px;
}

.input-group label {
    font-size: 14px;
    color: #003e74;
    font-weight: 500;
    margin-bottom: 6px;
    display: block;
}

.input-group input {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    transition: border 0.2s;
}

.input-group input:focus {
    outline: none;
    border-color: #005bbb;
}

/* SUBMIT BUTTON */
.primary-btn {
    width: 100%;
    padding: 12px;
    background: #005bbb;
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    margin-top: 10px;
}

.primary-btn:hover {
    background: #004c94;
}

/* LOGIN LINK */
.login-link {
    margin-top: 18px;
    font-size: 14px;
    color: #555;
}

.login-link a {
    color: #005bbb;
    text-decoration: none;
}

.login-link a:hover {
    text-decoration: underline;
}

/* ERROR BOX */
.error {
    background: #ffebeb;
    border-left: 4px solid #d10000;
    padding: 10px;
    margin-bottom: 15px;
    color: #b30000;
    font-size: 14px;
    text-align: left;
    border-radius: 4px;
}

    </style>
</head>

<body>

<div class="register-container">

    <div class="register-card">

        <h1>Sign Up</h1>
        <p class="subtitle">Create your patient account</p>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <form action="../Backend/signUpAction.php" method="POST">

            <div class="input-group">
                <label>First Name</label>
                <input type="text" name="fname" required>
            </div>

            <div class="input-group">
                <label>Last Name</label>
                <input type="text" name="lname" required>
            </div>

            <div class="input-group">
                <label>Date of Birth</label>
                <input type="date" name="dob" required>
            </div>

            <div class="input-group">
                <label>Gender (M/F)</label>
                <input type="text" name="gender" maxlength="1" required>
            </div>

            <div class="input-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>

            <div class="input-group">
                <label>Password</label>
                <input type="password" name="pass" required>
            </div>

            <div class="input-group">
                <label>Pre-existing Conditions</label>
                <input type="text" name="cond" placeholder="None or condition:asthma diabetes etc..">
            </div>

            <div class="input-group">
                <label>Blood Type</label>
                <input type="text" name="blood" maxlength="3" required placeholder="A+,B+,A-,B- etc...">
            </div>

            <button type="submit" class="primary-btn">Create Account</button>

            <p class="login-link">
                Already have an account? <a href="login.php">Login</a>
            </p>

        </form>
    </div>
</div>

</body>
</html>
