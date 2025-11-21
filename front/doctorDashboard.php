<?php
session_start();
require "../Backend/dbconx.php";

// Allow ONLY doctors
if (!isset($_SESSION["loggedIn"]) || $_SESSION["loggedIn"] !== true || $_SESSION["role"] !== "doctor") {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard</title>

    <link rel="stylesheet" href="styling.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="logo">
        <img src="../medias/logo.png" alt="Logo">
        <span>MediCare Hub</span>
    </div>

    <div class="nav-links">
        <a href="doctorDashboard.php" class="active">Dashboard</a>
        <a href="doctorAppointments.php">Appointments</a>
        <a href="doctorPatients.php">Patients</a>
        <a href="doctorReports.php">Reports</a>
    </div>

    <a class="login-btn" href="../Backend/logoutAction.php" style="Background-color:#FF2400;">Logout</a>
</div>

<!-- DASHBOARD CONTENT -->
<div class="dashboard-container">
    <h1 class="dash-title">Doctor Dashboard</h1>

    <div class="dashboard-grid">

        <!-- Today's Appointments -->
        <div class="dash-card">
            <h3>Today's Appointments</h3>
            <p id="todayCount">Loading...</p>
            <a href="doctorAppointments.php" class="btn-small">View Schedule</a>
        </div>

        <!-- All Patients -->
        <div class="dash-card">
            <h3>My Patients</h3>
            <p id="patientCount">Loading...</p>
            <a href="doctorPatients.php" class="btn-small">View Patients</a>
        </div>

        <!-- Pending Tests -->
        <div class="dash-card">
            <h3>Pending Lab Reports</h3>
            <p id="pendingCount">Loading...</p>
            <a href="doctorReports.php" class="btn-small">Review</a>
        </div>

    </div>
</div>

<script>

// Load Doctor Dashboard Data from API
async function loadDashboard() {

    // 1. Today’s Appointments
    const todayRes = await fetch("../Backend/drapi.php?type=today_appointments");
    const todayData = await todayRes.json();
    document.getElementById("todayCount").innerHTML =
        todayData.length + " appointment(s) today.";

    // 2. All Patients (search API with empty q)
    const patientsRes = await fetch("../Backend/drapi.php?type=search_patient&q=");
    const patientsData = await patientsRes.json();
    document.getElementById("patientCount").innerHTML =
        patientsData.length + " patient(s).";

    // 3. Pending Tests (unfinished interpretation = Notes empty OR NULL)
    const testsRes = await fetch("../Backend/drapi.php?type=patient_tests&patient_id=0");
    // GOOD NEWS: For pending tests we should create a new API:
    // but for now, we load all tests manually and filter here.
    
    // Instead we call new API:
    const pendingRes = await fetch("../Backend/drapi.php?type=pending_reports");
    const pendingData = await pendingRes.json();

    document.getElementById("pendingCount").innerHTML =
        pendingData.length + " pending report(s).";
}

loadDashboard();

</script>

</body>
</html>
