<?php
session_start();
require "dbConx.php";

// Only admins can delete patients
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../admin/adminLogin.php");
    exit();
}

// Check if patient ID was provided
if (!isset($_GET["id"]) || empty($_GET["id"])) {
    die("Error: No patient ID provided.");
}

$patient_id = intval($_GET["id"]);

// Delete the patient
$stmt = $pdo->prepare("DELETE FROM Patients WHERE Patient_id = ?");
$success = $stmt->execute([$patient_id]);

if ($success) {
    // Redirect back to adminPatients.php
    header("Location: ../admin/adminPatients.php?deleted=1");
    exit();
} else {
    die("Error deleting patient.");
}
?>
