<?php
session_start();
require "../Backend/dbconx.php";

if (!isset($_SESSION["loggedIn"]) || $_SESSION["role"] !== "patient") {
    header("Location: login.php");
    exit();
}

if (!isset($_GET["id"])) {
    die("Invalid request.");
}

$app_id = intval($_GET["id"]);
$patient_id = $_SESSION["id"];

// Only allow the patient to delete THEIR OWN appointment
$stmt = $pdo->prepare("DELETE FROM Appointment 
                       WHERE Appointment_id = ? AND Patient_id = ?");
$stmt->execute([$app_id, $patient_id]);

header("Location: ../front/viewAppointments.php");
exit();
