<?php
session_start();
require "dbConx.php";  // your database connection file

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $email = trim($_POST["email"]);
    $pass  = trim($_POST["password"]);

    // Basic empty check
    if (empty($email) || empty($pass)) {
        $_SESSION["error"] = "Please enter both email and password.";
        header("Location: ../front/adminLogin.php");
        exit();
    }

    // Search for admin in database
    $stmt = $pdo->prepare("SELECT * FROM Admins WHERE Email = ?");
    $stmt->execute([$email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    // Email not found
    if (!$admin) {
        $_SESSION["error"] = "Email not found.";
        header("Location: ../front/adminLogin.php");
        exit();
    }

    // Password verification
    if (!password_verify($pass, $admin["Password"])) {
        $_SESSION["error"] = "Incorrect password.";
        header("Location: ../front/adminLogin.php");
        exit();
    }

    // SUCCESS → Set session
    $_SESSION["loggedIn"] = true;
    $_SESSION["role"] = "admin";
    $_SESSION["id"] = $admin["Admin_id"];
    $_SESSION["name"] = $admin["First_name"];

    // Redirect to admin dashboard
    header("Location: ../admin/adminDashboard.php");
    exit();
}
?>
