<?php
session_start(); // Start the session to access session variables user id role etc...
require "dbconx.php"; //doesnot run if dbconx.php(cinnection to the db) fails

if (!isset($_SESSION["id"]) || $_SESSION["role"] !== "patient") {
    header("Location:../front/login.php");
    exit();//confirm user is logged in and if he is as patient if not takes him to login page
}

if (!isset($_GET["id"])) {
    die("Invalid request.");
}// Check if appointment id is provided if not, terminate with an error message

$app_id = intval($_GET["id"]);
$patient_id = $_SESSION["id"];

// Only allow the patient to delete THEIR OWN appointment
$stmt = $pdo->prepare("DELETE FROM Appointment 
                       WHERE Appointment_id = ? AND Patient_id = ?");
$stmt->execute([$app_id, $patient_id]);

header("Location: ../front/viewAppointments.php");
exit();
