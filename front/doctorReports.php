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
    <title>Doctor Reports</title>

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
        <a href="doctorAppointments.php">Appointments</a>
        <a href="doctorPatients.php">Patients</a>
        <a href="doctorReports.php" class="active">Reports</a>
    </div>

    <a class="login-btn" href="../Backend/logoutAction.php" style="Background-color:#FF2400;">Logout</a>
</div>

<div class="dashboard-container">
    <h1 class="dash-title">Lab Reports & Tests</h1>

    <div style="margin-bottom:15px; display:flex; gap:10px;">
        <button class="btn-small" id="showPending">Show Pending Tests</button>
        <button class="btn-small" id="showAll">Show All Tests</button>
    </div>

    <div class="table-wrapper">
        <table class="nice-table">
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Test</th>
                    <th>Date</th>
                    <th>Result</th>
                    <th>Attachment</th>
                    <th>Doctor Report</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="testsBody">
                <tr><td colspan="7">Choose a view...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
let currentMode = "pending"; // "pending" or "all";

async function loadTests() {
    let type = currentMode === "pending" ? "pending_tests" : "all_tests";
    const res = await fetch("../Backend/drapi.php?type=" + type);
    const data = await res.json();

    const tbody = document.getElementById("testsBody");
    if (!data || data.error) {
        tbody.innerHTML = `<tr><td colspan="7">${data.error || "No data."}</td></tr>`;
        return;
    }
    if (!data.length) {
        tbody.innerHTML = `<tr><td colspan="7">No tests found.</td></tr>`;
        return;
    }

    tbody.innerHTML = "";
    data.forEach(t => {
        const tr = document.createElement("tr");

        const safeReport = t.Doctor_Report || "";

        tr.innerHTML = `
            <td>${t.First_name} ${t.Last_name}</td>
            <td>${t.Test_name}</td>
            <td>${t.Test_date}</td>
            <td>${t.Result || "-"}</td>
            <td>
                ${t.Attachment ? `<a href="../${t.Attachment}" target="_blank">Open</a>` : "-"}
            </td>
            <td>
                <textarea data-test-id="${t.Test_id}" rows="2" style="width:100%;">${safeReport}</textarea>
            </td>
            <td>
                <button class="btn-small" onclick="saveReport(${t.Test_id})">Save</button>
                <button class="btn-small" onclick="viewPatient(${t.Patient_id})">Patient</button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

async function saveReport(testId) {
    const textarea = document.querySelector(`textarea[data-test-id="${testId}"]`);
    if (!textarea) return;

    const report = textarea.value.trim();
    const body = new URLSearchParams();
    body.append("test_id", testId);
    body.append("doctor_report", report);

    const res = await fetch("../Backend/drapi.php?type=update_test_report", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body
    });
    const data = await res.json();
    if (data.success) {
        alert("Report saved.");
        if (currentMode === "pending") loadTests(); // refresh list (might move from pending)
    } else {
        alert(data.error || "Error saving report.");
    }
}

function viewPatient(patientId) {
    window.location.href = "doctorPatients.php?patient_id=" + patientId;
}

document.getElementById("showPending").addEventListener("click", () => {
    currentMode = "pending";
    loadTests();
});
document.getElementById("showAll").addEventListener("click", () => {
    currentMode = "all";
    loadTests();
});

// default
currentMode = "pending";
loadTests();
</script>

</body>
</html>
