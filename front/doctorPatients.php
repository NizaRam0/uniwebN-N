<?php
session_start();
require "../Backend/dbconx.php";

if (!isset($_SESSION["loggedIn"]) || $_SESSION["loggedIn"] !== true || $_SESSION["role"] !== "doctor") {
    header("Location: login.php");
    exit();
}

$selectedPatientId = isset($_GET["patient_id"]) ? (int)$_GET["patient_id"] : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Patients</title>

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
        <a href="doctorPatients.php" class="active">Patients</a>
        <a href="doctorReports.php">Reports</a>
    </div>

    <a class="login-btn" href="../Backend/logoutAction.php" style="Background-color:#FF2400;">Logout</a>
</div>

<div class="dashboard-container">
    <h1 class="dash-title">My Patients</h1>

    <div style="display:flex; gap:20px; align-items:flex-start; flex-wrap:wrap;">

        <!-- LEFT: Search + list -->
        <div style="flex:1; min-width:280px;">
            <div class="filter-bar" style="margin-bottom:15px; display:flex; gap:8px;">
                <input type="text" id="searchInput" placeholder="Search by name or DOB (YYYY-MM-DD)" style="flex:1; padding:8px;">
                <button class="btn-small" id="searchBtn">Search</button>
                <button class="btn-small" id="loadMyPatientsBtn">My Patients</button>
            </div>

            <div class="table-wrapper">
                <table class="nice-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>DOB</th>
                            <th>Blood</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="patientsBody">
                        <tr><td colspan="4">Search or load patients...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- RIGHT: Patient details -->
        <div style="flex:1.3; min-width:320px;">
            <div id="patientDetails" class="dash-card " style=" min-width:420px;">
                <h3>Patient Details</h3>
                <div id="patientBasics">Select a patient...</div>

                <hr>

                <h4>Medical Records (Notes)</h4>
                <div id="recordsList">-</div>

                <form id="addNoteForm" style="margin-top:10px;">
                    <textarea id="noteText" rows="2" placeholder="Add new note..." style="width:100%;"></textarea>
                    <button type="submit" class="btn-small" style="margin-top:5px;">Add Note</button>
                </form>

                <hr>

                <h4>Medications</h4>
                <div id="medsList">-</div>

                <form id="addMedForm" style="margin-top:10px;">
                    <input type="text" id="medName" placeholder="Medication name" style="width:100%; margin-bottom:5px;">
                    <input type="text" id="medDose" placeholder="Dosage" style="width:100%; margin-bottom:5px;">
                    <textarea id="medInstr" rows="2" placeholder="Instructions" style="width:100%;"></textarea>
                    <button type="submit" class="btn-small" style="margin-top:5px;">Add Medication</button>
                </form>

                <hr>

                <h4>Tests</h4>
                <div id="testsList">-</div>

                <form id="addTestForm" style="margin-top:10px;" enctype="multipart/form-data">
                    <input type="text" id="testName" placeholder="Test name" style="width:100%; margin-bottom:5px;">
                    <textarea id="testResult" rows="2" placeholder="Result (text)" style="width:100%; margin-bottom:5px;"></textarea>
                    <textarea id="testReport" rows="2" placeholder="Doctor report (optional)" style="width:100%; margin-bottom:5px;"></textarea>
                    <input type="file" id="testFile" style="width:100%; margin-bottom:5px;">
                    <button type="submit" class="btn-small">Upload Test</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let currentPatientId = <?php echo $selectedPatientId ?: 0; ?>;

async function searchPatients() {
    const q = document.getElementById("searchInput").value.trim();
    const res = await fetch("../Backend/drapi.php?type=search_patient&q=" + encodeURIComponent(q));
    const data = await res.json();
    renderPatientsList(data);
}

async function loadMyPatients() {
    const res = await fetch("../Backend/drapi.php?type=doctor_patients");
    const data = await res.json();
    renderPatientsList(data);
}

