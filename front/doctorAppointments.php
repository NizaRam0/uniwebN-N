<?php
session_start();
require "../Backend/dbconx.php";

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
    <title>Doctor Appointments</title>

    <link rel="stylesheet" href="styling.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>

<div class="navbar">
    <div class="logo">
        <img src="../medias/logo.png" alt="Logo">
        <span>MediCare Hub</span>
    </div>

    <div class="nav-links">
        <a href="doctorDashboard.php">Dashboard</a>
        <a href="doctorAppointments.php" class="active">Appointments</a>
        <a href="doctorPatients.php">Patients</a>
        <a href="doctorReports.php">Reports</a>
    </div>

    <a class="login-btn" href="../Backend/logoutAction.php" style="Background-color:#FF2400;">Logout</a>
</div>

<div class="dashboard-container">
    <h1 class="dash-title">Upcoming Appointments</h1>

    <div class="filter-bar" style="margin-bottom:20px; display:flex; gap:10px; align-items:center;">
        <label for="filterDate">Filter by date:</label>
        <input type="date" id="filterDate">
        <button class="btn-small" id="resetFilter">Reset</button>
    </div>

    <div class="table-wrapper">
        <table class="nice-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Patient</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="appointmentsBody">
                <tr><td colspan="6">Loading...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
let allAppointments = [];

async function loadAppointments() {
    const res = await fetch("../Backend/drapi.php?type=upcoming_appointments");
    const data = await res.json();
    if (data.error) {
        document.getElementById("appointmentsBody").innerHTML =
            `<tr><td colspan="6">${data.error}</td></tr>`;
        return;
    }
    allAppointments = data;
    renderAppointments(allAppointments);
}

function renderAppointments(list) {
    const tbody = document.getElementById("appointmentsBody");
    if (!list.length) {
        tbody.innerHTML = `<tr><td colspan="6">No appointments found.</td></tr>`;
        return;
    }

    tbody.innerHTML = "";
    list.forEach(app => {
        const tr = document.createElement("tr");

        tr.innerHTML = `
            <td>${app.Appointment_Date}</td>
            <td>${app.Appointment_time.slice(0,5)}</td>
            <td>${app.First_name} ${app.Last_name}</td>
            <td>${app.Reason || "-"}</td>
            <td>${app.Status}</td>
            <td>
                <button class="btn-small" onclick="completeAppointment(${app.Appointment_id})">Complete</button>
                <button class="btn-small" onclick="cancelAppointment(${app.Appointment_id})">Cancel</button>
                <button class="btn-small" onclick="addNote(${app.Patient_id})">Add Note</button>
                <button class="btn-small" onclick="viewPatient(${app.Patient_id})">View Patient</button>
            </td>
        `;

        tbody.appendChild(tr);
    });
}

async function completeAppointment(id) {
    if (!confirm("Mark this appointment as COMPLETED?")) return;

    const body = new URLSearchParams();
    body.append("appointment_id", id);
    body.append("status", "Completed");

    const res = await fetch("../Backend/drapi.php?type=update_appointment_status", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body
    });
    const data = await res.json();
    if (data.success) loadAppointments();
    else alert(data.error || "Error updating status");
}

async function cancelAppointment(id) {
    if (!confirm("Cancel this appointment?")) return;

    const body = new URLSearchParams();
    body.append("appointment_id", id);
    body.append("status", "Cancelled");

    const res = await fetch("../Backend/drapi.php?type=update_appointment_status", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body
    });
    const data = await res.json();
    if (data.success) loadAppointments();
    else alert(data.error || "Error updating status");
}

async function addNote(patientId) {
    const note = prompt("Enter note about this patient/visit:");
    if (!note) return;

    const body = new URLSearchParams();
    body.append("patient_id", patientId);
    body.append("note", note);

    const res = await fetch("../Backend/drapi.php?type=add_note", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body
    });

    const data = await res.json();
    if (data.success) alert("Note added.");
    else alert(data.error || "Error adding note");
}

function viewPatient(patientId) {
    window.location.href = "doctorPatients.php?patient_id=" + patientId;
}

document.getElementById("filterDate").addEventListener("change", e => {
    const val = e.target.value;
    if (!val) {
        renderAppointments(allAppointments);
        return;
    }
    const filtered = allAppointments.filter(a => a.Appointment_Date === val);
    renderAppointments(filtered);
});

document.getElementById("resetFilter").addEventListener("click", () => {
    document.getElementById("filterDate").value = "";
    renderAppointments(allAppointments);
});

loadAppointments();
</script>
</body>
</html>
