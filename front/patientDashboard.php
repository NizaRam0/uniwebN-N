<?php
session_start();
require "../Backend/dbconx.php";

if (!isset($_SESSION["id"])) {
    header("Location: ../front/login.php");
    exit();
}

if ($_SESSION["role"] !== "patient") {
    header("Location: doctorDashboard.php");
    exit();
}

$patient_id = $_SESSION["id"];
$patient_name = $_SESSION["UserName"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard</title>
    <link rel="stylesheet" href="styling.css">
</head>
<body>

    <!-- NAVBAR -->
    <div class="navbar">
        <div class="logo">
            <img src="../medias/logo.png" style="width: 150px;">
        </div>
        
        <a class="login-btn" href="../Backend/logoutAction.php" style="background-color:#FF2400;">Logout</a>
    </div>

    <h1 class="dash-title">Welcome Back, <?php echo htmlspecialchars($patient_name); ?></h1>

<div class="dashboard-grid">

    <!-- UPCOMING APPOINTMENT -->
    <div class="dash-card">
        <h3>Upcoming Appointment</h3>
        <div id="appt-box">
            <p>Loading...</p>

        </div>
    </div>

    <!-- PRESCRIPTIONS -->
    <div class="dash-card">
        <h3>Your Prescriptions</h3>
        <p id="prescriptions-count">Loading...</p></p>
        <a href="prescriptions.php" class="btn-small">View</a>
    </div>

    <!-- Previous Tests Reports -->
    <div class="dash-card">
        <h3>Your Tests Reports</h3>
        <p id="tests-count">Loading...</p>
        <a href="viewTests.php" class="btn-small">View</a>
    </div>

    <!-- View information -->
    <div class="dash-card">
        <h3>Your Information</h3>
        <p>View and edit all your personal information</p>
        <a href="viewInformation.php" class="btn-small">View</a>
    </div>

    <!-- MESSAGES -->
    <div class="dash-card">
        <h3>Messages</h3>
        <p id="msg-count">Loading...</p>
        <a href="viewMessages.php" class="btn-small">Open Inbox</a>
    </div>

</div>

<!-- ========================================= -->
<!--               JAVASCRIPT API              -->
<!-- ========================================= -->
<script>
let patientId = <?php echo $patient_id; ?>;

// =================== UPCOMING APPOINTMENT ===================
fetch("../Backend/api.php?type=appointments&id=" + patientId)
    .then(res => res.json())
    .then(data => {
        let box = document.getElementById("appt-box");

        if (!Array.isArray(data) || data.length === 0) {
            box.innerHTML = `
                <p>No upcoming appointments.</p>
                <a href="../booking/Booking-Department.php" class="btn-small">Book Appointment</a>
            `;
            return;
        }

        // Get nearest appointment
        let appt = data[0];

        box.innerHTML = `
            <p>
                With Dr. ${appt.DoctorFirst} ${appt.DoctorLast}<br>
                On ${appt.Appointment_Date} at ${appt.Appointment_time}
            </p>
            <a href="viewAppointments.php?id=${appt.Appointment_id}" class="btn-small">View</a>
        `;
    })
    .catch(err => {
        document.getElementById("appt-box").innerHTML = "<p>Error loading appointment.</p>";
    });


// =================== TESTS COUNT ===================
fetch("../Backend/api.php?type=tests&id=" + patientId)
    .then(res => res.json())
    .then(data => {
        document.getElementById("tests-count").innerText =
            `You currently have ${data.length} test reports.`;
    })
    .catch(() => {
        document.getElementById("tests-count").innerText = "Could not load tests.";
    });


// =================== PRESCRIPTIONS (0 for now) ===================
fetch("../Backend/api.php?type=prescriptions&id=" + patientId)
    .then(res => res.json())
    .then(data => {
        document.getElementById("prescriptions-count").innerText =
        `You currently have ${data.length}  prescreptions.`;
    });
    


// =================== MESSAGES (0 until messages table exists) ===================
document.getElementById("msg-count").innerText = 
    "0 unread messages.";

</script>

</body>
</html>