function renderPatientsList(list) {
    const tbody = document.getElementById("patientsBody");
    if (!list || !list.length) {
        tbody.innerHTML = `<tr><td colspan="4">No patients found.</td></tr>`;
        return;
    }

    tbody.innerHTML = "";
    list.forEach(p => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td>${p.First_name} ${p.Last_name}</td>
            <td>${p.Date_of_birth}</td>
            <td>${p.Blood_type}</td>
            <td><button class="btn-small" onclick="selectPatient(${p.Patient_id})">View</button></td>
        `;
        tbody.appendChild(tr);
    });
}

async function selectPatient(patientId) {
    currentPatientId = patientId;
    await Promise.all([
        loadPatientBasics(),
        loadRecords(),
        loadMedications(),
        loadTests()
    ]);
}

async function loadPatientBasics() {
    if (!currentPatientId) {
        document.getElementById("patientBasics").innerHTML = "Select a patient...";
        return;
    }
    const res = await fetch("../Backend/drapi.php?type=patient_profile&patient_id=" + currentPatientId);
    const p = await res.json();
    if (!p || p.error) {
        document.getElementById("patientBasics").innerHTML = p.error || "Error loading patient.";
        return;
    }

    document.getElementById("patientBasics").innerHTML = `
        <strong>${p.First_name} ${p.Last_name}</strong><br>
        DOB: ${p.Date_of_birth} | Age: ${p.Age}<br>
        Gender: ${p.Gender} | Blood: ${p.Blood_type}<br>
        Conditions: ${p.Pre_existing_condition || "-"}<br>
        Email: ${p.Email}
    `;
}

async function loadRecords() {
    if (!currentPatientId) return;
    const res = await fetch("../Backend/drapi.php?type=patient_records&patient_id=" + currentPatientId);
    const list = await res.json();

    const container = document.getElementById("recordsList");
    if (!list.length) {
        container.innerHTML = "No records yet.";
        return;
    }
    container.innerHTML = list.map(r => `
        <div style="margin-bottom:6px;">
            <strong>${r.Date}</strong> - ${r.Notes}
        </div>
    `).join("");
}

async function loadMedications() {
    if (!currentPatientId) return;
    const res = await fetch("../Backend/drapi.php?type=patient_medications&patient_id=" + currentPatientId);
    const list = await res.json();

    const container = document.getElementById("medsList");
    if (!list.length) {
        container.innerHTML = "No medications yet.";
        return;
    }

    container.innerHTML = list.map(m => `
        <div style="margin-bottom:6px;">
            <strong>${m.Medication_name}</strong> (${m.Dosage}) - ${m.Status}<br>
            <em>${m.Instructions}</em><br>
            Date: ${m.Date_prescribed}
        </div>
    `).join("");
}

async function loadTests() {
    if (!currentPatientId) return;
    const res = await fetch("../Backend/drapi.php?type=patient_tests&patient_id=" + currentPatientId);
    const list = await res.json();

    const container = document.getElementById("testsList");
    if (!list.length) {
        container.innerHTML = "No tests yet.";
        return;
    }

    container.innerHTML = list.map(t => `
        <div style="margin-bottom:8px;">
            <strong>${t.Test_name}</strong> (${t.Test_date})<br>
            Result: ${t.Result || "-"}<br>
            Doctor Report: ${t.Doctor_Report || "<em>None</em>"}<br>
            ${t.Attachment ? `<a href="../${t.Attachment}" target="_blank">View Attachment</a>` : ""}
        </div>
    `).join("");
}

// Forms

document.getElementById("addNoteForm").addEventListener("submit", async e => {
    e.preventDefault();
    if (!currentPatientId) return alert("Select a patient first.");
    const note = document.getElementById("noteText").value.trim();
    if (!note) return;

    const body = new URLSearchParams();
    body.append("patient_id", currentPatientId);
    body.append("note", note);

    const res = await fetch("../Backend/drapi.php?type=add_note", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body
    });
    const data = await res.json();
    if (data.success) {
        document.getElementById("noteText").value = "";
        loadRecords();
    } else alert(data.error || "Error adding note");
});

document.getElementById("addMedForm").addEventListener("submit", async e => {
    e.preventDefault();
    if (!currentPatientId) return alert("Select a patient first.");

    const name = document.getElementById("medName").value.trim();
    const dose = document.getElementById("medDose").value.trim();
    const instr = document.getElementById("medInstr").value.trim();

    if (!name || !dose) return alert("Name and dosage are required.");

    const body = new URLSearchParams();
    body.append("patient_id", currentPatientId);
    body.append("name", name);
    body.append("dose", dose);
    body.append("instructions", instr);

    const res = await fetch("../Backend/drapi.php?type=add_medication", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body
    });
    const data = await res.json();
    if (data.success) {
        document.getElementById("medName").value = "";
        document.getElementById("medDose").value = "";
        document.getElementById("medInstr").value = "";
        loadMedications();
    } else alert(data.error || "Error adding medication");
});

document.getElementById("addTestForm").addEventListener("submit", async e => {
    e.preventDefault();
    if (!currentPatientId) return alert("Select a patient first.");

    const testName = document.getElementById("testName").value.trim();
    const resultText = document.getElementById("testResult").value.trim();
    const reportText = document.getElementById("testReport").value.trim();
    const fileInput = document.getElementById("testFile");

    if (!testName) return alert("Test name is required.");

    const formData = new FormData();
    formData.append("patient_id", currentPatientId);
    formData.append("test_name", testName);
    formData.append("result", resultText);
    formData.append("doctor_report", reportText);
    if (fileInput.files[0]) {
        formData.append("attachment", fileInput.files[0]);
    }

    const res = await fetch("../Backend/drapi.php?type=add_test", {
        method: "POST",
        body: formData
    });
    const data = await res.json();
    if (data.success) {
        document.getElementById("testName").value = "";
        document.getElementById("testResult").value = "";
        document.getElementById("testReport").value = "";
        document.getElementById("testFile").value = "";
        loadTests();
    } else alert(data.error || "Error uploading test");
});

// Buttons
document.getElementById("searchBtn").addEventListener("click", searchPatients);
document.getElementById("loadMyPatientsBtn").addEventListener("click", loadMyPatients);

// Auto-load if patient_id in URL
if (currentPatientId) {
    selectPatient(currentPatientId);
}

</script>
</body>
</html>
