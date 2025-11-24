<?php
session_start();

// Only allow admin
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: adminLogin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - MediCare Hub</title>

    <!-- Main Styles -->
    <link rel="stylesheet" href="../front/styling.css">
    <link rel="stylesheet" href="../front/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        /* Extra small adjustments for admin */
        .dash-title {
            font-size: 42px;
            color: #003e74;
            text-align: center;
            margin-top: 40px;
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="logo">
        <img src="../medias/logo.png" alt="Logo">
        <span>MediCare Hub</span>
    </div>

    <div class="nav-links">
        <a href="adminDashboard.php" class="active">Dashboard</a>
        <a style="background-color:red;" href="../Backend/logoutAction.php" class="login-btn">Logout</a>
    </div>
</div>

<!-- DASHBOARD MAIN -->
<div class="dashboard-container">

    <h1 class="dash-title">Admin Control Panel</h1>

    <div class="dashboard-grid">

        <!-- VIEW PATIENTS CARD -->
        <div class="dash-card">
            <h3>View All Patients</h3>
            <p>Manage patient accounts: edit, delete, or add new patients.</p>
            <a href="adminPatients.php" class="btn-small">View Patients</a>
        </div>

        <!-- VIEW DOCTORS CARD -->
        <div class="dash-card">
            <h3>View All Doctors</h3>
            <p>View doctor list, add new doctors, update profiles or remove accounts.</p>
            <a href="adminDoctors.php" class="btn-small">View Doctors</a>
        </div>

    </div>
</div>

</body>
</html>
